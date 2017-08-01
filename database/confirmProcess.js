/**
 * Created by Desarrollo on 02/09/2016.
 */
/*
db.tasks.find({id_process:DBRef("processes",ObjectId("57dbeb44e1382342a43aecc1"))}).count()
db.routes.find({id_process:DBRef("processes",ObjectId("57dbeb44e1382342a43aecc1"))}).count()
db.orders.find({id_process:DBRef("processes",ObjectId("57dbeb44e1382342a43aecc1"))}).count()

 db.tasks.remove({id_process:DBRef("processes",ObjectId("57dbeb44e1382342a43aecc1"))})
 db.routes.remove({id_process:DBRef("processes",ObjectId("57dbeb44e1382342a43aecc1"))})
 db.orders.remove({id_process:DBRef("processes",ObjectId("57dbeb44e1382342a43aecc1"))})

 db.loadServerScripts();
 confirmProcess("57dbeb44e1382342a43aecc1");
 */
db.system.js.save(
    {
        _id: "confirmProcess",
        value: function (idProcess) {
            //Que sea objeto id
            idProcess = new ObjectId(idProcess);

            //use fieldvision_alpha;
            //Recuperar proceso
            var processArray = db.processes.find({_id:idProcess},{'_id':1,'id_company':1,'targetDate':1,'resourceDefinitions':1,'resourceGroups':1,'resourceInstances':1}).limit(1).toArray();
            var process = processArray[0];
            var today = new Date(process.targetDate.getTime());
            print('today ' + today);
            var tomorrow = new Date(today.getTime() + 1000 * 3600 * 24 * 1);
            print('tomorrow '+ tomorrow);

            //Recuperar StatusConfiguration de la empresa a la que pertenece el proceso
            var companyDBRef = process.id_company;
            var companyArray = db.companies.find({_id:companyDBRef.$id},{'statusConfigurations.status':1,'statusConfigurations.reasons':1}).limit(1).toArray();
            var companyArray = db.companies.find({_id:companyDBRef.$id},{'statusConfigurations.status':1,'statusConfigurations.reasons':1}).limit(1).toArray();
            var company = companyArray[0];
            var statusConfigurations = company.statusConfigurations;
            print('statusConfigurations '+ statusConfigurations);

            //Recuperar recursos que afecta el proceso
            var and = [];

            //Convertir DBRefs a IDs
            var resourceDefinitionDBRefs = process.resourceDefinitions;
            print('resourceDefinitionDBRefs '+ resourceDefinitionDBRefs);
            var resourceDefinitionIds = [];
            var i = 0;
            for (i = 0; i < resourceDefinitionDBRefs.length; i++) {
                resourceDefinitionIds.push(resourceDefinitionDBRefs[i].$id);
            }
            print('resourceDefinitionIds '+ resourceDefinitionIds);
            if(resourceDefinitionDBRefs.length > 0)
            {
                and.push({ 'id_resourceDefinition': { $in: resourceDefinitionDBRefs } });
            }

            var resourceGroupDBRefs = process.resourceGroups;
            print('resourceGroupDBRefs '+ resourceGroupDBRefs);
            var resourceGroupIds = [];
            i = 0;
            for (i = 0; i < resourceGroupDBRefs.length; i++) {
                resourceGroupIds.push(resourceGroupDBRefs[i].$id);
            }
            print('resourceGroupIds '+ resourceGroupIds);
            if(resourceGroupIds.length > 0)
            {
                and.push({ 'resourceGroups': { $in: resourceGroupDBRefs } });
            }

            var excludedResourceInstanceDBRefs = process.resourceInstances;
            print('excludedResourceInstanceDBRefs '+ excludedResourceInstanceDBRefs);
            var excludedResourceInstances = [];
            var i = 0;
            for (i = 0; i < excludedResourceInstanceDBRefs.length; i++) {
                excludedResourceInstances.push(excludedResourceInstanceDBRefs[i].$id);
            }
            print('excludedResourceInstances '+ excludedResourceInstances);
            if(excludedResourceInstances.length > 0)
            {
                and.push({ '_id': { $nin: excludedResourceInstances } });
            }

            //Encontrar resourceInstances
            var resourceInstanceIds = [];
            var resourceInstanceDBRefs = [];
            var resourceInstances = db.resourceInstances.find(
                {
                    $and: and
                },
                {
                    '_id': 1
                }
            ).toArray();

            i = 0;
            for (i = 0; i < resourceInstances.length; i++) {
                resourceInstanceDBRefs.push(DBRef("resourceInstances",resourceInstances[i]._id));
                resourceInstanceIds.push(resourceInstances[i]._id);
            }
            print('resourceInstanceDBRefs ' + resourceInstanceDBRefs);
            print('resourceInstanceIds ' + resourceInstanceIds);

            //Recuperar los id de los procesos de las tareas que se deben borrar
            var processDBRefs = db.tasks.distinct("id_process",
                {
                    $and:[
                        {'id_resourceInstance': { $in: resourceInstanceDBRefs }},
                        {'arrival_time': { $gte: today }},
                        {'finish_time': { $lt: tomorrow }},
                    ]
                }
            );

            print('processDBRefs ' + processDBRefs);

            //Borrar tareas
            db.tasks.update(
                {
                    $and:[
                        {'id_resourceInstance': { $in: resourceInstanceDBRefs }},
                        {'arrival_time': { $gte: today }},
                        {'finish_time': { $lt: tomorrow }},
                    ]
                },
                {
                    $currentDate: { 'deleted_at': true }
                },
                {
                    multi:true
                }
            );


            //Insertar tareas
            db.temporalTasks.find({$and:[{'id_process':DBRef("processes", idProcess)},{'status':'SCHEDULED'}]}).forEach(
                function(temporalTask){
                    temporalTask.status = "PENDIENTE";

                    try {
                        temporalTask.id_order = DBRef("orders",ObjectId(temporalTask.idOrderFinal));
                    } catch (err) {
                        print(err);
                    }

                    delete temporalTask.msgStatus;
                    delete temporalTask.idOrderFinal;

                    var arrival_time = new Date(temporalTask.arrival_time.getTime());
                    var finish_time = new Date(temporalTask.finish_time.getTime());

                    temporalTask.arrival_time = arrival_time;
                    temporalTask.finish_time = finish_time;

                    temporalTask.statusConfigurations = statusConfigurations;

                    db.tasks.insert(temporalTask);
                }
            );

            //Borrar rutas
            db.routes.update(
                {
                    $and:[
                        {'resourceInstance._id': { $in: resourceInstanceIds }},
                        {'date': { $gte: today }},
                        {'date': { $lt: tomorrow }},
                    ]
                },
                {
                    $currentDate: { 'deleted_at': true }
                },
                {
                    multi:true
                }
            );

            //Insertar rutas
            db.temporalRoutes.find({'id_process':DBRef("processes", idProcess)}).forEach(
                function(temporalRoute){
                    temporalRoute._id = new ObjectId();
                    temporalRoute.statistics = {totalPending:temporalRoute.tasks.length, totalCheckin:0, totalCancelled:0, totalCheckoutWithoutForm:0, totalCheckoutWithForm:0, totalApproved:0};
                    db.routes.insert(temporalRoute);
                }
            );

            //Borrar ordenes
            db.orders.update(
                {
                    'id_process': { $in: processDBRefs }
                },
                {
                    $currentDate: { 'deleted_at': true }
                },
                {
                    multi:true
                }
            );

            //Insertar ordenes
            db.temporalOrders.find({'id_process':DBRef("processes", idProcess)}).forEach(
                function(temporalOrder){

                    try {
                        temporalOrder._id = new ObjectId(temporalOrder.idOrderFinal);
                    } catch (err) {
                        print(err);
                        temporalOrder._id = new ObjectId();
                    }

                    var date = new Date(temporalOrder.date.getTime());
                    temporalOrder.date = date;

                    db.orders.insert(temporalOrder);
                }
            );

            return 0;
        }
    }
);