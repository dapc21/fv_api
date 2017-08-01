//Ejemplo: $>mongo localhost:27017/fieldvision bashMongoFieldVision-v1.0.0.js
//mongoimport --db fieldvision --verbose --collection countries --drop --headerline --type csv --file C:\Users\Desarrollo\Desktop\ciudades\paises.csv
//mongoimport --db fieldvision --verbose --collection cities --drop --headerline --type csv --file C:\Users\Desarrollo\Desktop\ciudades\ciudades.csv

//Borrando la BD
db.dropDatabase();
print("Borrando la BD");

//Aplicaciones
print("Insertando las aplicaciones");
//Son como templates bases para luego instanciamos en los roles de acuerdo a las necesidades
db.applications.insert(
[
    {
        "_id" : ObjectId("574f7528214c9d7b9fb65950"),
        "name" : "API",
        "modules" : [
            {
                "name" : "users",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "users" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true,
                        "restore" : {
                            "PUT" : true
                        },
                        "changepassword" : {
                            "PUT" : true
                        },
                        "resetpassword" : {
                            "GET" : true,
                            "PUT" : true,
                            "POST" : true
                        }
                    }
                }
            },
            {
                "name" : "roles",
                "actions" : {
                    "GET" : true
                }
            },
            {
                "name" : "companies",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "companies" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true,
                        "licenses" : {
                            "GET" : true,
                            "POST" : true,
                            "strIdLicense" : {
                                "GET" : true,
                                "PUT" : true,
                                "DELETE" : true
                            }
                        }
                    }
                }
            },
            {
                "name" : "applications",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "applications" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true
                    }
                }
            },
            {
                "name" : "devicesdefinitions",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "devicesdefinitions" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true
                    }
                }
            },
            {
                "name" : "devicesinstances",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "devicesinstances" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true
                    }
                }
            },
            {
                "name" : "resourcesdefinitions",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "resourcesdefinitions" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true
                    }
                }
            },
            {
                "name" : "resourcesinstances",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "resourcesinstances" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true
                    }
                }
            },
            {
                "name" : "tracking",
                "actions" : {
                    "actual" : {
                        "GET" : true
                    },
                    "events" : {
                        "GET" : true
                    },
                    "history" : {
                        "GET" : true
                    }
                }
            },
            {
                "name" : "tasks",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "tasks" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true,
                        "cancel" : {
                            "PUT" : true
                        }
                    }
                }
            },
            {
                "name" : "resourcegroups",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "resourcegroups" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true
                    }
                }
            },
            {
                "name" : "geofences",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "geofences" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true
                    }
                }
            },
            {
                "name" : "checkpoints",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "checkpoints" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true
                    }
                }
            },
            {
                "name" : "speedlimits",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "speedlimits" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true
                    }
                }
            },
            {
                "name" : "messages",
                "actions" : {
                    "GET" : true,
                    "POST" : true,
                    "messages" : {
                        "GET" : true,
                        "PUT" : true,
                        "DELETE" : true
                    }
                }
            },
            {
                "name" : "addresses",
                "actions" : {
                    "validate" : {
                        "GET" : true
                    }
                }
            },
            {
                "name" : "login",
				"actions" : {
					"POST" : true
				}
            }
        ]
    },
    {
        "_id" : ObjectId("574f7528214c9d7b9fb65951"),
        "name" : "WEB",
        "modules" : [
            {
                "name" : "users",
                "actions" : {
                    "CREATE" : true,
                    "READ" : true,
                    "UPDATE" : true,
                    "DELETE" : true
                }
            },
            {
                "name" : "companies",
                "actions" : {
                    "CREATE" : true,
                    "READ" : true,
                    "UPDATE" : true,
                    "DELETE" : true
                }
            },
            {
                "name" : "applications",
                "actions" : {
                    "READ" : true,
                }
            },
            {
                "name" : "devicesdefinitions",
                "actions" : {
                    "READ" : true
                }
            },
            {
                "name" : "devicesinstances",
                "actions" : {
                    "CREATE" : true,
                    "READ" : true,
                    "UPDATE" : true,
                    "DELETE" : true
                }
            },
            {
                "name" : "resourcesdefinitions",
                "actions" : {
                    "CREATE" : true,
                    "READ" : true,
                    "UPDATE" : true,
                    "DELETE" : true
                }
            },
            {
                "name" : "resourcesinstances",
                "actions" : {
                    "CREATE" : true,
                    "READ" : true,
                    "UPDATE" : true,
                    "DELETE" : true
                }
            },
            {
                "name" : "tracking",
                "actions" : {
                    "READ" : true
                }
            },
            {
                "name" : "tasks",
                "actions" : {
                    "READ" : true
                }
            },
            {
                "name" : "resourcegroups",
                "actions" : {
                    "READ" : true
                }
            },
            {
                "name" : "geofences",
                "actions" : {
                    "CREATE" : true,
                    "READ" : true,
                    "UPDATE" : true,
                    "DELETE" : true
                }
            },
            {
                "name" : "checkpoints",
                "actions" : {
                    "CREATE" : true,
                    "READ" : true,
                    "UPDATE" : true,
                    "DELETE" : true
                }
            },
            {
                "name" : "speedlimits",
                "actions" : {
                    "CREATE" : true,
                    "READ" : true,
                    "UPDATE" : true,
                    "DELETE" : true
                }
            },
            {
                "name" : "messages",
                "actions" : {
                    "CREATE" : true,
                    "READ" : true,
                    "UPDATE" : true,
                    "DELETE" : true
                }
            },
            {
                "name" : "login",
				"actions" : {
					"POST" : true
				}
            }
        ]
    },
    {
        "_id" : ObjectId("574f7528214c9d7b9fb65952"),
        "name" : "com.datatraffic.formulariodinamico.app",
        "modules" : [
            {
                "name" : "login",
				"actions" : {
					"POST" : true
				}
            }
		]
	}
]
);

print("Cargadas todas las aplicaciones");

//Compañias
print("Insertando las compañias");
db.companies.insert(
[
    {
        "_id" : ObjectId("56f55709efe9e8d575768a54"),
        "name" : "Datatraffic S.A.S.",
        "status" : "active",
        "nit" : "23434324",
        "address" : "Carera 47 A No 91 91",
        "city" : "Bogotá",
        "phone" : "7426160",
        "legalRepresentativeName" : "Sergio",
        "legalRepresentativeLastName" : "Sinuco",
        "legalRepresentativeId" : "104510400",
        "legalRepresentativePhone" : "31088842650",
        "id_country":DBRef("countries",ObjectId("577fdd84904e507c5b83032b")),
        "licenses" : [
            {
                "_id" : ObjectId("56f55709efe9e8d575768a23"),
                "application" : {
                    "_id" : ObjectId("56f55709efe9e8d575768a14"),
                    "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65950")),
                    "name" : "API",
                    "modules" : [
                        {
                            "name" : "users"
                        },
                        {
                            "name" : "roles"
                        },
                        {
                            "name" : "companies"
                        },
                        {
                            "name" : "applications"
                        },
                        {
                            "name" : "devicedefinitions"
                        },
                        {
                            "name" : "deviceinstances"
                        },
                        {
                            "name" : "resourcetemplates"
                        },
                        {
                            "name" : "resourcedefinitions"
                        },
                        {
                            "name" : "resourceinstances"
                        },
                        {
                            "name" : "actualresourceinstance"
                        },
                        {
                            "name" : "tracking"
                        },
                        {
                            "name" : "tasks"
                        },
                        {
                            "name" : "resourcegroups"
                        },
                        {
                            "name" : "geofences"
                        },
                        {
                            "name" : "checkpoints"
                        },
                        {
                            "name" : "speedlimits"
                        },
                        {
                            "name" : "messages"
                        },
                        {
                            "name" : "addresses"
                        },
                        {
                            "name" : "forms"
                        },
                        {
                            "name" : "sections"
                        },
                        {
                            "name" : "export"
                        },
                        {
                            "name" : "cities"
                        },
                        {
                            "name" : "countries"
                        },
                        {
                            "name" : "batch"
                        }
                    ]
                }
            },
            {
                "_id" : ObjectId("56f55709efe9e8d575768a24"),
                "application" : {
                    "_id" : ObjectId("56f55709efe9e8d575768a15"),
                    "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65951")),
                    "name" : "WEB",
                    "modules" : [
                        {
                            "name" : "users"
                        },
                        {
                            "name" : "roles"
                        },
                        {
                            "name" : "companies"
                        },
                        {
                            "name" : "applications"
                        },
                        {
                            "name" : "devicedefinitions"
                        },
                        {
                            "name" : "deviceinstances"
                        },
                        {
                            "name" : "resourcetemplates"
                        },
                        {
                            "name" : "resourcedefinitions"
                        },
                        {
                            "name" : "resourceinstances"
                        },
                        {
                            "name" : "tracking"
                        },
                        {
                            "name" : "tasks"
                        },
                        {
                            "name" : "resourcegroups"
                        },
                        {
                            "name" : "geofences"
                        },
                        {
                            "name" : "checkpoints"
                        },
                        {
                            "name" : "speedlimits"
                        },
                        {
                            "name" : "messages"
                        },
                        {
                            "name" : "campaigns"
                        },
                        {
                            "name" : "forms"
                        },
                        {
                            "name" : "export"
                        },
                        {
                            "name" : "login"
                        },
                    ]
                }
            },
            {
                "_id" : ObjectId("56f55709efe9e8d575768a25"),
                "application" : {
                    "_id" : ObjectId("56f55709efe9e8d575768a15"),
                    "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65952")),
                    "name" : "com.datatraffic.formulariodinamico.app",
                    "modules" : [
                        {
                            "name" : "login"
                        }
					]
				}
			}				
		]
    },
    {
        "_id" : ObjectId("56f55709efe9e8d575768a55"),
        "name" : "Itelca S.A.S.",
        "status" : "active",
        "nit" : "7808700",
        "address" : "Calle 94 No 47 25",
        "city" : "Bogotá",
        "phone" : "2194100",
        "legalRepresentativeName" : "Pepe",
        "legalRepresentativeLastName" : "Perez",
        "legalRepresentativeId" : "87045000",
        "legalRepresentativePhone" : "980001445",
        "id_country":DBRef("countries",ObjectId("577fdd84904e507c5b83032b")),
        "licenses" : [
            {
                "_id" : ObjectId("56f55709efe9e8d575768a25"),
                "application" : {
                    "_id" : ObjectId("56f55709efe9e8d575768a16"),
                    "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65950")),
                    "name" : "API",
                    "modules" : [
                        {
                            "name" : "users"
                        },
                        {
                            "name" : "roles"
                        },
                        {
                            "name" : "companies"
                        },
                        {
                            "name" : "applications"
                        },
                        {
                            "name" : "devicedefinitions"
                        },
                        {
                            "name" : "deviceinstances"
                        },
                        {
                            "name" : "resourcetemplates"
                        },
                        {
                            "name" : "resourcedefinitions"
                        },
                        {
                            "name" : "resourceinstances"
                        },
                        {
                            "name" : "actualresourceinstance"
                        },
                        {
                            "name" : "tracking"
                        },
                        {
                            "name" : "tasks"
                        },
                        {
                            "name" : "resourcegroups"
                        },
                        {
                            "name" : "geofences"
                        },
                        {
                            "name" : "checkpoints"
                        },
                        {
                            "name" : "speedlimits"
                        },
                        {
                            "name" : "messages"
                        },
                        {
                            "name" : "addresses"
                        },
                        {
                            "name" : "forms"
                        },
                        {
                            "name" : "sections"
                        },
                        {
                            "name" : "export"
                        },
                        {
                            "name" : "cities"
                        },
                        {
                            "name" : "countries"
                        },
                        {
                            "name" : "batch"
                        }
                    ]
                }
            },
            {
                "_id" : ObjectId("56f55709efe9e8d575768a26"),
                "application" : {
                    "_id" : ObjectId("56f55709efe9e8d575768a17"),
                    "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65951")),
                    "name" : "WEB",
                    "modules" : [
                        {
                            "name" : "users"
                        },
                        {
                            "name" : "roles"
                        },
                        {
                            "name" : "companies"
                        },
                        {
                            "name" : "applications"
                        },
                        {
                            "name" : "devicedefinitions"
                        },
                        {
                            "name" : "deviceinstances"
                        },
                        {
                            "name" : "resourcetemplates"
                        },
                        {
                            "name" : "resourcedefinitions"
                        },
                        {
                            "name" : "resourceinstances"
                        },
                        {
                            "name" : "tracking"
                        },
                        {
                            "name" : "tasks"
                        },
                        {
                            "name" : "resourcegroups"
                        },
                        {
                            "name" : "geofences"
                        },
                        {
                            "name" : "checkpoints"
                        },
                        {
                            "name" : "speedlimits"
                        },
                        {
                            "name" : "messages"
                        },
                        {
                            "name" : "campaigns"
                        },
                        {
                            "name" : "forms"
                        },
                        {
                            "name" : "export"
                        },
                        {
                            "name" : "login"
                        },
                    ]
                }
            },
            {
                "_id" : ObjectId("56f55709efe9e8d575768a25"),
                "application" : {
                    "_id" : ObjectId("56f55709efe9e8d575768a15"),
                    "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65952")),
                    "name" : "com.datatraffic.formulariodinamico.app",
                    "modules" : [
                        {
                            "name" : "login"
                        }
					]
				}
			}
        ]
    }
]
);
print("Cargadas todas las compañias")

//Roles
print("Insertando los roles");
db.roles.insert(
[
    {
        "_id" : ObjectId("5748b2d61d69b01b2800702f"),
        "name" : "Administrator API",
        "updated_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "created_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "application" : {
            "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65950")),
            "name" : "API",
            "modules" : [
                {
                    "name" : "users",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "users" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true,
                            "restore" : {
                                "PUT" : true
                            },
                            "changepassword" : {
                                "PUT" : true
                            },
                            "resetpassword" : {
                                "GET" : true,
                                "PUT" : true,
                                "POST" : true
                            }
                        }
                    }
                },
                {
                    "name" : "roles",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "companies",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "companies" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true,
                            "licenses" : {
                                "GET" : true,
                                "POST" : true,
                                "strIdLicense" : {
                                    "GET" : true,
                                    "PUT" : true,
                                    "DELETE" : true
                                }
                            }
                        }
                    }
                },
                {
                    "name" : "applications",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "applications" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true
                        }
                    }
                },
                {
                    "name" : "devicedefinitions",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "devicedefinitions" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true
                        }
                    }
                },
                {
                    "name" : "deviceinstances",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "deviceinstances" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true
                        },
                        "campaigns":{
                            "GET" : true,
                        }
                    }
                },
                {
                    "name" : "resourcetemplates",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "resourcetemplates" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true
                        }
                    }
                },
                {
                    "name" : "resourcedefinitions",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "resourcedefinitions" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true
                        }
                    }
                },
                {
                    "name" : "resourceinstances",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "resourceinstances" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true,
                            "restore" : {
                                "PUT" : true
                            },
                            "changepassword" : {
                                "PUT" : true
                            },
                            "resetpassword" : {
                                "GET" : true,
                                "PUT" : true,
                                "POST" : true
                            }
                        }
                    }
                },
                {
                    "name":"actualresourceinstance",
                    "actions" : {
                        "GET" : true,
                        "campaigns":{
                            "GET" : true,
                        }
                    }
                },
                {
                    "name" : "tracking",
                    "actions" : {
                        "actual" : {
                            "GET" : true
                        },
                        "events" : {
                            "GET" : true,
                            "POST" : true
                        },
                        "history" : {
                            "GET" : true
                        },
                        "positions" : {
                            "POST" : true
                        }
                    }
                },
                {
                    "name" : "tasks",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "tasks" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true,
                            "cancel" : {
                                "PUT" : true
                            }
                        }
                    }
                },
                {
                    "name" : "resourcegroups",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "resourcegroups" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true
                        }
                    }
                },
                {
                    "name" : "geofences",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "geofences" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true
                        }
                    }
                },
                {
                    "name" : "checkpoints",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "checkpoints" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true
                        }
                    }
                },
                {
                    "name" : "speedlimits",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "speedlimits" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true
                        }
                    }
                },
                {
                    "name" : "messages",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "messages" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true
                        }
                    }
                },
                {
                    "name" : "addresses",
                    "actions" : {
                        "validate" : {
                            "GET" : true
                        }
                    }
                },
                {
                    "name" : "forms",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "forms" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true,
                        }
                    }
                },
                {
                    "name" : "sections",
                    "actions" : {
                        "GET" : true,
                        "POST" : true,
                        "sections" : {
                            "GET" : true,
                            "PUT" : true,
                            "DELETE" : true,
                        }
                    }
                },
                {
                    "name" : "export",
                    "actions" : {
                        "registers" : {
                            "GET" : true
                        }
                    }
                },
                {
                    "name" : "positions",
                    "actions" : {
                        "POST" : true
                    }
                },
                {
                    "name" : "cities",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "countries",
                    "actions" : {
                        "GET" : true
                    }
                }
            ]
        }
    },
    {
        "_id" : ObjectId("574f6fdd1d69b028bc003454"),
        "name" : "Consultor API",
        "updated_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "created_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "application" : {
            "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65950")),
            "name" : "API",
            "modules" : [
                {
                    "name" : "users",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "roles",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "companies",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "applications",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "devicedefinitions",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "deviceinstances",
                    "actions" : {
                        "GET" : true,
                    }
                },
                {
                    "name" : "resourcetemplates",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "resourcedefinitions",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "resourceinstances",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name":"actualresourceinstance",
                    "actions" : {
                        "campaigns":{
                            "GET" : true,
                        },
                        "GET" : true,
                    }
                },
                {
                    "name" : "tracking",
                    "actions" : {
                        "actual" : {
                            "GET" : true
                        },
                        "events" : {
                            "GET" : true
                        },
                        "history" : {
                            "GET" : true
                        }
                    }
                },
                {
                    "name" : "tasks",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "resourcegroups",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "geofences",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "checkpoints",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "speedlimits",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "messages",
                    "actions" : {
                        "GET" : true
                    }
                },
                {
                    "name" : "addresses",
                    "actions" : {
                        "validate" : {
                            "GET" : true
                        }
                    }
                },
                {
                    "name" : "campaigns",
                    "actions" : {
                        "GET" : true,
                        "campaigns" : {
                            "GET" : true,
                            "forms":{
                                "GET" : true
                            }
                        }
                    }
                },
                {
                    "name" : "forms",
                    "actions" : {
                        "forms" : {
                            "sections":{
                                "GET" : true
                            },
                            "registers":{
                                "GET" : true
                            }
                        }
                    }
                },
                {
                    "name" : "export",
                    "actions" : {
                        "registers" : {
                            "GET" : true
                        }
                    }
                }
            ]
        }
    },
    {
        "_id" : ObjectId("575029591d69b028bc003471"),
        "name" : "Administrator WEB",
        "updated_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "created_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "application" : {
            "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65951")),
            "name" : "WEB",
            "modules" : [
                {
                    "name" : "users",
                    "actions" : {
                        "CREATE" : true,
                        "READ" : true,
                        "UPDATE" : true,
                        "DELETE" : true
                    }
                },
                {
                    "name" : "companies",
                    "actions" : {
                        "CREATE" : true,
                        "READ" : true,
                        "UPDATE" : true,
                        "DELETE" : true
                    }
                },
                {
                    "name" : "applications",
                    "actions" : {
                        "READ" : true,
                    }
                },
                {
                    "name" : "devicesdefinitions",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "devicesinstances",
                    "actions" : {
                        "CREATE" : true,
                        "READ" : true,
                        "UPDATE" : true,
                        "DELETE" : true
                    }
                },
                {
                    "name" : "resourcesdefinitions",
                    "actions" : {
                        "CREATE" : true,
                        "READ" : true,
                        "UPDATE" : true,
                        "DELETE" : true
                    }
                },
                {
                    "name" : "resourcesinstances",
                    "actions" : {
                        "CREATE" : true,
                        "READ" : true,
                        "UPDATE" : true,
                        "DELETE" : true
                    }
                },
                {
                    "name" : "tracking",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "tasks",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "resourcegroups",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "geofences",
                    "actions" : {
                        "CREATE" : true,
                        "READ" : true,
                        "UPDATE" : true,
                        "DELETE" : true
                    }
                },
                {
                    "name" : "checkpoints",
                    "actions" : {
                        "CREATE" : true,
                        "READ" : true,
                        "UPDATE" : true,
                        "DELETE" : true
                    }
                },
                {
                    "name" : "speedlimits",
                    "actions" : {
                        "CREATE" : true,
                        "READ" : true,
                        "UPDATE" : true,
                        "DELETE" : true
                    }
                },
                {
                    "name" : "messages",
                    "actions" : {
                        "CREATE" : true,
                        "READ" : true,
                        "UPDATE" : true,
                        "DELETE" : true
                    }
                },
                {
                    "name" : "export",
                    "actions" : {
                        "registers" : {
                            "GET" : true
                        }
                    }
                },
                {
                    "name" : "login",
                    "actions" : {
                            "POST" : true
                    }
                }
            ]
        }
    },
    {
        "_id" : ObjectId("575032f61d69b028bc00348b"),
        "name" : "Consultor WEB",
        "updated_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "created_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "application" : {
            "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65951")),
            "name" : "WEB",
            "modules" : [
                {
                    "name" : "users",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "companies",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "applications",
                    "actions" : {
                        "READ" : true,
                    }
                },
                {
                    "name" : "devicesdefinitions",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "devicesinstances",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "resourcesdefinitions",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "resourcesinstances",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "tracking",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "tasks",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "resourcegroups",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "geofences",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "checkpoints",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "speedlimits",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "messages",
                    "actions" : {
                        "READ" : true
                    }
                },
                {
                    "name" : "export",
                    "actions" : {
                        "registers" : {
                            "GET" : true
                        }
                    }
                },
                {
                    "name" : "login",
                    "actions" : {
                            "POST" : true
                    }
                }
            ]
        }
    },
    {
        "_id" : ObjectId("574f7528214c9d7b9fb65952"),
        "name" : "Administrador com.datatraffic.formulariodinamico.app",
        "updated_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "created_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "application" : {
            "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65952")),
            "name" : "com.datatraffic.formulariodinamico.app",
            "modules" : [
                {
                    "name" : "login",
                    "actions" : {
                            "POST" : true
                    }
                }
            ]
        }
    },
    {
        "_id" : ObjectId("574f7528214c9d7b9fb65953"),
        "name" : "Tableta API",
        "updated_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "created_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "application" : {
            "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65950")),
            "name" : "API",
            "modules" : [
                {
                    "name":"actualresource",
                    "actions" : {
                        "campaigns":{
                            "GET" : true,
                        }
                    }
                },
                {
                    "name" : "tracking",
                    "actions" : {
                        "actual" : {
                            "GET" : true
                        },
                        "events" : {
                            "GET" : true,
							"POST":true
                        },
                        "history" : {
                            "GET" : true
                        },
                        "positions" : {
                            "POST" : true
                        }
                    }
                },
                {
                    "name" : "campaigns",
                    "actions" : {
                        "GET" : true,
                        "campaigns" : {
                            "GET" : true,
                            "forms":{
                                "GET" : true
                            }
                        }
                    }
                },
                {
                    "name" : "forms",
                    "actions" : {
                        "forms" : {
                            "sections":{
                                "GET" : true
                            },
                            "registers":{
                                "GET" : true
                            }
                        }
                    }
                },
                {
                    "name" : "batch",
                    "actions" : {
                        "POST" : true
                    }
                }
            ]
        }
    },
    {
        "_id" : ObjectId("572bf279c740cde2218b45d1"),
        "name" : "Administrator Datatraffic",
        "updated_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "created_at" : ISODate("2016-05-27T20:49:26.431Z"),
        "application" : {
            "id_application" : DBRef("applications", ObjectId("574f7528214c9d7b9fb65950")),
            "name" : "API",
            "modules":[
				{
					"name":"manageallcompanies",
					"actions" : {
						"GET" : true,
						"POST" : true,
						"PUT" : true,
						"DELETE" : true,	
					}
				}
			]
		}
    },	
]
);

print("Cargadas todas los roles");

//
print("Insertando los devices_definitions");
db.deviceDefinitions.insert(
[
{
    "_id" : ObjectId("57431ca8a2f3d755076742ef"),
    "name" : "GPS",
	"javaClass":"GPS",
    "parents":
    [
    ],
    "children":
    [
        DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f0")),
        DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f1")),
        DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f2")),
        DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f4"))
    ],
    "customAttributes":
    [
        {
            "xtype": "textfield",
            "fieldLabel": "Fabricante"
        },
        {
            "xtype": "datefield",
            "fieldLabel": "Fecha compra"
        },
        {
            "xtype": "datefield",
            "fieldLabel": "Fecha vencimiento garantia"
        }
    ]
},
    {
        "_id" : ObjectId("57431ca8a2f3d755076742f0"),
        "name" : "SIM",
		"javaClass":"SIM",
        "parents":
            [
                DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742ef"))
            ],
        "children":
            [
                DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f1")),
                DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f2")),
                DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f4"))
            ],
        "customAttributes":
            [
                {
                    "xtype": "textfield", 
                    "fieldLabel": "Operador"
                },
                {
                    "xtype": "numberfield",   
                    "fieldLabel": "Numero celular"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha compra"
                },
                {
                    "xtype": "textfield",
                    "fieldLabel": "Plan"
                }
            ]
	},
    {
        "_id" : ObjectId("57431ca8a2f3d755076742f1"),
        "name" : "DATAMIX",
		"javaClass":"Datamix",
        "parents":
            [
                DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef"))
            ],
        "children":
            [
                DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f2")),
                DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4"))
            ],
        "customAttributes":
            [
                {
                    "xtype": "textfield",
                    "fieldLabel": "Version"
                }
            ]
	},
    {
        "_id" : ObjectId("57431ca8a2f3d755076742f2"),
        "name" : "Sonda de combustible",
		"javaClass":"Probe",
        "parents":
            [
                DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1"))
            ],
        "children":
            [
            ],
        "customAttributes":
            [
                {
                    "xtype": "textfield",
                    "fieldLabel": "Fabricante"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha compra"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha vencimiento garantia"
                }
            ]
	},
    {
        "_id" : ObjectId("57431ca8a2f3d755076742f3"),
        "name" : "Tablet",
		"javaClass":"Tablet",
        "parents":
            [
            ],
        "children":
            [
            ],
        "customAttributes":
            [
                {
                    "xtype": "textfield",
                    "fieldLabel": "Fabricante"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha compra"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha vencimiento garantia"
                },

                {
                    "xtype": "textfield",
                    "fieldLabel": "Conectividad"
                }
            ]
    },
    {
        "_id" : ObjectId("57431ca8a2f3d755076742f4"),
        "name" : "Biometrico",
		"javaClass":"Biometric",
        "parents":
            [
                DBRef("devicesDefinitions",ObjectId("57431ca8a2f3d755076742f1"))
            ],
        "children":
            [
            ],
        "customAttributes":
            [
                {
                    "xtype": "textfield",
                    "fieldLabel": "Fabricante"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha compra"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha vencimiento garantia"
                }
            ]
    },
    {
        "_id" : ObjectId("57431ca8a2f3d755076742f5"),
        "name" : "Trailer",
		"javaClass":"Trailer",
        "parents":
            [
                DBRef("devicesDefinitions",ObjectId("57431ca8a2f3d755076742ef"))
            ],
        "children":
            [
            ],
        "customAttributes":
            [
                {
                    "xtype": "textfield",
                    "fieldLabel": "Fabricante"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha compra"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha vencimiento garantia"
                }
            ]
    },
    {
        "_id" : ObjectId("57431ca8a2f3d755076742f6"),
        "name" : "Sensor de pasajero",
		"javaClass":"PassangerSensor",
        "parents":
            [
                DBRef("devicesDefinitions",ObjectId("57431ca8a2f3d755076742ef"))
            ],
        "children":
            [
            ],
        "customAttributes":
            [
                {
                    "xtype": "textfield",
                    "fieldLabel": "Fabricante"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha compra"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha vencimiento garantia"
                }
            ]
    },
    {
        "_id" : ObjectId("57431ca8a2f3d755076742f7"),
        "name" : "Ecumonitor",
		"javaClass":"Ecumonitor",
        "parents":
            [
                DBRef("devicesDefinitions",ObjectId("57431ca8a2f3d755076742f1"))
            ],
        "children":
            [
            ],
        "customAttributes":
            [
                {
                    "xtype": "textfield",
                    "fieldLabel": "Fabricante"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha compra"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha vencimiento garantia"
                }
            ]
    },
    {
        "_id" : ObjectId("57431ca8a2f3d755076742f8"),
        "name" : "Boton de panico",
		"javaClass":"PanicButton",
        "parents":
            [
                DBRef("devicesDefinitions",ObjectId("57431ca8a2f3d755076742ef"))
            ],
        "children":
            [
            ],
        "customAttributes":
            [
                {
                    "xtype": "textfield",
                    "fieldLabel": "Fabricante"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha compra"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha vencimiento garantia"
                }
            ]
    }
]);

print("Cargadas todos los devices_definitions");

//
print("Insertando los devicesInstances");
db.deviceInstances.insert(
[

{"_id": ObjectId("570f9f46da1c882fea57db80"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026105835","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"_id": ObjectId("570f9f46da1c882fea57db81"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026212185","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"_id": ObjectId("570f9f46da1c882fea57db82"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026212698","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"_id": ObjectId("570f9f46da1c882fea57db83"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026204364","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"_id": ObjectId("570f9f46da1c882fea57db84"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026362402","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026210858","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026315277","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026105777","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612023685334","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026291668","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026206054","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026360661","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026223208","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612024582902","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026294258","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666051533120","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612024624183","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026228223","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026211872","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},

{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612024212831","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612026340606","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666051537014","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666051312293","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666051458906","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666050658308","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666051451521","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666050661062","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666051214978","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666050706982","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666051305578","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666050659868","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666051258215","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666050642252","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666050739850","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666050714390","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666051541610","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "357666051450226","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612023602206","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742ef")),"serial": "356612023672696","customAttributes" :{"fabricante" :"DCT","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},

{"_id": ObjectId("570f9f46da1c882fea57db90"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "RPUPE2QST8","customAttributes" :{"operador" :"Avantel","numeroCelular":"5906846681","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"_id": ObjectId("570f9f46da1c882fea57db91"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "XFRTDL2O67","customAttributes" :{"operador" :"Avantel","numeroCelular":"1397075232","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"_id": ObjectId("570f9f46da1c882fea57db92"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "7NFSYSZ3BK","customAttributes" :{"operador" :"Avantel","numeroCelular":"2338365949","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"_id": ObjectId("570f9f46da1c882fea57db93"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "6EV7HG5IGS","customAttributes" :{"operador" :"Avantel","numeroCelular":"53831377","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"_id": ObjectId("570f9f46da1c882fea57db94"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "E19DV305DE","customAttributes" :{"operador" :"Avantel","numeroCelular":"1040131159","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "HXRCXE7WG7","customAttributes" :{"operador" :"Avantel","numeroCelular":"7814241895","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "J65O0KM3IG","customAttributes" :{"operador" :"Avantel","numeroCelular":"5888514339","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "75E870TX2N","customAttributes" :{"operador" :"Avantel","numeroCelular":"926467673","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "WB0SC8EDZD","customAttributes" :{"operador" :"Avantel","numeroCelular":"7934201821","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "MQJMSNEBR2","customAttributes" :{"operador" :"Avantel","numeroCelular":"7934942202","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "9PTQDBNY5K","customAttributes" :{"operador" :"Avantel","numeroCelular":"5477065627","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "EFY1ZRB6EJ","customAttributes" :{"operador" :"Avantel","numeroCelular":"2872565920","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "FSS9ULR2WM","customAttributes" :{"operador" :"Avantel","numeroCelular":"3382968049","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "54LFXWF0D0","customAttributes" :{"operador" :"Avantel","numeroCelular":"9444779092","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "9BP9CWU04F","customAttributes" :{"operador" :"Avantel","numeroCelular":"1141673270","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "ZDQ5E99YUH","customAttributes" :{"operador" :"Avantel","numeroCelular":"586702548","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "3JHMTRYTW2","customAttributes" :{"operador" :"Avantel","numeroCelular":"9396194630","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "SFQBQ909AL","customAttributes" :{"operador" :"Avantel","numeroCelular":"70760324","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "8KZ1XA996E","customAttributes" :{"operador" :"Avantel","numeroCelular":"6370440517","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "1J5BPB31JO","customAttributes" :{"operador" :"Avantel","numeroCelular":"8007637852","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},

{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "KR2HUE7YKH","customAttributes" :{"operador" :"Avantel","numeroCelular":"4345863912","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "GHF1CG1BII","customAttributes" :{"operador" :"Avantel","numeroCelular":"9629202432","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "EUEKTXKSBK","customAttributes" :{"operador" :"Avantel","numeroCelular":"9463309964","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "RHA27HC6I0","customAttributes" :{"operador" :"Avantel","numeroCelular":"7868469303","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "PJD8QH01PF","customAttributes" :{"operador" :"Avantel","numeroCelular":"4784658289","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "2EYMUQDO2L","customAttributes" :{"operador" :"Avantel","numeroCelular":"6616144845","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "9CXOK27ERY","customAttributes" :{"operador" :"Avantel","numeroCelular":"8464340236","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "BL1GZ6NIES","customAttributes" :{"operador" :"Avantel","numeroCelular":"9432240271","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "IAEGI0P7UM","customAttributes" :{"operador" :"Avantel","numeroCelular":"2104402678","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "UTILKUZ7YB","customAttributes" :{"operador" :"Avantel","numeroCelular":"3264642851","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "BZDDWYJIME","customAttributes" :{"operador" :"Avantel","numeroCelular":"8039616426","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "1FBJX5RBTV","customAttributes" :{"operador" :"Avantel","numeroCelular":"9629006372","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "55K8AYPXF3","customAttributes" :{"operador" :"Avantel","numeroCelular":"4383246124","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "XMQS43RF4B","customAttributes" :{"operador" :"Avantel","numeroCelular":"1398459247","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "OFDB4AQJPK","customAttributes" :{"operador" :"Avantel","numeroCelular":"6202010226","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "NCE078IFQZ","customAttributes" :{"operador" :"Avantel","numeroCelular":"1065825665","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "KJTWUKTAHP","customAttributes" :{"operador" :"Avantel","numeroCelular":"3550728457","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "MHTYT1N1MC","customAttributes" :{"operador" :"Avantel","numeroCelular":"8415020005","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "ZUE0FLI2VA","customAttributes" :{"operador" :"Avantel","numeroCelular":"6209863666","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f0")),"serial": "7DCVND556I","customAttributes" :{"operador" :"Avantel","numeroCelular":"4163630902","fechaCompra":"2016-01-01","plan":"Empresarial 1GB"}},

{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "RPUPE2QST8","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "XFRTDL2O67","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "7NFSYSZ3BK","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "6EV7HG5IGS","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "E19DV305DE","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "HXRCXE7WG7","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "J65O0KM3IG","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "75E870TX2N","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "WB0SC8EDZD","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "MQJMSNEBR2","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "9PTQDBNY5K","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "EFY1ZRB6EJ","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "FSS9ULR2WM","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "54LFXWF0D0","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "9BP9CWU04F","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "ZDQ5E99YUH","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "3JHMTRYTW2","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "SFQBQ909AL","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "8KZ1XA996E","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "1J5BPB31JO","customAttributes" :{"version" :"2"}},

{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "KR2HUE7YKH","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "GHF1CG1BII","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "EUEKTXKSBK","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "RHA27HC6I0","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "PJD8QH01PF","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "2EYMUQDO2L","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "9CXOK27ERY","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "BL1GZ6NIES","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "IAEGI0P7UM","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "UTILKUZ7YB","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "BZDDWYJIME","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "1FBJX5RBTV","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "55K8AYPXF3","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "XMQS43RF4B","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "OFDB4AQJPK","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "NCE078IFQZ","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "KJTWUKTAHP","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "MHTYT1N1MC","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "ZUE0FLI2VA","customAttributes" :{"version" :"2"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f1")),"serial": "7DCVND556I","customAttributes" :{"version" :"2"}},

{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "H6MAOF0H2W","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "9SA4LM2XI5","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "ID6XV8F6UQ","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "1YWSKQTB5O","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "VT0YS7QN6A","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "UEP907E24K","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "2DEJ8KATQ5","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "TYZ10ZB7ST","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "CU810WITL8","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "U966SH3515","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "8JUXWXOEOI","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "347FTV0DRD","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "A4EEDR96XV","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "K1VMP6G9Q3","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "TXOD9KYSQ0","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "RYS9T1GUPA","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "2T7HFLBXS5","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "A5L8ZEPTUD","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "EFYE35MKWV","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "R51I8Z0SLZ","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
                                                                                                                                                                                                                                                                  
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "JDX16SXU9T","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "CHGAWTTXNK","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "MZP3VTVH41","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "G26P5UIZCH","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "0PMTIEQR6K","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "JXO51MF8ZH","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "KKUR5NZSM1","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "C5FNP16GOS","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "YRGQCSDMU4","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "Q6S0IUQ8UI","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "76TZ7BY9AP","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "IVUUE1WKT2","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "ZWXAUIJ8H7","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "3PONGNT2MQ","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "D90HHVM726","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "ERO02924MF","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "KNOY5LB561","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "BDLFXJQ42M","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "NQNC1V0CFY","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f2")),"serial": "4E8TQE9ZDQ","customAttributes" :{"fabricante" :"Vepamon","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},

{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "OLW9962YEZ","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "8SWBBWQRKY","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "0ULBA671FQ","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "DAPK3ABD6Y","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "UYNI9Q9FPB","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "ZMTFMH8HQ6","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "ZLTINNSI8B","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "LTM2CBWTNS","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "Z1DCJ8DRXC","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "SN8FS661SJ","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "DVFY232RLA","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "OG4MIUWVN8","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "87G87LXE85","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "52TJIT3407","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "W7YI7BQTR3","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "P4636RGOLV","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "CS9PZI22S8","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "9JY424MKE9","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "PDUNDZQEJ0","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "MN89FZV0L7","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},

{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "HCAUXFHJ4Z","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "0BNH0MRL2U","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "3GTF8V21LW","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "LDNQQ4LX0T","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "M8AQGTQF6W","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "BSFW8CTMKO","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "H3TNGN1WID","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "6UN4TEAYBK","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "SY7L57P4E1","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "7LF51KTYHL","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "KO6NBUTLV5","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "VI3025QW3I","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "ZTGK378S18","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "RJHC0I3DBM","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "41AHQIWBCX","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "JH47H2PTH6","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "05BPRIEHJY","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "QQOME9CC44","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "NINFJQAJHS","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f4")),"serial": "Z1ANFN1DIH","customAttributes" :{"fabricante" :"SecuGen","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31"}},

{"_id": ObjectId("570f9f46da1c882fea57db60"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "RPUPE2QST8","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"_id": ObjectId("570f9f46da1c882fea57db61"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "XFRTDL2O67","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"_id": ObjectId("570f9f46da1c882fea57db62"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "7NFSYSZ3BK","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"_id": ObjectId("570f9f46da1c882fea57db63"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "6EV7HG5IGS","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"_id": ObjectId("570f9f46da1c882fea57db64"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "E19DV305DE","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "HXRCXE7WG7","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "J65O0KM3IG","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "75E870TX2N","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "WB0SC8EDZD","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "MQJMSNEBR2","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "9PTQDBNY5K","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "EFY1ZRB6EJ","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "FSS9ULR2WM","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "54LFXWF0D0","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "9BP9CWU04F","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "ZDQ5E99YUH","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "3JHMTRYTW2","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "SFQBQ909AL","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "8KZ1XA996E","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "1J5BPB31JO","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},

{"_id": ObjectId("570f9f46da1c882fea57db70"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "KR2HUE7YKH","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"_id": ObjectId("570f9f46da1c882fea57db71"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "GHF1CG1BII","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"_id": ObjectId("570f9f46da1c882fea57db72"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "EUEKTXKSBK","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"_id": ObjectId("570f9f46da1c882fea57db73"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "RHA27HC6I0","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"_id": ObjectId("570f9f46da1c882fea57db74"),"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "PJD8QH01PF","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "2EYMUQDO2L","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "9CXOK27ERY","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "BL1GZ6NIES","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "IAEGI0P7UM","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "UTILKUZ7YB","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "BZDDWYJIME","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "1FBJX5RBTV","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "55K8AYPXF3","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "XMQS43RF4B","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "OFDB4AQJPK","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "NCE078IFQZ","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "KJTWUKTAHP","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "MHTYT1N1MC","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "ZUE0FLI2VA","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_deviceDefinition":DBRef("deviceDefinitions",ObjectId("57431ca8a2f3d755076742f3")),"serial": "7DCVND556I","customAttributes" :{"fabricante" :"Lenovo","fechaCompra":"2016-01-01","fechaVencimientoGarantia":"2016-12-31","conectividad":"wifi"}}

]
);

print("Cargadas todos los devicesInstances");

//
print("Insertando los resources_templates");
db.resourceTemplates.insert(
[
{
	"_id" : ObjectId("56f55709efe9e8d574868a21"),
	"name" : "Vehiculo",
	"customAttributes" :
	[
		{
			"xtype": "textfield",
			"fieldLabel": "Placa"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Marca"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Modelo"
		}		
	],
	"deviceDefinitions": 
	[
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742ef")),
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f0")),
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f1")),
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f2"))
	]
},
{
	"_id" : ObjectId("56f55709efe9e8d574868a22"),
	"name" : "Persona",
	"customAttributes" :
	[
		{
			"xtype": "textfield",
			"fieldLabel": "Nombres"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Apellidos"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Documento"
		}
	],
	"deviceDefinitions": 
	[
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f3"))
	]
}
]
);
print("Cargadas todos los resources_templates");

//
print("Insertando los resources_definitions");
db.resourceDefinitions.insert(
[
{
    "_id": ObjectId("570f9f46da1c882fea57db56"),
    "id_company": DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
    "name": "Conductor",
    "customAttributes": 
    [
		{
			"xtype": "textfield",
			"fieldLabel": "Nombres"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Apellidos"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Documento"
		}
    ],
    "deviceDefinitions": 
    [
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f3"))
    ],
    "resourceDefinitions": [],
    "isSystem":true
},
{
    "_id": ObjectId("570f9f46da1c882fea57db57"),
    "id_company": DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
    "name": "Camion",
    "customAttributes": 
    [
		{
			"xtype": "textfield",
			"fieldLabel": "Placa"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Marca"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Modelo"
		}
    ],
    "deviceDefinitions": 
    [
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742ef")),
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f0")),
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f2")),
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f5")),
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f6")),
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f7")),
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f8"))
    ],
    "resourceDefinitions": 
    [
        {
            "id_resourceDefinition": DBRef("resourceDefinitions", ObjectId("570f9f46da1c882fea57db56")),
            "customAttributes": 
            [
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha inicio"
                },
                {
                    "xtype": "datefield",
                    "fieldLabel": "Fecha fin"
                }				
            ]
        }
    ],
    "isSystem":true
},
{
    "_id": ObjectId("570f9f46da1c882fea57db58"),
    "id_company": DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
    "name": "Mula",
    "customAttributes": 
    [
		{
			"xtype": "textfield",
			"fieldLabel": "Placa"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Marca"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Modelo"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Numero de ejes"
		}
    ],
    "deviceDefinitions": 
    [
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742ef")),
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f0")),
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f1")),
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f2"))
    ],
    "resourceDefinitions": [],
    "isSystem":true
},
{
    "_id": ObjectId("570f9f46da1c882fea57db59"),
    "id_company": DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),
    "name": "Vendedor",
    "customAttributes": 
    [
		{
			"xtype": "textfield",
			"fieldLabel": "Nombres"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Apellidos"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Documento"
		}
    ],
    "deviceDefinitions": 
    [
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f3"))
    ],
    "resourceDefinitions": [],
    "isSystem":true
},
{
    "_id": ObjectId("570f9f46da1c882fea57db60"),
    "id_company": DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),
    "name": "Tecnico",
    "customAttributes":
    [
		{
			"xtype": "textfield",
			"fieldLabel": "Nombres"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Apellidos"
		},
		{
			"xtype": "textfield",
			"fieldLabel": "Documento"
		}
    ],
    "deviceDefinitions":
    [
		DBRef("deviceDefinitions", ObjectId("57431ca8a2f3d755076742f3"))
    ],
    "resourceDefinitions": [],
    "isSystem":true
},
{
    "_id": ObjectId("570f9f46da1c882fea57db61"),
    "id_company": DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
    "name": "Usuarios",
    "customAttributes":
        [
            {
                "xtype": "textfield",
                "fieldLabel": "name"
            },
            {
                "xtype": "textfield",
                "fieldLabel": "lastName"
            }
        ],
    "deviceDefinitions":[],
    "resourceDefinitions": [],
    "isSystem":true
},
{
    "_id": ObjectId("570f9f46da1c882fea57db62"),
    "id_company": DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),
    "name": "Usuarios",
    "customAttributes":
        [
            {
                "xtype": "textfield",
                "fieldLabel": "name"
            },
            {
                "xtype": "textfield",
                "fieldLabel": "lastName"
            }
        ],
    "deviceDefinitions":[],
    "resourceDefinitions": [],
    "isSystem":true
}
]
);
print("Cargadas todos los resources_definitions");

print("Insertando los grupos");

db.resourceGroups.insert(
[
	{
        "_id": ObjectId("571009a21dd20ebf5a071bf0"),
        "id_company":DBRef("companies",ObjectId("56f55709efe9e8d575768a54")),
		"id_resourceDefinition" : DBRef("resourceDefinitions", ObjectId("570f9f46da1c882fea57db56")),
        "name": "Grupo conductores",
        "description" : "Grupo conductores"		
    },
    {
		"_id": ObjectId("571009a21dd20ebf5a071bf1"),
        "id_company":DBRef("companies",ObjectId("56f55709efe9e8d575768a54")),
		"id_resourceDefinition" : DBRef("resourceDefinitions", ObjectId("570f9f46da1c882fea57db57")),
        "name": "Grupo camiones",
        "description" : "Grupo camiones A"	
    },
    {
		"_id": ObjectId("571009a21dd20ebf5a071bf2"),
        "id_company":DBRef("companies",ObjectId("56f55709efe9e8d575768a54")),
		"id_resourceDefinition" : DBRef("resourceDefinitions", ObjectId("570f9f46da1c882fea57db58")),
        "name": "Grupo mulas",
        "description" : "Grupo mulas"	
    },
    {
		"_id": ObjectId("571009a21dd20ebf5a071bf3"),
        "id_company":DBRef("companies",ObjectId("56f55709efe9e8d575768a55")),
		"id_resourceDefinition" : DBRef("resourceDefinition", ObjectId("570f9f46da1c882fea57db59")),
        "name": "Grupo vendedores",
        "description" : "Grupo vendedores"	
    },
    {
		"_id": ObjectId("571009a21dd20ebf5a071bf4"),
        "id_company":DBRef("companies",ObjectId("56f55709efe9e8d575768a55")),
		"id_resourceDefinition" : DBRef("resourceDefinition", ObjectId("570f9f46da1c882fea57db60")),
        "name": "Grupo tecnicos",
        "description" : "Grupo tecnicos"	
    },
    {
		"_id": ObjectId("571009a21dd20ebf5a071bf5"),
        "id_company":DBRef("companies",ObjectId("56f55709efe9e8d575768a54")),
		"id_resourceDefinition" : DBRef("resourceDefinitions", ObjectId("570f9f46da1c882fea57db57")),
        "name": "Grupo camiones",
        "description" : "Grupo camiones B"	
    }
]
);

print("Cargadas todos los grupos");

db.resourceInstances.insert(
[
{
    "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
    "id_resourceDefinition" : DBRef("resourceDefinitions", ObjectId("570f9f46da1c882fea57db61")),
    "resourceGroups" : [],
    "login" : "pedrocaicedo",
    "email" : "pedrocaicedo@datatraffic.com.co",
    "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK",
	"customStatus":"SIN CARGA",
    "deviceInstances" : [],
    "resourceInstances" : [ ],
    "customAttributes" : {
        "name" : "Pedro",
        "lastName" : "Caicedo"
    },
    "roles" : [
        {
            "applicationName" : "API",
            "roleName" : "Administrator API",
            "id_role" : DBRef("roles", ObjectId("5748b2d61d69b01b2800702f"))
        },
        {
            "applicationName" : "WEB",
            "roleName" : "Administrator WEB",
            "id_role" : DBRef("roles", ObjectId("575029591d69b028bc003471"))
        },
		{
            "applicationName" : "API",
            "roleName" : "Administrator Datatraffic",
            "id_role" : DBRef("roles", ObjectId("572bf279c740cde2218b45d1"))
		}
    ]
},
{
    "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),
    "id_resourceDefinition" : DBRef("resourceDefinitions", ObjectId("570f9f46da1c882fea57db62")),
    "resourceGroups" : [],
    "login" : "sergiosinuco",
    "email" : "sergiosinuco@datatraffic.com.co", 
	"password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK",
	"customStatus":"SIN CARGA",
    "deviceInstances" : [],
    "resourceInstances" : [ ],
    "customAttributes" : {
        "name" : "Sergio",
        "LastName" : "Sinuco"
    },
    "roles" : [
        {
            "applicationName" : "API",
            "roleName" : "Administrator API",
            "id_role" : DBRef("roles", ObjectId("5748b2d61d69b01b2800702f"))
        },
        {
            "applicationName" : "WEB",
            "roleName" : "Administrator WEB",
            "id_role" : DBRef("roles", ObjectId("575029591d69b028bc003471"))
        }
    ]
},

{"_id":ObjectId("570f9f46da1c882fea57db50"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db56")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf0"))],"login": "17340898","email" : "soporte@datatraffic.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db60"))],"resourceInstances":[],"customAttributes" :{"nombres" :"RICARDO","apellidos":"AVILA OVALLE"}},
{"_id":ObjectId("570f9f46da1c882fea57db51"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db56")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf0"))],"login": "13176749","email" : "soporte@datatraffic.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db61"))],"resourceInstances":[],"customAttributes" :{"nombres" :"CARLOS ALBERTO","apellidos":"RINCON AMAYA"}},
{"_id":ObjectId("570f9f46da1c882fea57db52"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db56")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf0"))],"login": "17309592","email" : "soporte@datatraffic.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db62"))],"resourceInstances":[],"customAttributes" :{"nombres" :"JOSE EFRAIN","apellidos":"NOVOA SANCHEZ"}},
{"_id":ObjectId("570f9f46da1c882fea57db53"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db56")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf0"))],"login": "91249638","email" : "soporte@datatraffic.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db63"))],"resourceInstances":[],"customAttributes" :{"nombres" :"PEDRO JAVIER","apellidos":"FLOREZ DURAN"}},
{"_id":ObjectId("570f9f46da1c882fea57db54"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db56")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf0"))],"login": "91539473","email" : "soporte@datatraffic.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db64"))],"resourceInstances":[],"customAttributes" :{"nombres" :"LAITON LUNA","apellidos":"JEISON ANDRES"}},

{"_id":ObjectId("56f55709efe9e8d575768a60"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf1")),DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf5"))],"login": "SMG845","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db80")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db90"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db50")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
{"_id":ObjectId("56f55709efe9e8d575768a61"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf1")),DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf5"))],"login": "SMG872","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db81")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db91"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db51")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
{"_id":ObjectId("56f55709efe9e8d575768a62"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf1"))],"login": "SMG846","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db82")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db92"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db52")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
{"_id":ObjectId("56f55709efe9e8d575768a63"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf1"))],"login": "UYX480","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db83")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db93"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db53")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
{"_id":ObjectId("56f55709efe9e8d575768a64"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf5"))],"login": "SMG847","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db84")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db94"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db54")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},

{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db60")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf4"))],"login": "91348653","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db70"))],"resourceInstances":[],"customAttributes" :{"nombres" :"EDWING YOVANNY","apellidos":"PEÑARANDA MANTILLA"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db60")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf4"))],"login": "79826761","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db71"))],"resourceInstances":[],"customAttributes" :{"nombres" :"Cesar Leonel","apellidos":"Rodriguez"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db60")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf4"))],"login": "19194484","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db72"))],"resourceInstances":[],"customAttributes" :{"nombres" :"Cesar","apellidos":"Rodriguez"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db60")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf4"))],"login": "1102716746","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db73"))],"resourceInstances":[],"customAttributes" :{"nombres" :"Oscar Julian","apellidos":"Centeno Gomez"}},
{"id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a55")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db60")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf4"))],"login": "12345678","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db74"))],"resourceInstances":[],"customAttributes" :{"nombres" :"JORGE ENRIQUE","apellidos":"CASTRO"}}

]
);
print("Cargadas todos los resources_instances");

print("Insertando todos los Actual");
db.actual.insert({
    "_class": "co.com.datatraffic.fieldvision.tracking.Actual",
    "resource": 
	{"_id":ObjectId("56f55709efe9e8d575768a60"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf1")),DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf5"))],"login": "SMG845","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db80")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db90"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db50")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
    "actualGeofences": [
      {
        "_id": ObjectId("571009a21dd20ebf5a071bed"),
        "name": "Sector I",
        "description": "Barrios: la granja, soledad norte, la almeria, tabora, santa maria del lago",
        "isActive": true,
        "isPublic": true,
        "geometry": {
          "type": "Polygon",
          "coordinates": [
            [
              [
                -74.100916,
                4.703048
              ],
              [
                -74.089029,
                4.695392
              ],
              [
                -74.094951,
                4.68718
              ],
              [
                -74.10568,
                4.69582
              ],
              [
                -74.100916,
                4.703048
              ]
            ]
          ]
        }
      }
    ],
    "actualCheckPoints": [
      {
        "_id": ObjectId("571009a21dd20ebf5a071bef"),
        "name": "Panaderia venecia",
        "description": "Panaderia de la castellana",
        "isActive": false,
        "isPublic": false,
        "geometry": {
          "type": "Point",
          "coordinates": [
            -74.114842,
            4.683897
          ]
        },
        "ratio": 0
      }
    ],
    "deviceData": {
        "GPS": {
            "imei": "00783180",
            "latitude": 4.690116,
            "longitude": -74.079609,
            "speed": 2,
            "address": "ENTURNE EST IMPALA BARRANCA (BARRANCABERMEJA)",
            "heading": 324,
            "updateTime": "2016-05-27 06:06:13",
            "ev": "00",
            "odometer": "33516506",
            "ignitionStatus": "OFF",
            "_class": "co.com.datatraffic.fieldvision.tracking.devices.GPS"
        },
        "Probe": {
            "instantConsumption": "0",
            "instantEstimatedConsumption": "0",
            "probeValue": "001b.0,0151.0",
            "levelGasTank": null,
            "status": 12,
            "totalConsumption": null,
            "totalEstimatedConsumption": "0",
            "created_at": "2016-05-27 06:06:13",
            "percentageLevelGasTank": 85.45,
            "fixedLevelGasTank": "38.9299999999999997",
            "_class": "co.com.datatraffic.fieldvision.tracking.devices.Probe"
        },
        "Trailer": {
            "_class": "co.com.datatraffic.fieldvision.tracking.devices.Trailer",
            "ev": false,
            "status": "NO"
        },
        "PassangerSensor": {
            "_class": "co.com.datatraffic.fieldvision.tracking.devices.PassangerSensor",
            "ev": false,
            "status": "NO"
        },
        "Ecumonitor": {
            "_class": "co.com.datatraffic.fieldvision.tracking.devices.Ecumonitor",
            "RPM": 989,
            "totlaFuelConsuption": 300.5,
            "totalTraveledDistance": 499999,
            "totalFuelComsumtionWhileIdle": 888,
            "tripOdometer": 990,
            "engineUsage": 900,
            "totalTimeWhileEngineIdle": 90.9,
            "instantFuelConsumption": 950,
            "dataTroubleCode": "SD",
            "onBoardFuelLevel": 89
        },
        "PanicButton": {
            "ev": "20",
            "status": "NO",
            "_class": "co.com.datatraffic.fieldvision.tracking.devices.PanicButton"
        }
    },
        "latitude" : 4.690116,
        "longitude" : -74.079609,
        "speed" : 2,
        "address" : "ENTURNE EST IMPALA BARRANCA (BARRANCABERMEJA)",
        "heading" : 324,
        "updateTime" : "2016-05-27 06:06:13",
        "hasEvent" : false,
        "distance" : "140",
        "odometer" : "33516506",
        "totalDistance" : "171",
        "created_at" : "2016-05-27 06:06:15",
        "id_rawData" : "106650000",
        "isVisible" : true,	
"tasks":[
    {
      "_id": ObjectId("572bf279c740cde2218b45d1"),
      "type": "Inicio",
      "code": "571d8a0fc740cd5136be5c53",
      "loadAmount": 0,
      "status": "PENDIENTE",
      "name": "Bodega 2",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "18:00",
      "duration": 0,
      "updated_at": "2016-05-06 01:25:13",
      "created_at": "2016-05-06 01:25:13",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "NBT868",
          "type": "carro",
          "capacidad": 300,
          "startHour": "06:00",
          "endHour": "19:00",
          "_id": {
            "$id": "571e243ac740cd5336be5c52"
          },
          "updated_at": {
            "sec": 1462497913,
            "usec": 218000
          },
          "created_at": {
            "sec": 1462497913,
            "usec": 218000
          }
        }
      ],
      "location_id": "571d8a0fc740cd5136be5c53",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "07:37"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4593"),
      "type": "pickup",
      "code": "572bf25cc740cdb5218b457d",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 23",
      "location": {
        "name": "Calle 69 68C, 111061 Bogot\u00e1, Colombia",
        "lat": 4.67704,
        "lng": -74.08914
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "NBT868",
          "type": "carro",
          "capacidad": 300,
          "startHour": "06:00",
          "endHour": "19:00",
          "_id": {
            "$id": "571e243ac740cd5336be5c52"
          },
          "updated_at": {
            "sec": 1462497913,
            "usec": 233000
          },
          "created_at": {
            "sec": 1462497913,
            "usec": 233000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457d",
      "location_name": "Calle 69 68C, 111061 Bogot\u00e1, Colombia",
      "arrival_time": "08:00",
      "finish_time": "09:00"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b45a7"),
      "type": "pickup",
      "code": "572bf25fc740cdb5218b4587",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 33",
      "location": {
        "name": "Avenida Calle 53 66A, 111321 Bogot\u00e1, Colombia",
        "lat": 4.65554,
        "lng": -74.0981
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "NBT868",
          "type": "carro",
          "capacidad": 300,
          "startHour": "06:00",
          "endHour": "19:00",
          "_id": {
            "$id": "571e243ac740cd5336be5c52"
          },
          "updated_at": {
            "sec": 1462497913,
            "usec": 247000
          },
          "created_at": {
            "sec": 1462497913,
            "usec": 247000
          }
        }
      ],
      "location_id": "572bf25fc740cdb5218b4587",
      "location_name": "Avenida Calle 53 66A, 111321 Bogot\u00e1, Colombia",
      "arrival_time": "09:14",
      "finish_time": "10:14"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b45a5"),
      "type": "pickup",
      "code": "572bf25fc740cdb5218b4586",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 32",
      "location": {
        "name": "Avenida Calle 53 66A, 111321 Bogot\u00e1, Colombia",
        "lat": 4.65554,
        "lng": -74.0981
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "NBT868",
          "type": "carro",
          "capacidad": 300,
          "startHour": "06:00",
          "endHour": "19:00",
          "_id": {
            "$id": "571e243ac740cd5336be5c52"
          },
          "updated_at": {
            "sec": 1462497913,
            "usec": 261000
          },
          "created_at": {
            "sec": 1462497913,
            "usec": 261000
          }
        }
      ],
      "location_id": "572bf25fc740cdb5218b4586",
      "location_name": "Avenida Calle 53 66A, 111321 Bogot\u00e1, Colombia",
      "arrival_time": "10:14",
      "finish_time": "11:14"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4591"),
      "type": "pickup",
      "code": "572bf25bc740cdb5218b457c",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 22",
      "location": {
        "name": "Calle 61 56, 111321 Bogot\u00e1, Colombia",
        "lat": 4.65372,
        "lng": -74.08626
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "NBT868",
          "type": "carro",
          "capacidad": 300,
          "startHour": "06:00",
          "endHour": "19:00",
          "_id": {
            "$id": "571e243ac740cd5336be5c52"
          },
          "updated_at": {
            "sec": 1462497913,
            "usec": 286000
          },
          "created_at": {
            "sec": 1462497913,
            "usec": 286000
          }
        }
      ],
      "location_id": "572bf25bc740cdb5218b457c",
      "location_name": "Calle 61 56, 111321 Bogot\u00e1, Colombia",
      "arrival_time": "11:25",
      "finish_time": "12:25"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4592"),
      "type": "dropoff",
      "code": "572bf25bc740cdb5218b457c",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 22",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "NBT868",
          "type": "carro",
          "capacidad": 300,
          "startHour": "06:00",
          "endHour": "19:00",
          "_id": {
            "$id": "571e243ac740cd5336be5c52"
          },
          "updated_at": {
            "sec": 1462497913,
            "usec": 300000
          },
          "created_at": {
            "sec": 1462497913,
            "usec": 300000
          }
        }
      ],
      "location_id": "572bf25bc740cdb5218b457c",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "12:53",
      "finish_time": "13:53"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4594"),
      "type": "dropoff",
      "code": "572bf25cc740cdb5218b457d",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 23",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "NBT868",
          "type": "carro",
          "capacidad": 300,
          "startHour": "06:00",
          "endHour": "19:00",
          "_id": {
            "$id": "571e243ac740cd5336be5c52"
          },
          "updated_at": {
            "sec": 1462497913,
            "usec": 315000
          },
          "created_at": {
            "sec": 1462497913,
            "usec": 315000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457d",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "13:53",
      "finish_time": "14:53"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b45a8"),
      "type": "dropoff",
      "code": "572bf25fc740cdb5218b4587",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 33",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "NBT868",
          "type": "carro",
          "capacidad": 300,
          "startHour": "06:00",
          "endHour": "19:00",
          "_id": {
            "$id": "571e243ac740cd5336be5c52"
          },
          "updated_at": {
            "sec": 1462497913,
            "usec": 329000
          },
          "created_at": {
            "sec": 1462497913,
            "usec": 329000
          }
        }
      ],
      "location_id": "572bf25fc740cdb5218b4587",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "14:53",
      "finish_time": "15:53"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b45a6"),
      "type": "dropoff",
      "code": "572bf25fc740cdb5218b4586",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 32",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "NBT868",
          "type": "carro",
          "capacidad": 300,
          "startHour": "06:00",
          "endHour": "19:00",
          "_id": {
            "$id": "571e243ac740cd5336be5c52"
          },
          "updated_at": {
            "sec": 1462497913,
            "usec": 344000
          },
          "created_at": {
            "sec": 1462497913,
            "usec": 344000
          }
        }
      ],
      "location_id": "572bf25fc740cdb5218b4586",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "15:53",
      "finish_time": "16:53"
    },
    {
      "_id": ObjectId("572bf279c740cde2218b45d2"),
      "type": "Fin",
      "code": "571d8a2ac740cdeb4ebe5c53",
      "loadAmount": 0,
      "status": "PENDIENTE",
      "name": "Bodega 1",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "18:00",
      "duration": 0,
      "updated_at": "2016-05-06 01:25:13",
      "created_at": "2016-05-06 01:25:13",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "NBT868",
          "type": "carro",
          "capacidad": 300,
          "startHour": "06:00",
          "endHour": "19:00",
          "_id": {
            "$id": "571e243ac740cd5336be5c52"
          },
          "updated_at": {
            "sec": 1462497913,
            "usec": 365000
          },
          "created_at": {
            "sec": 1462497913,
            "usec": 365000
          }
        }
      ],
      "location_id": "571d8a2ac740cdeb4ebe5c53",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "16:53"
    }
  ],
"javaScriptShape": "new Array(new OpenLayers.Geometry.Point(-74.0609117,4.68041),new OpenLayers.Geometry.Point(-74.0608656,4.68068),new OpenLayers.Geometry.Point(-74.0624642,4.6809483),new OpenLayers.Geometry.Point(-74.0635049,4.6811414),new OpenLayers.Geometry.Point(-74.0633333,4.6822357),new OpenLayers.Geometry.Point(-74.0630758,4.6826005),new OpenLayers.Geometry.Point(-74.0629041,4.6828902),new OpenLayers.Geometry.Point(-74.0627217,4.683255),new OpenLayers.Geometry.Point(-74.0627003,4.6836627),new OpenLayers.Geometry.Point(-74.062711,4.6838343),new OpenLayers.Geometry.Point(-74.0627754,4.6840489),new OpenLayers.Geometry.Point(-74.0630651,4.6846712),new OpenLayers.Geometry.Point(-74.0633762,4.685272),new OpenLayers.Geometry.Point(-74.0639019,4.6861732),new OpenLayers.Geometry.Point(-74.0645671,4.6876001),new OpenLayers.Geometry.Point(-74.0648353,4.6883404),new OpenLayers.Geometry.Point(-74.0649962,4.688952),new OpenLayers.Geometry.Point(-74.0650392,4.6891022),new OpenLayers.Geometry.Point(-74.0653396,4.6891451),new OpenLayers.Geometry.Point(-74.0672708,4.6895957),new OpenLayers.Geometry.Point(-74.067775,4.6896815),new OpenLayers.Geometry.Point(-74.0680218,4.6897137),new OpenLayers.Geometry.Point(-74.0686977,4.6897566),new OpenLayers.Geometry.Point(-74.0690196,4.6897674),new OpenLayers.Geometry.Point(-74.0694058,4.6897566),new OpenLayers.Geometry.Point(-74.0698349,4.6897244),new OpenLayers.Geometry.Point(-74.0705431,4.6896064),new OpenLayers.Geometry.Point(-74.0710688,4.6894884),new OpenLayers.Geometry.Point(-74.071852,4.6892738),new OpenLayers.Geometry.Point(-74.0722919,4.6890807),new OpenLayers.Geometry.Point(-74.0731609,4.6886516),new OpenLayers.Geometry.Point(-74.0740407,4.68804),new OpenLayers.Geometry.Point(-74.0759289,4.6864951),new OpenLayers.Geometry.Point(-74.0767014,4.685905),new OpenLayers.Geometry.Point(-74.077785,4.6850252),new OpenLayers.Geometry.Point(-74.0780532,4.6848857),new OpenLayers.Geometry.Point(-74.0786862,4.6843171),new OpenLayers.Geometry.Point(-74.0790617,4.684006),new OpenLayers.Geometry.Point(-74.0801024,4.6831799),new OpenLayers.Geometry.Point(-74.0802634,4.6831262),new OpenLayers.Geometry.Point(-74.0809608,4.682579),new OpenLayers.Geometry.Point(-74.0810359,4.6825039),new OpenLayers.Geometry.Point(-74.0833104,4.6807015),new OpenLayers.Geometry.Point(-74.0847266,4.6795106),new OpenLayers.Geometry.Point(-74.0851128,4.6791351),new OpenLayers.Geometry.Point(-74.0852737,4.678942),new OpenLayers.Geometry.Point(-74.0856707,4.6783626),new OpenLayers.Geometry.Point(-74.0860248,4.6776545),new OpenLayers.Geometry.Point(-74.0862823,4.6772254),new OpenLayers.Geometry.Point(-74.0864861,4.677161),new OpenLayers.Geometry.Point(-74.0865827,4.6771932),new OpenLayers.Geometry.Point(-74.086647,4.6772361),new OpenLayers.Geometry.Point(-74.0872693,4.6778154),new OpenLayers.Geometry.Point(-74.0877092,4.6782768),new OpenLayers.Geometry.Point(-74.0882027,4.6787059),new OpenLayers.Geometry.Point(-74.0885246,4.6783841),new OpenLayers.Geometry.Point(-74.0886962,4.6781588),new OpenLayers.Geometry.Point(-74.0890718,4.6777833),new OpenLayers.Geometry.Point(-74.0893185,4.6775043),new OpenLayers.Geometry.Point(-74.0894687,4.6773648),new OpenLayers.Geometry.Point(-74.0891474,4.6770327),new OpenLayers.Geometry.Point(-74.0889645,4.6768498),new OpenLayers.Geometry.Point(-74.0884817,4.67641),new OpenLayers.Geometry.Point(-74.0884387,4.6763349),new OpenLayers.Geometry.Point(-74.0893292,4.6751869),new OpenLayers.Geometry.Point(-74.0881705,4.6739638),new OpenLayers.Geometry.Point(-74.0884924,4.6734273),new OpenLayers.Geometry.Point(-74.088428,4.6733952),new OpenLayers.Geometry.Point(-74.0889752,4.672451),new OpenLayers.Geometry.Point(-74.0897477,4.6712601),new OpenLayers.Geometry.Point(-74.0921187,4.6682024),new OpenLayers.Geometry.Point(-74.0922797,4.6679449),new OpenLayers.Geometry.Point(-74.0925157,4.6676123),new OpenLayers.Geometry.Point(-74.0928161,4.6673548),new OpenLayers.Geometry.Point(-74.0932453,4.666872),new OpenLayers.Geometry.Point(-74.0934062,4.6666467),new OpenLayers.Geometry.Point(-74.0935135,4.6666145),new OpenLayers.Geometry.Point(-74.0936637,4.6665394),new OpenLayers.Geometry.Point(-74.0938139,4.6662283),new OpenLayers.Geometry.Point(-74.0937817,4.6659923),new OpenLayers.Geometry.Point(-74.0935993,4.6657991),new OpenLayers.Geometry.Point(-74.0932989,4.6656811),new OpenLayers.Geometry.Point(-74.0930843,4.6657562),new OpenLayers.Geometry.Point(-74.0927839,4.6654558),new OpenLayers.Geometry.Point(-74.0925694,4.6651125),new OpenLayers.Geometry.Point(-74.0922904,4.6647263),new OpenLayers.Geometry.Point(-74.0911961,4.6630955),new OpenLayers.Geometry.Point(-74.0893507,4.6604347),new OpenLayers.Geometry.Point(-74.0892112,4.6601987),new OpenLayers.Geometry.Point(-74.0890932,4.6599412),new OpenLayers.Geometry.Point(-74.0890396,4.6596837),new OpenLayers.Geometry.Point(-74.0890825,4.6594906),new OpenLayers.Geometry.Point(-74.089061,4.6592009),new OpenLayers.Geometry.Point(-74.0890932,4.6589434),new OpenLayers.Geometry.Point(-74.0891683,4.6586752),new OpenLayers.Geometry.Point(-74.0906489,4.6552956),new OpenLayers.Geometry.Point(-74.091239,4.6540189),new OpenLayers.Geometry.Point(-74.0914965,4.653579),new OpenLayers.Geometry.Point(-74.0917969,4.6531928),new OpenLayers.Geometry.Point(-74.0919363,4.6530426),new OpenLayers.Geometry.Point(-74.0923655,4.6526456),new OpenLayers.Geometry.Point(-74.0931702,4.6520662),new OpenLayers.Geometry.Point(-74.0972149,4.6549308),new OpenLayers.Geometry.Point(-74.0980991,4.6555415),new OpenLayers.Geometry.Point(-74.0980991,4.6555415),new OpenLayers.Geometry.Point(-74.0990388,4.6561861),new OpenLayers.Geometry.Point(-74.1004014,4.6571732),new OpenLayers.Geometry.Point(-74.1004658,4.6572161),new OpenLayers.Geometry.Point(-74.0981054,4.6602845),new OpenLayers.Geometry.Point(-74.095906,4.6630955),new OpenLayers.Geometry.Point(-74.0949404,4.6643615),new OpenLayers.Geometry.Point(-74.0947473,4.6645868),new OpenLayers.Geometry.Point(-74.0945649,4.664737),new OpenLayers.Geometry.Point(-74.0943396,4.6649837),new OpenLayers.Geometry.Point(-74.0942109,4.6649408),new OpenLayers.Geometry.Point(-74.0939856,4.6650589),new OpenLayers.Geometry.Point(-74.0935457,4.6654236),new OpenLayers.Geometry.Point(-74.0932989,4.6656811),new OpenLayers.Geometry.Point(-74.0930843,4.6657562),new OpenLayers.Geometry.Point(-74.0927839,4.6654558),new OpenLayers.Geometry.Point(-74.0925694,4.6651125),new OpenLayers.Geometry.Point(-74.0922904,4.6647263),new OpenLayers.Geometry.Point(-74.0911961,4.6630955),new OpenLayers.Geometry.Point(-74.0893507,4.6604347),new OpenLayers.Geometry.Point(-74.0892112,4.6601987),new OpenLayers.Geometry.Point(-74.0890932,4.6599412),new OpenLayers.Geometry.Point(-74.0890396,4.6596837),new OpenLayers.Geometry.Point(-74.0890825,4.6594906),new OpenLayers.Geometry.Point(-74.089061,4.6592009),new OpenLayers.Geometry.Point(-74.0889537,4.6590507),new OpenLayers.Geometry.Point(-74.0888357,4.6589649),new OpenLayers.Geometry.Point(-74.088707,4.6589327),new OpenLayers.Geometry.Point(-74.0884495,4.6589649),new OpenLayers.Geometry.Point(-74.0881276,4.6586537),new OpenLayers.Geometry.Point(-74.086411,4.6561432),new OpenLayers.Geometry.Point(-74.08566,4.6549952),new OpenLayers.Geometry.Point(-74.085263,4.6545017),new OpenLayers.Geometry.Point(-74.0850377,4.6542549),new OpenLayers.Geometry.Point(-74.0846622,4.6539545),new OpenLayers.Geometry.Point(-74.0842009,4.6535468),new OpenLayers.Geometry.Point(-74.0841901,4.653461),new OpenLayers.Geometry.Point(-74.0841579,4.6533751),new OpenLayers.Geometry.Point(-74.0840828,4.6532249),new OpenLayers.Geometry.Point(-74.0840185,4.6531391),new OpenLayers.Geometry.Point(-74.083997,4.6529996),new OpenLayers.Geometry.Point(-74.083997,4.6528602),new OpenLayers.Geometry.Point(-74.0840185,4.6527314),new OpenLayers.Geometry.Point(-74.0841794,4.6522593),new OpenLayers.Geometry.Point(-74.0845764,4.6512401),new OpenLayers.Geometry.Point(-74.0849411,4.6515191),new OpenLayers.Geometry.Point(-74.0854239,4.6518302),new OpenLayers.Geometry.Point(-74.0866792,4.6527207),new OpenLayers.Geometry.Point(-74.0870547,4.6529675),new OpenLayers.Geometry.Point(-74.0868616,4.6532679),new OpenLayers.Geometry.Point(-74.0865612,4.6536756),new OpenLayers.Geometry.Point(-74.0864646,4.6538579),new OpenLayers.Geometry.Point(-74.0864646,4.6538579),new OpenLayers.Geometry.Point(-74.0865612,4.6536756),new OpenLayers.Geometry.Point(-74.0868616,4.6532679),new OpenLayers.Geometry.Point(-74.0870547,4.6529675),new OpenLayers.Geometry.Point(-74.0866792,4.6527207),new OpenLayers.Geometry.Point(-74.0854239,4.6518302),new OpenLayers.Geometry.Point(-74.0849411,4.6515191),new OpenLayers.Geometry.Point(-74.0845764,4.6512401),new OpenLayers.Geometry.Point(-74.0844476,4.651165),new OpenLayers.Geometry.Point(-74.0839005,4.6525919),new OpenLayers.Geometry.Point(-74.0838253,4.65271),new OpenLayers.Geometry.Point(-74.0835893,4.6529353),new OpenLayers.Geometry.Point(-74.0833318,4.6530104),new OpenLayers.Geometry.Point(-74.0832353,4.6530747),new OpenLayers.Geometry.Point(-74.0830958,4.653064),new OpenLayers.Geometry.Point(-74.0827525,4.6529782),new OpenLayers.Geometry.Point(-74.0821838,4.6527529),new OpenLayers.Geometry.Point(-74.0818298,4.6526349),new OpenLayers.Geometry.Point(-74.0806925,4.652431),new OpenLayers.Geometry.Point(-74.0801346,4.6523023),new OpenLayers.Geometry.Point(-74.079963,4.6523452),new OpenLayers.Geometry.Point(-74.0796411,4.6522808),new OpenLayers.Geometry.Point(-74.0788794,4.6521628),new OpenLayers.Geometry.Point(-74.0781283,4.6520662),new OpenLayers.Geometry.Point(-74.0773559,4.651916),new OpenLayers.Geometry.Point(-74.0771842,4.6517551),new OpenLayers.Geometry.Point(-74.0771198,4.6516263),new OpenLayers.Geometry.Point(-74.0770984,4.6515298),new OpenLayers.Geometry.Point(-74.0771091,4.6513903),new OpenLayers.Geometry.Point(-74.0771413,4.6512616),new OpenLayers.Geometry.Point(-74.0771949,4.651165),new OpenLayers.Geometry.Point(-74.07727,4.6511006),new OpenLayers.Geometry.Point(-74.0773451,4.6510684),new OpenLayers.Geometry.Point(-74.0774846,4.6510684),new OpenLayers.Geometry.Point(-74.0778494,4.6511328),new OpenLayers.Geometry.Point(-74.0779352,4.651165),new OpenLayers.Geometry.Point(-74.0779996,4.6512508),new OpenLayers.Geometry.Point(-74.0780425,4.6513474),new OpenLayers.Geometry.Point(-74.0779352,4.6522593),new OpenLayers.Geometry.Point(-74.077903,4.6529353),new OpenLayers.Geometry.Point(-74.0777636,4.6547377),new OpenLayers.Geometry.Point(-74.0776455,4.6555853),new OpenLayers.Geometry.Point(-74.0775812,4.656229),new OpenLayers.Geometry.Point(-74.0771627,4.6584606),new OpenLayers.Geometry.Point(-74.0768838,4.6593833),new OpenLayers.Geometry.Point(-74.0766692,4.6602523),new OpenLayers.Geometry.Point(-74.0765297,4.6607351),new OpenLayers.Geometry.Point(-74.0760684,4.6627951),new OpenLayers.Geometry.Point(-74.0759182,4.6632564),new OpenLayers.Geometry.Point(-74.0758109,4.663825),new OpenLayers.Geometry.Point(-74.0757251,4.6640396),new OpenLayers.Geometry.Point(-74.0755856,4.6645331),new OpenLayers.Geometry.Point(-74.0752852,4.6650374),new OpenLayers.Geometry.Point(-74.0751135,4.6653807),new OpenLayers.Geometry.Point(-74.0748346,4.6657455),new OpenLayers.Geometry.Point(-74.0742981,4.6664),new OpenLayers.Geometry.Point(-74.0736651,4.6671295),new OpenLayers.Geometry.Point(-74.0733647,4.6674299),new OpenLayers.Geometry.Point(-74.0726352,4.6682775),new OpenLayers.Geometry.Point(-74.0721202,4.6689105),new OpenLayers.Geometry.Point(-74.0714014,4.6697474),new OpenLayers.Geometry.Point(-74.0708864,4.670316),new OpenLayers.Geometry.Point(-74.0705645,4.6707344),new OpenLayers.Geometry.Point(-74.0703714,4.6709597),new OpenLayers.Geometry.Point(-74.0702105,4.671185),new OpenLayers.Geometry.Point(-74.0696096,4.6717966),new OpenLayers.Geometry.Point(-74.0686548,4.6711206),new OpenLayers.Geometry.Point(-74.0663266,4.6696293),new OpenLayers.Geometry.Point(-74.0652001,4.6709919),new OpenLayers.Geometry.Point(-74.0650821,4.671185),new OpenLayers.Geometry.Point(-74.0650499,4.6712708),new OpenLayers.Geometry.Point(-74.06564,4.6720111),new OpenLayers.Geometry.Point(-74.0661657,4.6726012),new OpenLayers.Geometry.Point(-74.0664876,4.6729231),new OpenLayers.Geometry.Point(-74.0673351,4.6738458),new OpenLayers.Geometry.Point(-74.0673566,4.6739316),new OpenLayers.Geometry.Point(-74.0675068,4.6742105),new OpenLayers.Geometry.Point(-74.0676785,4.6744788),new OpenLayers.Geometry.Point(-74.0679252,4.6747684),new OpenLayers.Geometry.Point(-74.0679896,4.6749187),new OpenLayers.Geometry.Point(-74.0680003,4.674983),new OpenLayers.Geometry.Point(-74.0679681,4.6751118),new OpenLayers.Geometry.Point(-74.0679145,4.6752083),new OpenLayers.Geometry.Point(-74.0675604,4.6755946),new OpenLayers.Geometry.Point(-74.0672815,4.6759379),new OpenLayers.Geometry.Point(-74.0657473,4.6785343),new OpenLayers.Geometry.Point(-74.0656078,4.6788132),new OpenLayers.Geometry.Point(-74.0652001,4.6795213),new OpenLayers.Geometry.Point(-74.0640843,4.6812272),new OpenLayers.Geometry.Point(-74.0635049,4.6811414),new OpenLayers.Geometry.Point(-74.0624642,4.6809483),new OpenLayers.Geometry.Point(-74.0608656,4.68068),new OpenLayers.Geometry.Point(-74.0609117,4.68041),new OpenLayers.Geometry.Point(-74.0609117,4.68041))"
   });

db.actual.insert({
    "_class": "co.com.datatraffic.fieldvision.tracking.Actual",
    "resource": 
	{"_id":ObjectId("56f55709efe9e8d575768a61"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf1")),DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf5"))],"login": "SMG872","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db81")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db91"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db51")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
    "actualGeofences": [
      {
        "_id": ObjectId("571009a21dd20ebf5a071bed"),
        "name": "Sector I",
        "description": "Barrios: la granja, soledad norte, la almeria, tabora, santa maria del lago",
        "isActive": true,
        "isPublic": true,
        "geometry": {
          "type": "Polygon",
          "coordinates": [
            [
              [
                -74.100916,
                4.703048
              ],
              [
                -74.089029,
                4.695392
              ],
              [
                -74.094951,
                4.68718
              ],
              [
                -74.10568,
                4.69582
              ],
              [
                -74.100916,
                4.703048
              ]
            ]
          ]
        }
      }
    ],
    "actualCheckPoints": [
      {
        "_id": ObjectId("571009a21dd20ebf5a071bef"),
        "name": "Panaderia venecia",
        "description": "Panaderia de la castellana",
        "isActive": false,
        "isPublic": false,
        "geometry": {
          "type": "Point",
          "coordinates": [
            -74.114842,
            4.683897
          ]
        },
        "ratio": 0
      }
    ],
    "deviceData": {
        "GPS": {
            "imei": "00783180",
            "latitude": 4.673007,
            "longitude": -74.066906,
            "speed": 2,
            "address": "ENTURNE EST IMPALA BARRANCA (BARRANCABERMEJA)",
            "heading": 324,
            "updateTime": "2016-05-27 06:06:13",
            "ev": "00",
            "odometer": "33516506",
            "ignitionStatus": "OFF",
            "_class": "co.com.datatraffic.fieldvision.tracking.devices.GPS"
        },
        "Probe": {
            "instantConsumption": "0",
            "instantEstimatedConsumption": "0",
            "probeValue": "001b.0,0151.0",
            "levelGasTank": null,
            "status": 12,
            "totalConsumption": null,
            "totalEstimatedConsumption": "0",
            "created_at": "2016-05-27 06:06:13",
            "percentageLevelGasTank": 54.21,
            "fixedLevelGasTank": "38.9299999999999997",
            "_class": "co.com.datatraffic.fieldvision.tracking.devices.Probe"
        },
        "Trailer": {
            "_class": "co.com.datatraffic.fieldvision.tracking.devices.Trailer",
            "ev": false,
            "status": "SI"
        },
        "PassangerSensor": {
            "_class": "co.com.datatraffic.fieldvision.tracking.devices.PassangerSensor",
            "ev": false,
            "status": "NO"
        },
        "Ecumonitor": {
            "_class": "co.com.datatraffic.fieldvision.tracking.devices.Ecumonitor",
            "RPM": 220,
            "totlaFuelConsuption": 300.5,
            "totalTraveledDistance": 499999,
            "totalFuelComsumtionWhileIdle": 888,
            "tripOdometer": 990,
            "engineUsage": 900,
            "totalTimeWhileEngineIdle": 90.9,
            "instantFuelConsumption": 950,
            "dataTroubleCode": "SD",
            "onBoardFuelLevel": 89
        },
        "PanicButton": {
            "ev": "20",
            "status": "NO",
            "_class": "co.com.datatraffic.fieldvision.tracking.devices.PanicButton"
        }
    },
		"latitude" : 4.673007,
		"longitude" : -74.066906,
        "speed" : 2,
        "address" : "ENTURNE EST IMPALA BARRANCA (BARRANCABERMEJA)",
        "heading" : 324,
        "updateTime" : "2016-05-27 06:06:13",
        "hasEvent" : false,
        "distance" : "140",
        "odometer" : "33516506",
        "totalDistance" : "171",
        "created_at" : "2016-05-27 06:06:15",
        "id_rawData" : "106650000",
        "isVisible" : true,	
  "tasks": [
    {
      "_id": ObjectId("572bf278c740cde2218b45ce"),
      "type": "Inicio",
      "code": "571d8a0fc740cd5136be5c53",
      "loadAmount": 0,
      "status": "PENDIENTE",
      "name": "Bodega 2",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "18:00",
      "duration": 0,
      "updated_at": "2016-05-06 01:25:12",
      "created_at": "2016-05-06 01:25:12",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "HOA521",
          "type": "carro",
          "capacidad": 100,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89f5c740cd5036be5c58"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 703000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 703000
          }
        }
      ],
      "location_id": "571d8a0fc740cd5136be5c53",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "08:00"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b459d"),
      "type": "pickup",
      "code": "572bf25dc740cdb5218b4582",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 28",
      "location": {
        "name": "Carrera 62 98, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68757,
        "lng": -74.06814
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "HOA521",
          "type": "carro",
          "capacidad": 100,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89f5c740cd5036be5c58"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 718000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 718000
          }
        }
      ],
      "location_id": "572bf25dc740cdb5218b4582",
      "location_name": "Carrera 62 98, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "08:08",
      "finish_time": "09:08"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4569"),
      "type": "pickup",
      "code": "572bf255c740cdb5218b4568",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 2",
      "location": {
        "name": "Carrera 18B 120, 110111 Bogot\u00e1, Colombia",
        "lat": 4.70054,
        "lng": -74.04791
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "HOA521",
          "type": "carro",
          "capacidad": 100,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89f5c740cd5036be5c58"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 733000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 733000
          }
        }
      ],
      "location_id": "572bf255c740cdb5218b4568",
      "location_name": "Carrera 18B 120, 110111 Bogot\u00e1, Colombia",
      "arrival_time": "09:27",
      "finish_time": "10:27"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b457f"),
      "type": "pickup",
      "code": "572bf258c740cdb5218b4573",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 13",
      "location": {
        "name": "Calle 122-99, 110111 Bogot\u00e1, Colombia",
        "lat": 4.7017854,
        "lng": -74.0501239
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "HOA521",
          "type": "carro",
          "capacidad": 100,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89f5c740cd5036be5c58"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 749000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 749000
          }
        }
      ],
      "location_id": "572bf258c740cdb5218b4573",
      "location_name": "Calle 122-99, 110111 Bogot\u00e1, Colombia",
      "arrival_time": "10:29",
      "finish_time": "11:29"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b458b"),
      "type": "pickup",
      "code": "572bf25ac740cdb5218b4579",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 19",
      "location": {
        "name": "Carrera 46 123, 111111 Bogot\u00e1, Colombia",
        "lat": 4.7046599,
        "lng": -74.05587
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "HOA521",
          "type": "carro",
          "capacidad": 100,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89f5c740cd5036be5c58"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 764000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 764000
          }
        }
      ],
      "location_id": "572bf25ac740cdb5218b4579",
      "location_name": "Carrera 46 123, 111111 Bogot\u00e1, Colombia",
      "arrival_time": "11:40",
      "finish_time": "12:40"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b459e"),
      "type": "dropoff",
      "code": "572bf25dc740cdb5218b4582",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 28",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "HOA521",
          "type": "carro",
          "capacidad": 100,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89f5c740cd5036be5c58"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 779000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 779000
          }
        }
      ],
      "location_id": "572bf25dc740cdb5218b4582",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "12:54",
      "finish_time": "13:54"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b458c"),
      "type": "dropoff",
      "code": "572bf25ac740cdb5218b4579",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 19",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "HOA521",
          "type": "carro",
          "capacidad": 100,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89f5c740cd5036be5c58"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 794000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 794000
          }
        }
      ],
      "location_id": "572bf25ac740cdb5218b4579",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "13:54",
      "finish_time": "14:54"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b456a"),
      "type": "dropoff",
      "code": "572bf255c740cdb5218b4568",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 2",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "HOA521",
          "type": "carro",
          "capacidad": 100,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89f5c740cd5036be5c58"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 808000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 808000
          }
        }
      ],
      "location_id": "572bf255c740cdb5218b4568",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "14:54",
      "finish_time": "15:54"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4580"),
      "type": "dropoff",
      "code": "572bf258c740cdb5218b4573",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 13",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "HOA521",
          "type": "carro",
          "capacidad": 100,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89f5c740cd5036be5c58"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 823000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 823000
          }
        }
      ],
      "location_id": "572bf258c740cdb5218b4573",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "15:54",
      "finish_time": "16:54"
    },
    {
      "_id": ObjectId("572bf278c740cde2218b45cf"),
      "type": "Fin",
      "code": "571d8a2ac740cdeb4ebe5c53",
      "loadAmount": 0,
      "status": "PENDIENTE",
      "name": "Bodega 1",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "18:00",
      "duration": 0,
      "updated_at": "2016-05-06 01:25:12",
      "created_at": "2016-05-06 01:25:12",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "HOA521",
          "type": "carro",
          "capacidad": 100,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89f5c740cd5036be5c58"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 844000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 844000
          }
        }
      ],
      "location_id": "571d8a2ac740cdeb4ebe5c53",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "16:54"
    }
  ]
,
"javaScriptShape": "new Array(new OpenLayers.Geometry.Point(-74.0609117,4.68041),new OpenLayers.Geometry.Point(-74.0608656,4.68068),new OpenLayers.Geometry.Point(-74.0624642,4.6809483),new OpenLayers.Geometry.Point(-74.0635049,4.6811414),new OpenLayers.Geometry.Point(-74.0633333,4.6822357),new OpenLayers.Geometry.Point(-74.0630758,4.6826005),new OpenLayers.Geometry.Point(-74.0629041,4.6828902),new OpenLayers.Geometry.Point(-74.0627217,4.683255),new OpenLayers.Geometry.Point(-74.0627003,4.6836627),new OpenLayers.Geometry.Point(-74.062711,4.6838343),new OpenLayers.Geometry.Point(-74.0627754,4.6840489),new OpenLayers.Geometry.Point(-74.0629148,4.6839738),new OpenLayers.Geometry.Point(-74.0629685,4.6839094),new OpenLayers.Geometry.Point(-74.0632474,4.683845),new OpenLayers.Geometry.Point(-74.0633547,4.6838772),new OpenLayers.Geometry.Point(-74.0649533,4.6848643),new OpenLayers.Geometry.Point(-74.0648782,4.68539),new OpenLayers.Geometry.Point(-74.0664768,4.6856046),new OpenLayers.Geometry.Point(-74.0667236,4.6856475),new OpenLayers.Geometry.Point(-74.0672171,4.6857655),new OpenLayers.Geometry.Point(-74.0673566,4.6858191),new OpenLayers.Geometry.Point(-74.0686762,4.6865058),new OpenLayers.Geometry.Point(-74.0681411,4.6875704),new OpenLayers.Geometry.Point(-74.0686762,4.6865058),new OpenLayers.Geometry.Point(-74.069674,4.6870208),new OpenLayers.Geometry.Point(-74.0687728,4.6888232),new OpenLayers.Geometry.Point(-74.068408,4.6895206),new OpenLayers.Geometry.Point(-74.0683973,4.6895957),new OpenLayers.Geometry.Point(-74.0676999,4.6895206),new OpenLayers.Geometry.Point(-74.0649962,4.688952),new OpenLayers.Geometry.Point(-74.0626574,4.6882653),new OpenLayers.Geometry.Point(-74.0591383,4.6872783),new OpenLayers.Geometry.Point(-74.0586126,4.6871603),new OpenLayers.Geometry.Point(-74.0584409,4.6870959),new OpenLayers.Geometry.Point(-74.0573251,4.6868491),new OpenLayers.Geometry.Point(-74.0570891,4.6868169),new OpenLayers.Geometry.Point(-74.0559411,4.6865702),new OpenLayers.Geometry.Point(-74.0558231,4.6864951),new OpenLayers.Geometry.Point(-74.0555978,4.68642),new OpenLayers.Geometry.Point(-74.0555656,4.6863878),new OpenLayers.Geometry.Point(-74.0555334,4.6863019),new OpenLayers.Geometry.Point(-74.0555549,4.6861947),new OpenLayers.Geometry.Point(-74.0556407,4.6861303),new OpenLayers.Geometry.Point(-74.0557373,4.6861088),new OpenLayers.Geometry.Point(-74.0561128,4.6862698),new OpenLayers.Geometry.Point(-74.056735,4.686377),new OpenLayers.Geometry.Point(-74.0568531,4.68642),new OpenLayers.Geometry.Point(-74.0569389,4.6865058),new OpenLayers.Geometry.Point(-74.0569711,4.6866345),new OpenLayers.Geometry.Point(-74.0568316,4.6874499),new OpenLayers.Geometry.Point(-74.0566921,4.6881151),new OpenLayers.Geometry.Point(-74.0566385,4.6884906),new OpenLayers.Geometry.Point(-74.0565848,4.688555),new OpenLayers.Geometry.Point(-74.0564775,4.6891773),new OpenLayers.Geometry.Point(-74.0564346,4.689585),new OpenLayers.Geometry.Point(-74.0562308,4.6907973),new OpenLayers.Geometry.Point(-74.0562201,4.6917093),new OpenLayers.Geometry.Point(-74.0562308,4.6918058),new OpenLayers.Geometry.Point(-74.0559947,4.6930826),new OpenLayers.Geometry.Point(-74.0559196,4.6936727),new OpenLayers.Geometry.Point(-74.0554798,4.6962798),new OpenLayers.Geometry.Point(-74.055233,4.697696),new OpenLayers.Geometry.Point(-74.0552008,4.6977603),new OpenLayers.Geometry.Point(-74.0548897,4.6994662),new OpenLayers.Geometry.Point(-74.0548253,4.6999276),new OpenLayers.Geometry.Point(-74.0545464,4.700861),new OpenLayers.Geometry.Point(-74.0545142,4.7009146),new OpenLayers.Geometry.Point(-74.0542996,4.7021484),new OpenLayers.Geometry.Point(-74.0541172,4.7033393),new OpenLayers.Geometry.Point(-74.0537953,4.7053349),new OpenLayers.Geometry.Point(-74.0530229,4.7052169),new OpenLayers.Geometry.Point(-74.0505767,4.7047985),new OpenLayers.Geometry.Point(-74.0507269,4.7040153),new OpenLayers.Geometry.Point(-74.0510488,4.7020948),new OpenLayers.Geometry.Point(-74.0485597,4.7016549),new OpenLayers.Geometry.Point(-74.0486991,4.7008932),new OpenLayers.Geometry.Point(-74.0481627,4.7008073),new OpenLayers.Geometry.Point(-74.0486991,4.7008932),new OpenLayers.Geometry.Point(-74.0485597,4.7016549),new OpenLayers.Geometry.Point(-74.0500983,4.701927),new OpenLayers.Geometry.Point(-74.0510488,4.7020948),new OpenLayers.Geometry.Point(-74.0507269,4.7040153),new OpenLayers.Geometry.Point(-74.0505767,4.7047985),new OpenLayers.Geometry.Point(-74.0530229,4.7052169),new OpenLayers.Geometry.Point(-74.0537953,4.7053349),new OpenLayers.Geometry.Point(-74.0537632,4.7055066),new OpenLayers.Geometry.Point(-74.0536344,4.7066545),new OpenLayers.Geometry.Point(-74.0535057,4.7071481),new OpenLayers.Geometry.Point(-74.0533125,4.7072661),new OpenLayers.Geometry.Point(-74.0531838,4.7072768),new OpenLayers.Geometry.Point(-74.0530336,4.7072446),new OpenLayers.Geometry.Point(-74.0529156,4.7071803),new OpenLayers.Geometry.Point(-74.0528405,4.7071159),new OpenLayers.Geometry.Point(-74.0527976,4.7070301),new OpenLayers.Geometry.Point(-74.0527868,4.706912),new OpenLayers.Geometry.Point(-74.052819,4.7068262),new OpenLayers.Geometry.Point(-74.0530658,4.7066653),new OpenLayers.Geometry.Point(-74.0537953,4.706794),new OpenLayers.Geometry.Point(-74.0539777,4.7068048),new OpenLayers.Geometry.Point(-74.0542352,4.7068691),new OpenLayers.Geometry.Point(-74.0548575,4.7069764),new OpenLayers.Geometry.Point(-74.0549648,4.7070837),new OpenLayers.Geometry.Point(-74.054997,4.7071803),new OpenLayers.Geometry.Point(-74.0549755,4.7072768),new OpenLayers.Geometry.Point(-74.0549004,4.7073734),new OpenLayers.Geometry.Point(-74.0546751,4.7075236),new OpenLayers.Geometry.Point(-74.0544498,4.7076523),new OpenLayers.Geometry.Point(-74.0543211,4.7076845),new OpenLayers.Geometry.Point(-74.0542352,4.7076631),new OpenLayers.Geometry.Point(-74.0541708,4.707588),new OpenLayers.Geometry.Point(-74.0541494,4.7073627),new OpenLayers.Geometry.Point(-74.0542459,4.706794),new OpenLayers.Geometry.Point(-74.0542567,4.7065151),new OpenLayers.Geometry.Point(-74.0543103,4.7061718),new OpenLayers.Geometry.Point(-74.0543854,4.7054744),new OpenLayers.Geometry.Point(-74.0545034,4.704895),new OpenLayers.Geometry.Point(-74.0552115,4.7050023),new OpenLayers.Geometry.Point(-74.0554154,4.7036612),new OpenLayers.Geometry.Point(-74.0560162,4.7037685),new OpenLayers.Geometry.Point(-74.0558599,4.7046581),new OpenLayers.Geometry.Point(-74.0560162,4.7037685),new OpenLayers.Geometry.Point(-74.0554154,4.7036612),new OpenLayers.Geometry.Point(-74.0555227,4.7030497),new OpenLayers.Geometry.Point(-74.0556085,4.7024381),new OpenLayers.Geometry.Point(-74.0557158,4.7018909),new OpenLayers.Geometry.Point(-74.0553403,4.7018373),new OpenLayers.Geometry.Point(-74.0551901,4.7016978),new OpenLayers.Geometry.Point(-74.0550935,4.7015691),new OpenLayers.Geometry.Point(-74.0554154,4.699595),new OpenLayers.Geometry.Point(-74.0554476,4.6992195),new OpenLayers.Geometry.Point(-74.0555334,4.6989834),new OpenLayers.Geometry.Point(-74.0556622,4.6981359),new OpenLayers.Geometry.Point(-74.0559304,4.6966338),new OpenLayers.Geometry.Point(-74.0559947,4.695797),new OpenLayers.Geometry.Point(-74.055984,4.6952069),new OpenLayers.Geometry.Point(-74.0561771,4.6936941),new OpenLayers.Geometry.Point(-74.0563703,4.6925461),new OpenLayers.Geometry.Point(-74.0566385,4.6909904),new OpenLayers.Geometry.Point(-74.0566921,4.6909475),new OpenLayers.Geometry.Point(-74.0569496,4.6902931),new OpenLayers.Geometry.Point(-74.0572393,4.688834),new OpenLayers.Geometry.Point(-74.057368,4.6878791),new OpenLayers.Geometry.Point(-74.0573466,4.687804),new OpenLayers.Geometry.Point(-74.0573466,4.6876752),new OpenLayers.Geometry.Point(-74.0573144,4.6875572),new OpenLayers.Geometry.Point(-74.0573359,4.6870744),new OpenLayers.Geometry.Point(-74.0574002,4.6868706),new OpenLayers.Geometry.Point(-74.0576148,4.685626),new OpenLayers.Geometry.Point(-74.0577757,4.6850681),new OpenLayers.Geometry.Point(-74.0578938,4.6847463),new OpenLayers.Geometry.Point(-74.0579796,4.6843171),new OpenLayers.Geometry.Point(-74.0583658,4.6820104),new OpenLayers.Geometry.Point(-74.058677,4.6803153),new OpenLayers.Geometry.Point(-74.0592349,4.6804225),new OpenLayers.Geometry.Point(-74.0608656,4.68068),new OpenLayers.Geometry.Point(-74.0609117,4.68041),new OpenLayers.Geometry.Point(-74.0609117,4.68041))"
    });

db.actual.insert({
	"_class": "co.com.datatraffic.fieldvision.tracking.Actual",
    "resource":		
		{"_id":ObjectId("56f55709efe9e8d575768a62"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf1"))],"login": "SMG846","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db82")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db92"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db52")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
    "actualGeofences": [
      {
        "_id": ObjectId("571009a21dd20ebf5a071bed"),
        "name": "Sector I",
        "description": "Barrios: la granja, soledad norte, la almeria, tabora, santa maria del lago",
        "isActive": true,
        "isPublic": true,
        "geometry": {
          "type": "Polygon",
          "coordinates": [
            [
              [
                -74.100916,
                4.703048
              ],
              [
                -74.089029,
                4.695392
              ],
              [
                -74.094951,
                4.68718
              ],
              [
                -74.10568,
                4.69582
              ],
              [
                -74.100916,
                4.703048
              ]
            ]
          ]
        }
      }
    ],
    "actualCheckPoints": [
      {
        "_id": ObjectId("571009a21dd20ebf5a071bef"),
        "name": "Panaderia venecia",
        "description": "Panaderia de la castellana",
        "isActive": false,
        "isPublic": false,
        "geometry": {
          "type": "Point",
          "coordinates": [
            -74.114842,
            4.683897
          ]
        },
        "ratio": 0
      }
    ],
    "deviceData": [],
		"latitude" : 4.655555,
		"longitude" : -74.066906,
        "speed" : 2,
        "address" : "ENTURNE EST IMPALA BARRANCA (BARRANCABERMEJA)",
        "heading" : 324,
        "updateTime" : "2016-05-27 06:06:13",
        "hasEvent" : false,
        "distance" : "140",
        "odometer" : "33516506",
        "totalDistance" : "171",
        "created_at" : "2016-05-27 06:06:15",
        "id_rawData" : "106650000",
        "isVisible" : true,	
		"tasks": [
    {
      "_id": ObjectId("572bf278c740cde2218b45cb"),
      "type": "Inicio",
      "code": "571d8a0fc740cd5136be5c53",
      "loadAmount": 0,
      "status": "PENDIENTE",
      "name": "Bodega 2",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "18:00",
      "duration": 0,
      "updated_at": "2016-05-06 01:25:12",
      "created_at": "2016-05-06 01:25:12",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 130000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 130000
          }
        }
      ],
      "location_id": "571d8a0fc740cd5136be5c53",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "08:00"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4597"),
      "type": "pickup",
      "code": "572bf25cc740cdb5218b457f",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 25",
      "location": {
        "name": "Diagonal 68 12, 110231 Bogot\u00e1, Colombia",
        "lat": 4.65446,
        "lng": -74.06226
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 146000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 146000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457f",
      "location_name": "Diagonal 68 12, 110231 Bogot\u00e1, Colombia",
      "arrival_time": "08:18",
      "finish_time": "09:18"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4595"),
      "type": "pickup",
      "code": "572bf25cc740cdb5218b457e",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 24",
      "location": {
        "name": "Avenida Calle 72 12, Bogot\u00e1, Colombia",
        "lat": 4.65818,
        "lng": -74.06005
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 160000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 160000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457e",
      "location_name": "Avenida Calle 72 12, Bogot\u00e1, Colombia",
      "arrival_time": "09:27",
      "finish_time": "10:27"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b45b1"),
      "type": "pickup",
      "code": "572bf260c740cdb5218b458c",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 38",
      "location": {
        "name": "Avenida Calle 72 21, 111211 Bogot\u00e1, Colombia",
        "lat": 4.66198,
        "lng": -74.0655
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 174000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 174000
          }
        }
      ],
      "location_id": "572bf260c740cdb5218b458c",
      "location_name": "Avenida Calle 72 21, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "10:31",
      "finish_time": "11:31"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b45b2"),
      "type": "dropoff",
      "code": "572bf260c740cdb5218b458c",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 38",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 188000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 188000
          }
        }
      ],
      "location_id": "572bf260c740cdb5218b458c",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "11:48",
      "finish_time": "12:48"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4596"),
      "type": "dropoff",
      "code": "572bf25cc740cdb5218b457e",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 24",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 205000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 205000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457e",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "12:48",
      "finish_time": "13:48"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4598"),
      "type": "dropoff",
      "code": "572bf25cc740cdb5218b457f",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 25",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 220000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 220000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457f",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "13:48",
      "finish_time": "14:48"
    },
    {
      "_id": ObjectId("572bf278c740cde2218b45cc"),
      "type": "Fin",
      "code": "571d8a2ac740cdeb4ebe5c53",
      "loadAmount": 0,
      "status": "PENDIENTE",
      "name": "Bodega 1",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "18:00",
      "duration": 0,
      "updated_at": "2016-05-06 01:25:12",
      "created_at": "2016-05-06 01:25:12",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 240000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 240000
          }
        }
      ],
      "location_id": "571d8a2ac740cdeb4ebe5c53",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "14:48"
    }
  ],
		"javaScriptShape": "new Array(new OpenLayers.Geometry.Point(-74.0609117,4.68041),new OpenLayers.Geometry.Point(-74.0606403,4.6820641),new OpenLayers.Geometry.Point(-74.0600932,4.6819782),new OpenLayers.Geometry.Point(-74.0599108,4.6829653),new OpenLayers.Geometry.Point(-74.0597713,4.6838772),new OpenLayers.Geometry.Point(-74.0581083,4.6835876),new OpenLayers.Geometry.Point(-74.0583873,4.6819246),new OpenLayers.Geometry.Point(-74.0583122,4.6809375),new OpenLayers.Geometry.Point(-74.058398,4.6803474),new OpenLayers.Geometry.Point(-74.0585804,4.6793497),new OpenLayers.Geometry.Point(-74.0585911,4.6791351),new OpenLayers.Geometry.Point(-74.058795,4.6781695),new OpenLayers.Geometry.Point(-74.0587842,4.6780729),new OpenLayers.Geometry.Point(-74.0588486,4.677676),new OpenLayers.Geometry.Point(-74.0589452,4.6774185),new OpenLayers.Geometry.Point(-74.0592778,4.6766996),new OpenLayers.Geometry.Point(-74.0593314,4.6764314),new OpenLayers.Geometry.Point(-74.0601146,4.6715927),new OpenLayers.Geometry.Point(-74.0601575,4.6711528),new OpenLayers.Geometry.Point(-74.0601361,4.6709919),new OpenLayers.Geometry.Point(-74.0603507,4.6697474),new OpenLayers.Geometry.Point(-74.0603614,4.6695971),new OpenLayers.Geometry.Point(-74.060297,4.6691895),new OpenLayers.Geometry.Point(-74.060415,4.6685565),new OpenLayers.Geometry.Point(-74.0606189,4.6677732),new OpenLayers.Geometry.Point(-74.0610051,4.6661639),new OpenLayers.Geometry.Point(-74.0610802,4.6659172),new OpenLayers.Geometry.Point(-74.0610588,4.6657348),new OpenLayers.Geometry.Point(-74.0610373,4.6656275),new OpenLayers.Geometry.Point(-74.0610373,4.6655309),new OpenLayers.Geometry.Point(-74.0611017,4.6646082),new OpenLayers.Geometry.Point(-74.0617239,4.6608961),new OpenLayers.Geometry.Point(-74.0618634,4.6602201),new OpenLayers.Geometry.Point(-74.0620029,4.6594048),new OpenLayers.Geometry.Point(-74.0621424,4.6584392),new OpenLayers.Geometry.Point(-74.0624857,4.6565402),new OpenLayers.Geometry.Point(-74.0631294,4.6566474),new OpenLayers.Geometry.Point(-74.0632153,4.6565723),new OpenLayers.Geometry.Point(-74.0633547,4.6563363),new OpenLayers.Geometry.Point(-74.0633976,4.6562076),new OpenLayers.Geometry.Point(-74.062711,4.6557784),new OpenLayers.Geometry.Point(-74.0625501,4.6556926),new OpenLayers.Geometry.Point(-74.0614235,4.654609),new OpenLayers.Geometry.Point(-74.0615952,4.6544588),new OpenLayers.Geometry.Point(-74.0617347,4.6543944),new OpenLayers.Geometry.Point(-74.0618956,4.6543622),new OpenLayers.Geometry.Point(-74.0620244,4.6543837),new OpenLayers.Geometry.Point(-74.0622614,4.6544556),new OpenLayers.Geometry.Point(-74.0626252,4.654566),new OpenLayers.Geometry.Point(-74.062711,4.6541476),new OpenLayers.Geometry.Point(-74.0618849,4.653815),new OpenLayers.Geometry.Point(-74.0619063,4.6541154),new OpenLayers.Geometry.Point(-74.0618956,4.6543622),new OpenLayers.Geometry.Point(-74.0617347,4.6543944),new OpenLayers.Geometry.Point(-74.0615952,4.6544588),new OpenLayers.Geometry.Point(-74.0614235,4.654609),new OpenLayers.Geometry.Point(-74.060297,4.6536005),new OpenLayers.Geometry.Point(-74.0599751,4.6533322),new OpenLayers.Geometry.Point(-74.0592241,4.6526349),new OpenLayers.Geometry.Point(-74.0588915,4.6531928),new OpenLayers.Geometry.Point(-74.058398,4.6538687),new OpenLayers.Geometry.Point(-74.0580654,4.6543944),new OpenLayers.Geometry.Point(-74.0579581,4.654609),new OpenLayers.Geometry.Point(-74.0575826,4.6551776),new OpenLayers.Geometry.Point(-74.057014,4.6559179),new OpenLayers.Geometry.Point(-74.0569282,4.6560359),new OpenLayers.Geometry.Point(-74.0596962,4.6579456),new OpenLayers.Geometry.Point(-74.0600494,4.6581811),new OpenLayers.Geometry.Point(-74.0604043,4.658407),new OpenLayers.Geometry.Point(-74.0613699,4.6591151),new OpenLayers.Geometry.Point(-74.0615308,4.6592546),new OpenLayers.Geometry.Point(-74.0617454,4.6593833),new OpenLayers.Geometry.Point(-74.0619707,4.659555),new OpenLayers.Geometry.Point(-74.0625286,4.6599197),new OpenLayers.Geometry.Point(-74.0631831,4.6604133),new OpenLayers.Geometry.Point(-74.06461,4.6613789),new OpenLayers.Geometry.Point(-74.0652752,4.661808),new OpenLayers.Geometry.Point(-74.0655005,4.6619795),new OpenLayers.Geometry.Point(-74.0657258,4.6621513),new OpenLayers.Geometry.Point(-74.0670776,4.6630847),new OpenLayers.Geometry.Point(-74.0665519,4.6640396),new OpenLayers.Geometry.Point(-74.0657473,4.665252),new OpenLayers.Geometry.Point(-74.065479,4.6657133),new OpenLayers.Geometry.Point(-74.0666807,4.6664751),new OpenLayers.Geometry.Point(-74.0660369,4.6674943),new OpenLayers.Geometry.Point(-74.0655863,4.6681488),new OpenLayers.Geometry.Point(-74.0645027,4.6698654),new OpenLayers.Geometry.Point(-74.0655756,4.6705306),new OpenLayers.Geometry.Point(-74.0652001,4.6709919),new OpenLayers.Geometry.Point(-74.0650821,4.671185),new OpenLayers.Geometry.Point(-74.0650499,4.6712708),new OpenLayers.Geometry.Point(-74.06564,4.6720111),new OpenLayers.Geometry.Point(-74.0661657,4.6726012),new OpenLayers.Geometry.Point(-74.0664876,4.6729231),new OpenLayers.Geometry.Point(-74.0673351,4.6738458),new OpenLayers.Geometry.Point(-74.0673566,4.6739316),new OpenLayers.Geometry.Point(-74.0675068,4.6742105),new OpenLayers.Geometry.Point(-74.0676785,4.6744788),new OpenLayers.Geometry.Point(-74.0679252,4.6747684),new OpenLayers.Geometry.Point(-74.0679896,4.6749187),new OpenLayers.Geometry.Point(-74.0680003,4.674983),new OpenLayers.Geometry.Point(-74.0679681,4.6751118),new OpenLayers.Geometry.Point(-74.0679145,4.6752083),new OpenLayers.Geometry.Point(-74.0675604,4.6755946),new OpenLayers.Geometry.Point(-74.0672815,4.6759379),new OpenLayers.Geometry.Point(-74.0657473,4.6785343),new OpenLayers.Geometry.Point(-74.0656078,4.6788132),new OpenLayers.Geometry.Point(-74.0652001,4.6795213),new OpenLayers.Geometry.Point(-74.0640843,4.6812272),new OpenLayers.Geometry.Point(-74.0635049,4.6811414),new OpenLayers.Geometry.Point(-74.0624642,4.6809483),new OpenLayers.Geometry.Point(-74.0608656,4.68068),new OpenLayers.Geometry.Point(-74.0609117,4.68041),new OpenLayers.Geometry.Point(-74.0609117,4.68041))"
    });

db.actual.insert({
    "_class": "co.com.datatraffic.fieldvision.tracking.Actual",
    "resource":		
		{"_id":ObjectId("56f55709efe9e8d575768a63"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf1"))],"login": "UYX480","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db83")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db93"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db53")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
    "actualGeofences": [
      {
        "_id": ObjectId("571009a21dd20ebf5a071bed"),
        "name": "Sector I",
        "description": "Barrios: la granja, soledad norte, la almeria, tabora, santa maria del lago",
        "isActive": true,
        "isPublic": true,
        "geometry": {
          "type": "Polygon",
          "coordinates": [
            [
              [
                -74.100916,
                4.703048
              ],
              [
                -74.089029,
                4.695392
              ],
              [
                -74.094951,
                4.68718
              ],
              [
                -74.10568,
                4.69582
              ],
              [
                -74.100916,
                4.703048
              ]
            ]
          ]
        }
      }
    ],
    "actualCheckPoints": [
      {
        "_id": ObjectId("571009a21dd20ebf5a071bef"),
        "name": "Panaderia venecia",
        "description": "Panaderia de la castellana",
        "isActive": false,
        "isPublic": false,
        "geometry": {
          "type": "Point",
          "coordinates": [
            -74.114842,
            4.683897
          ]
        },
        "ratio": 0
      }
    ],
    "deviceData": [

    ],
		"latitude" : 4.655555,
		"longitude" : -74.066906,
        "speed" : 2,
        "address" : "ENTURNE EST IMPALA BARRANCA (BARRANCABERMEJA)",
        "heading" : 324,
        "updateTime" : "2016-05-27 06:06:13",
        "hasEvent" : false,
        "distance" : "140",
        "odometer" : "33516506",
        "totalDistance" : "171",
        "created_at" : "2016-05-27 06:06:15",
        "id_rawData" : "106650000",
        "isVisible" : true,	
		"tasks": [
    {
      "_id": ObjectId("572bf278c740cde2218b45cb"),
      "type": "Inicio",
      "code": "571d8a0fc740cd5136be5c53",
      "loadAmount": 0,
      "status": "PENDIENTE",
      "name": "Bodega 2",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "18:00",
      "duration": 0,
      "updated_at": "2016-05-06 01:25:12",
      "created_at": "2016-05-06 01:25:12",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 130000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 130000
          }
        }
      ],
      "location_id": "571d8a0fc740cd5136be5c53",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "08:00"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4597"),
      "type": "pickup",
      "code": "572bf25cc740cdb5218b457f",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 25",
      "location": {
        "name": "Diagonal 68 12, 110231 Bogot\u00e1, Colombia",
        "lat": 4.65446,
        "lng": -74.06226
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 146000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 146000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457f",
      "location_name": "Diagonal 68 12, 110231 Bogot\u00e1, Colombia",
      "arrival_time": "08:18",
      "finish_time": "09:18"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4595"),
      "type": "pickup",
      "code": "572bf25cc740cdb5218b457e",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 24",
      "location": {
        "name": "Avenida Calle 72 12, Bogot\u00e1, Colombia",
        "lat": 4.65818,
        "lng": -74.06005
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 160000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 160000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457e",
      "location_name": "Avenida Calle 72 12, Bogot\u00e1, Colombia",
      "arrival_time": "09:27",
      "finish_time": "10:27"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b45b1"),
      "type": "pickup",
      "code": "572bf260c740cdb5218b458c",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 38",
      "location": {
        "name": "Avenida Calle 72 21, 111211 Bogot\u00e1, Colombia",
        "lat": 4.66198,
        "lng": -74.0655
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 174000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 174000
          }
        }
      ],
      "location_id": "572bf260c740cdb5218b458c",
      "location_name": "Avenida Calle 72 21, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "10:31",
      "finish_time": "11:31"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b45b2"),
      "type": "dropoff",
      "code": "572bf260c740cdb5218b458c",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 38",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 188000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 188000
          }
        }
      ],
      "location_id": "572bf260c740cdb5218b458c",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "11:48",
      "finish_time": "12:48"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4596"),
      "type": "dropoff",
      "code": "572bf25cc740cdb5218b457e",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 24",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 205000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 205000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457e",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "12:48",
      "finish_time": "13:48"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4598"),
      "type": "dropoff",
      "code": "572bf25cc740cdb5218b457f",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 25",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 220000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 220000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457f",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "13:48",
      "finish_time": "14:48"
    },
    {
      "_id": ObjectId("572bf278c740cde2218b45cc"),
      "type": "Fin",
      "code": "571d8a2ac740cdeb4ebe5c53",
      "loadAmount": 0,
      "status": "PENDIENTE",
      "name": "Bodega 1",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "18:00",
      "duration": 0,
      "updated_at": "2016-05-06 01:25:12",
      "created_at": "2016-05-06 01:25:12",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 240000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 240000
          }
        }
      ],
      "location_id": "571d8a2ac740cdeb4ebe5c53",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "14:48"
    }
  ],
		"javaScriptShape": "new Array(new OpenLayers.Geometry.Point(-74.0609117,4.68041),new OpenLayers.Geometry.Point(-74.0606403,4.6820641),new OpenLayers.Geometry.Point(-74.0600932,4.6819782),new OpenLayers.Geometry.Point(-74.0599108,4.6829653),new OpenLayers.Geometry.Point(-74.0597713,4.6838772),new OpenLayers.Geometry.Point(-74.0581083,4.6835876),new OpenLayers.Geometry.Point(-74.0583873,4.6819246),new OpenLayers.Geometry.Point(-74.0583122,4.6809375),new OpenLayers.Geometry.Point(-74.058398,4.6803474),new OpenLayers.Geometry.Point(-74.0585804,4.6793497),new OpenLayers.Geometry.Point(-74.0585911,4.6791351),new OpenLayers.Geometry.Point(-74.058795,4.6781695),new OpenLayers.Geometry.Point(-74.0587842,4.6780729),new OpenLayers.Geometry.Point(-74.0588486,4.677676),new OpenLayers.Geometry.Point(-74.0589452,4.6774185),new OpenLayers.Geometry.Point(-74.0592778,4.6766996),new OpenLayers.Geometry.Point(-74.0593314,4.6764314),new OpenLayers.Geometry.Point(-74.0601146,4.6715927),new OpenLayers.Geometry.Point(-74.0601575,4.6711528),new OpenLayers.Geometry.Point(-74.0601361,4.6709919),new OpenLayers.Geometry.Point(-74.0603507,4.6697474),new OpenLayers.Geometry.Point(-74.0603614,4.6695971),new OpenLayers.Geometry.Point(-74.060297,4.6691895),new OpenLayers.Geometry.Point(-74.060415,4.6685565),new OpenLayers.Geometry.Point(-74.0606189,4.6677732),new OpenLayers.Geometry.Point(-74.0610051,4.6661639),new OpenLayers.Geometry.Point(-74.0610802,4.6659172),new OpenLayers.Geometry.Point(-74.0610588,4.6657348),new OpenLayers.Geometry.Point(-74.0610373,4.6656275),new OpenLayers.Geometry.Point(-74.0610373,4.6655309),new OpenLayers.Geometry.Point(-74.0611017,4.6646082),new OpenLayers.Geometry.Point(-74.0617239,4.6608961),new OpenLayers.Geometry.Point(-74.0618634,4.6602201),new OpenLayers.Geometry.Point(-74.0620029,4.6594048),new OpenLayers.Geometry.Point(-74.0621424,4.6584392),new OpenLayers.Geometry.Point(-74.0624857,4.6565402),new OpenLayers.Geometry.Point(-74.0631294,4.6566474),new OpenLayers.Geometry.Point(-74.0632153,4.6565723),new OpenLayers.Geometry.Point(-74.0633547,4.6563363),new OpenLayers.Geometry.Point(-74.0633976,4.6562076),new OpenLayers.Geometry.Point(-74.062711,4.6557784),new OpenLayers.Geometry.Point(-74.0625501,4.6556926),new OpenLayers.Geometry.Point(-74.0614235,4.654609),new OpenLayers.Geometry.Point(-74.0615952,4.6544588),new OpenLayers.Geometry.Point(-74.0617347,4.6543944),new OpenLayers.Geometry.Point(-74.0618956,4.6543622),new OpenLayers.Geometry.Point(-74.0620244,4.6543837),new OpenLayers.Geometry.Point(-74.0622614,4.6544556),new OpenLayers.Geometry.Point(-74.0626252,4.654566),new OpenLayers.Geometry.Point(-74.062711,4.6541476),new OpenLayers.Geometry.Point(-74.0618849,4.653815),new OpenLayers.Geometry.Point(-74.0619063,4.6541154),new OpenLayers.Geometry.Point(-74.0618956,4.6543622),new OpenLayers.Geometry.Point(-74.0617347,4.6543944),new OpenLayers.Geometry.Point(-74.0615952,4.6544588),new OpenLayers.Geometry.Point(-74.0614235,4.654609),new OpenLayers.Geometry.Point(-74.060297,4.6536005),new OpenLayers.Geometry.Point(-74.0599751,4.6533322),new OpenLayers.Geometry.Point(-74.0592241,4.6526349),new OpenLayers.Geometry.Point(-74.0588915,4.6531928),new OpenLayers.Geometry.Point(-74.058398,4.6538687),new OpenLayers.Geometry.Point(-74.0580654,4.6543944),new OpenLayers.Geometry.Point(-74.0579581,4.654609),new OpenLayers.Geometry.Point(-74.0575826,4.6551776),new OpenLayers.Geometry.Point(-74.057014,4.6559179),new OpenLayers.Geometry.Point(-74.0569282,4.6560359),new OpenLayers.Geometry.Point(-74.0596962,4.6579456),new OpenLayers.Geometry.Point(-74.0600494,4.6581811),new OpenLayers.Geometry.Point(-74.0604043,4.658407),new OpenLayers.Geometry.Point(-74.0613699,4.6591151),new OpenLayers.Geometry.Point(-74.0615308,4.6592546),new OpenLayers.Geometry.Point(-74.0617454,4.6593833),new OpenLayers.Geometry.Point(-74.0619707,4.659555),new OpenLayers.Geometry.Point(-74.0625286,4.6599197),new OpenLayers.Geometry.Point(-74.0631831,4.6604133),new OpenLayers.Geometry.Point(-74.06461,4.6613789),new OpenLayers.Geometry.Point(-74.0652752,4.661808),new OpenLayers.Geometry.Point(-74.0655005,4.6619795),new OpenLayers.Geometry.Point(-74.0657258,4.6621513),new OpenLayers.Geometry.Point(-74.0670776,4.6630847),new OpenLayers.Geometry.Point(-74.0665519,4.6640396),new OpenLayers.Geometry.Point(-74.0657473,4.665252),new OpenLayers.Geometry.Point(-74.065479,4.6657133),new OpenLayers.Geometry.Point(-74.0666807,4.6664751),new OpenLayers.Geometry.Point(-74.0660369,4.6674943),new OpenLayers.Geometry.Point(-74.0655863,4.6681488),new OpenLayers.Geometry.Point(-74.0645027,4.6698654),new OpenLayers.Geometry.Point(-74.0655756,4.6705306),new OpenLayers.Geometry.Point(-74.0652001,4.6709919),new OpenLayers.Geometry.Point(-74.0650821,4.671185),new OpenLayers.Geometry.Point(-74.0650499,4.6712708),new OpenLayers.Geometry.Point(-74.06564,4.6720111),new OpenLayers.Geometry.Point(-74.0661657,4.6726012),new OpenLayers.Geometry.Point(-74.0664876,4.6729231),new OpenLayers.Geometry.Point(-74.0673351,4.6738458),new OpenLayers.Geometry.Point(-74.0673566,4.6739316),new OpenLayers.Geometry.Point(-74.0675068,4.6742105),new OpenLayers.Geometry.Point(-74.0676785,4.6744788),new OpenLayers.Geometry.Point(-74.0679252,4.6747684),new OpenLayers.Geometry.Point(-74.0679896,4.6749187),new OpenLayers.Geometry.Point(-74.0680003,4.674983),new OpenLayers.Geometry.Point(-74.0679681,4.6751118),new OpenLayers.Geometry.Point(-74.0679145,4.6752083),new OpenLayers.Geometry.Point(-74.0675604,4.6755946),new OpenLayers.Geometry.Point(-74.0672815,4.6759379),new OpenLayers.Geometry.Point(-74.0657473,4.6785343),new OpenLayers.Geometry.Point(-74.0656078,4.6788132),new OpenLayers.Geometry.Point(-74.0652001,4.6795213),new OpenLayers.Geometry.Point(-74.0640843,4.6812272),new OpenLayers.Geometry.Point(-74.0635049,4.6811414),new OpenLayers.Geometry.Point(-74.0624642,4.6809483),new OpenLayers.Geometry.Point(-74.0608656,4.68068),new OpenLayers.Geometry.Point(-74.0609117,4.68041),new OpenLayers.Geometry.Point(-74.0609117,4.68041))"
});

db.actual.insert({
    "_class": "co.com.datatraffic.fieldvision.tracking.Actual",
    "resource":		
		{"_id":ObjectId("56f55709efe9e8d575768a64"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf5"))],"login": "SMG847","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db84")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db94"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db54")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
    "actualGeofences": [
      {
        "_id": ObjectId("571009a21dd20ebf5a071bed"),
        "name": "Sector I",
        "description": "Barrios: la granja, soledad norte, la almeria, tabora, santa maria del lago",
        "isActive": true,
        "isPublic": true,
        "geometry": {
          "type": "Polygon",
          "coordinates": [
            [
              [
                -74.100916,
                4.703048
              ],
              [
                -74.089029,
                4.695392
              ],
              [
                -74.094951,
                4.68718
              ],
              [
                -74.10568,
                4.69582
              ],
              [
                -74.100916,
                4.703048
              ]
            ]
          ]
        }
      }
    ],
    "actualCheckPoints": [
      {
        "_id": ObjectId("571009a21dd20ebf5a071bef"),
        "name": "Panaderia venecia",
        "description": "Panaderia de la castellana",
        "isActive": false,
        "isPublic": false,
        "geometry": {
          "type": "Point",
          "coordinates": [
            -74.114842,
            4.683897
          ]
        },
        "ratio": 0
      }
    ],
    "deviceData": [

    ],
		"latitude" : 4.655555,
		"longitude" : -74.066906,
        "speed" : 2,
        "address" : "ENTURNE EST IMPALA BARRANCA (BARRANCABERMEJA)",
        "heading" : 324,
        "updateTime" : "2016-05-27 06:06:13",
        "hasEvent" : false,
        "distance" : "140",
        "odometer" : "33516506",
        "totalDistance" : "171",
        "created_at" : "2016-05-27 06:06:15",
        "id_rawData" : "106650000",
        "isVisible" : true,	
		"tasks": [
    {
      "_id": ObjectId("572bf278c740cde2218b45cb"),
      "type": "Inicio",
      "code": "571d8a0fc740cd5136be5c53",
      "loadAmount": 0,
      "status": "PENDIENTE",
      "name": "Bodega 2",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "18:00",
      "duration": 0,
      "updated_at": "2016-05-06 01:25:12",
      "created_at": "2016-05-06 01:25:12",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 130000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 130000
          }
        }
      ],
      "location_id": "571d8a0fc740cd5136be5c53",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "08:00"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4597"),
      "type": "pickup",
      "code": "572bf25cc740cdb5218b457f",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 25",
      "location": {
        "name": "Diagonal 68 12, 110231 Bogot\u00e1, Colombia",
        "lat": 4.65446,
        "lng": -74.06226
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 146000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 146000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457f",
      "location_name": "Diagonal 68 12, 110231 Bogot\u00e1, Colombia",
      "arrival_time": "08:18",
      "finish_time": "09:18"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4595"),
      "type": "pickup",
      "code": "572bf25cc740cdb5218b457e",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 24",
      "location": {
        "name": "Avenida Calle 72 12, Bogot\u00e1, Colombia",
        "lat": 4.65818,
        "lng": -74.06005
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 160000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 160000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457e",
      "location_name": "Avenida Calle 72 12, Bogot\u00e1, Colombia",
      "arrival_time": "09:27",
      "finish_time": "10:27"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b45b1"),
      "type": "pickup",
      "code": "572bf260c740cdb5218b458c",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 38",
      "location": {
        "name": "Avenida Calle 72 21, 111211 Bogot\u00e1, Colombia",
        "lat": 4.66198,
        "lng": -74.0655
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 174000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 174000
          }
        }
      ],
      "location_id": "572bf260c740cdb5218b458c",
      "location_name": "Avenida Calle 72 21, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "10:31",
      "finish_time": "11:31"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b45b2"),
      "type": "dropoff",
      "code": "572bf260c740cdb5218b458c",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 38",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 188000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 188000
          }
        }
      ],
      "location_id": "572bf260c740cdb5218b458c",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "11:48",
      "finish_time": "12:48"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4596"),
      "type": "dropoff",
      "code": "572bf25cc740cdb5218b457e",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 24",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 205000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 205000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457e",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "12:48",
      "finish_time": "13:48"
    },
    {
      "_id": ObjectId("572bf270c740cde2218b4598"),
      "type": "dropoff",
      "code": "572bf25cc740cdb5218b457f",
      "loadAmount": "15",
      "status": "PENDIENTE",
      "name": "TAREA 25",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "17:00",
      "duration": "60",
      "updated_at": "2016-05-06 01:25:04",
      "created_at": "2016-05-06 01:25:04",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 220000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 220000
          }
        }
      ],
      "location_id": "572bf25cc740cdb5218b457f",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "13:48",
      "finish_time": "14:48"
    },
    {
      "_id": ObjectId("572bf278c740cde2218b45cc"),
      "type": "Fin",
      "code": "571d8a2ac740cdeb4ebe5c53",
      "loadAmount": 0,
      "status": "PENDIENTE",
      "name": "Bodega 1",
      "location": {
        "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "lat": 4.68042,
        "lng": -74.06097
      },
      "start": "08:00",
      "end": "18:00",
      "duration": 0,
      "updated_at": "2016-05-06 01:25:12",
      "created_at": "2016-05-06 01:25:12",
      "resource": [
        {
          "id_user_create": {
            "$id": "570f8f9df270122e4aa9d4e3"
          },
          "numberPlate": "BKO676",
          "type": "carro",
          "capacidad": 150,
          "startHour": "08:00",
          "endHour": "18:00",
          "_id": {
            "$id": "571d89e5c740cde94ebe5c52"
          },
          "updated_at": {
            "sec": 1462497912,
            "usec": 240000
          },
          "created_at": {
            "sec": 1462497912,
            "usec": 240000
          }
        }
      ],
      "location_id": "571d8a2ac740cdeb4ebe5c53",
      "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
      "arrival_time": "14:48"
    }
  ],
		"javaScriptShape": "new Array(new OpenLayers.Geometry.Point(-74.0609117,4.68041),new OpenLayers.Geometry.Point(-74.0606403,4.6820641),new OpenLayers.Geometry.Point(-74.0600932,4.6819782),new OpenLayers.Geometry.Point(-74.0599108,4.6829653),new OpenLayers.Geometry.Point(-74.0597713,4.6838772),new OpenLayers.Geometry.Point(-74.0581083,4.6835876),new OpenLayers.Geometry.Point(-74.0583873,4.6819246),new OpenLayers.Geometry.Point(-74.0583122,4.6809375),new OpenLayers.Geometry.Point(-74.058398,4.6803474),new OpenLayers.Geometry.Point(-74.0585804,4.6793497),new OpenLayers.Geometry.Point(-74.0585911,4.6791351),new OpenLayers.Geometry.Point(-74.058795,4.6781695),new OpenLayers.Geometry.Point(-74.0587842,4.6780729),new OpenLayers.Geometry.Point(-74.0588486,4.677676),new OpenLayers.Geometry.Point(-74.0589452,4.6774185),new OpenLayers.Geometry.Point(-74.0592778,4.6766996),new OpenLayers.Geometry.Point(-74.0593314,4.6764314),new OpenLayers.Geometry.Point(-74.0601146,4.6715927),new OpenLayers.Geometry.Point(-74.0601575,4.6711528),new OpenLayers.Geometry.Point(-74.0601361,4.6709919),new OpenLayers.Geometry.Point(-74.0603507,4.6697474),new OpenLayers.Geometry.Point(-74.0603614,4.6695971),new OpenLayers.Geometry.Point(-74.060297,4.6691895),new OpenLayers.Geometry.Point(-74.060415,4.6685565),new OpenLayers.Geometry.Point(-74.0606189,4.6677732),new OpenLayers.Geometry.Point(-74.0610051,4.6661639),new OpenLayers.Geometry.Point(-74.0610802,4.6659172),new OpenLayers.Geometry.Point(-74.0610588,4.6657348),new OpenLayers.Geometry.Point(-74.0610373,4.6656275),new OpenLayers.Geometry.Point(-74.0610373,4.6655309),new OpenLayers.Geometry.Point(-74.0611017,4.6646082),new OpenLayers.Geometry.Point(-74.0617239,4.6608961),new OpenLayers.Geometry.Point(-74.0618634,4.6602201),new OpenLayers.Geometry.Point(-74.0620029,4.6594048),new OpenLayers.Geometry.Point(-74.0621424,4.6584392),new OpenLayers.Geometry.Point(-74.0624857,4.6565402),new OpenLayers.Geometry.Point(-74.0631294,4.6566474),new OpenLayers.Geometry.Point(-74.0632153,4.6565723),new OpenLayers.Geometry.Point(-74.0633547,4.6563363),new OpenLayers.Geometry.Point(-74.0633976,4.6562076),new OpenLayers.Geometry.Point(-74.062711,4.6557784),new OpenLayers.Geometry.Point(-74.0625501,4.6556926),new OpenLayers.Geometry.Point(-74.0614235,4.654609),new OpenLayers.Geometry.Point(-74.0615952,4.6544588),new OpenLayers.Geometry.Point(-74.0617347,4.6543944),new OpenLayers.Geometry.Point(-74.0618956,4.6543622),new OpenLayers.Geometry.Point(-74.0620244,4.6543837),new OpenLayers.Geometry.Point(-74.0622614,4.6544556),new OpenLayers.Geometry.Point(-74.0626252,4.654566),new OpenLayers.Geometry.Point(-74.062711,4.6541476),new OpenLayers.Geometry.Point(-74.0618849,4.653815),new OpenLayers.Geometry.Point(-74.0619063,4.6541154),new OpenLayers.Geometry.Point(-74.0618956,4.6543622),new OpenLayers.Geometry.Point(-74.0617347,4.6543944),new OpenLayers.Geometry.Point(-74.0615952,4.6544588),new OpenLayers.Geometry.Point(-74.0614235,4.654609),new OpenLayers.Geometry.Point(-74.060297,4.6536005),new OpenLayers.Geometry.Point(-74.0599751,4.6533322),new OpenLayers.Geometry.Point(-74.0592241,4.6526349),new OpenLayers.Geometry.Point(-74.0588915,4.6531928),new OpenLayers.Geometry.Point(-74.058398,4.6538687),new OpenLayers.Geometry.Point(-74.0580654,4.6543944),new OpenLayers.Geometry.Point(-74.0579581,4.654609),new OpenLayers.Geometry.Point(-74.0575826,4.6551776),new OpenLayers.Geometry.Point(-74.057014,4.6559179),new OpenLayers.Geometry.Point(-74.0569282,4.6560359),new OpenLayers.Geometry.Point(-74.0596962,4.6579456),new OpenLayers.Geometry.Point(-74.0600494,4.6581811),new OpenLayers.Geometry.Point(-74.0604043,4.658407),new OpenLayers.Geometry.Point(-74.0613699,4.6591151),new OpenLayers.Geometry.Point(-74.0615308,4.6592546),new OpenLayers.Geometry.Point(-74.0617454,4.6593833),new OpenLayers.Geometry.Point(-74.0619707,4.659555),new OpenLayers.Geometry.Point(-74.0625286,4.6599197),new OpenLayers.Geometry.Point(-74.0631831,4.6604133),new OpenLayers.Geometry.Point(-74.06461,4.6613789),new OpenLayers.Geometry.Point(-74.0652752,4.661808),new OpenLayers.Geometry.Point(-74.0655005,4.6619795),new OpenLayers.Geometry.Point(-74.0657258,4.6621513),new OpenLayers.Geometry.Point(-74.0670776,4.6630847),new OpenLayers.Geometry.Point(-74.0665519,4.6640396),new OpenLayers.Geometry.Point(-74.0657473,4.665252),new OpenLayers.Geometry.Point(-74.065479,4.6657133),new OpenLayers.Geometry.Point(-74.0666807,4.6664751),new OpenLayers.Geometry.Point(-74.0660369,4.6674943),new OpenLayers.Geometry.Point(-74.0655863,4.6681488),new OpenLayers.Geometry.Point(-74.0645027,4.6698654),new OpenLayers.Geometry.Point(-74.0655756,4.6705306),new OpenLayers.Geometry.Point(-74.0652001,4.6709919),new OpenLayers.Geometry.Point(-74.0650821,4.671185),new OpenLayers.Geometry.Point(-74.0650499,4.6712708),new OpenLayers.Geometry.Point(-74.06564,4.6720111),new OpenLayers.Geometry.Point(-74.0661657,4.6726012),new OpenLayers.Geometry.Point(-74.0664876,4.6729231),new OpenLayers.Geometry.Point(-74.0673351,4.6738458),new OpenLayers.Geometry.Point(-74.0673566,4.6739316),new OpenLayers.Geometry.Point(-74.0675068,4.6742105),new OpenLayers.Geometry.Point(-74.0676785,4.6744788),new OpenLayers.Geometry.Point(-74.0679252,4.6747684),new OpenLayers.Geometry.Point(-74.0679896,4.6749187),new OpenLayers.Geometry.Point(-74.0680003,4.674983),new OpenLayers.Geometry.Point(-74.0679681,4.6751118),new OpenLayers.Geometry.Point(-74.0679145,4.6752083),new OpenLayers.Geometry.Point(-74.0675604,4.6755946),new OpenLayers.Geometry.Point(-74.0672815,4.6759379),new OpenLayers.Geometry.Point(-74.0657473,4.6785343),new OpenLayers.Geometry.Point(-74.0656078,4.6788132),new OpenLayers.Geometry.Point(-74.0652001,4.6795213),new OpenLayers.Geometry.Point(-74.0640843,4.6812272),new OpenLayers.Geometry.Point(-74.0635049,4.6811414),new OpenLayers.Geometry.Point(-74.0624642,4.6809483),new OpenLayers.Geometry.Point(-74.0608656,4.68068),new OpenLayers.Geometry.Point(-74.0609117,4.68041),new OpenLayers.Geometry.Point(-74.0609117,4.68041))"
});

print("Cargadas todos los Actual");

print("Insertando las geocercas");

db.geofences.insert({
    "_id": ObjectId("571009a21dd20ebf5a071bed"),
    "id_company":DBRef("companies",ObjectId("56f55709efe9e8d575768a54")),
    "name": "Sector I",
    "description": "Barrios: la granja, soledad norte, la almeria, tabora, santa maria del lago",
    "isActive": true,
    "isPublic": true,
    "geometry": {
        "type": "Polygon",
        "coordinates": [
            [
                [
                    -74.100916,
                    4.703048
                ],
                [
                    -74.089029,
                    4.695392
                ],
                [
                    -74.094951,
                    4.68718
                ],
                [
                    -74.10568,
                    4.69582
                ],
                [
                    -74.100916,
                    4.703048
                ]
            ]
        ]
    }
});

print("Cargadas todos las geocercas");

print("Insertando las checkpoint");

db.checkPoints.insert(
    {
        "_id": ObjectId("571009a21dd20ebf5a071bef"),
        "id_company":DBRef("companies",ObjectId("56f55709efe9e8d575768a54")),
        "name": "Panaderia venecia",
        "description": "Panaderia de la castellana",
        "isActive": false,
        "isPublic": false,
        "geometry": {
            "type": "Point",
            "coordinates": [
                -74.114842,
                4.683897
            ]
        },
        "ratio": 0
    });

print("Cargadas todos los checkpoint");

db.tasks.insert([
    {
        "_id": ObjectId("572bf279c740cde2218b45d1"),
        "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
        "type": "Inicio",
        "code": "571d8a0fc740cd5136be5c53",
        "loadAmount": 0,
        "status": "PENDIENTE",
        "name": "Bodega 2",
        "location": {
            "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
            "lat": 4.68042,
            "lng": -74.06097
        },
        "start": "08:00",
        "end": "18:00",
        "duration": 0,
        "updated_at": "2016-05-06 01:25:13",
        "created_at": "2016-05-06 01:25:13",
        "resource": [
            {
                "id_user_create": {
                    "$id": "570f8f9df270122e4aa9d4e3"
                },
                "numberPlate": "NBT868",
                "type": "carro",
                "capacidad": 300,
                "startHour": "06:00",
                "endHour": "19:00",
                "_id": {
                    "$id": "571e243ac740cd5336be5c52"
                },
                "updated_at": {
                    "sec": 1462497913,
                    "usec": 218000
                },
                "created_at": {
                    "sec": 1462497913,
                    "usec": 218000
                }
            }
        ],
        "location_id": "571d8a0fc740cd5136be5c53",
        "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "arrival_time": "07:37",
        "events": [
            {
                "eventType": "pendingEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveringEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveredEvent",
                "message": "La tarea fue entregada con exito",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "approvedEvent",
                "message": "La tarea fue aprobada",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            }
        ]
    },
    {
        "_id": ObjectId("572bf270c740cde2218b4593"),
        "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
        "type": "pickup",
        "code": "572bf25cc740cdb5218b457d",
        "loadAmount": "15",
        "status": "PENDIENTE",
        "name": "TAREA 23",
        "location": {
            "name": "Calle 69 68C, 111061 Bogot\u00e1, Colombia",
            "lat": 4.67704,
            "lng": -74.08914
        },
        "start": "08:00",
        "end": "17:00",
        "duration": "60",
        "updated_at": "2016-05-06 01:25:04",
        "created_at": "2016-05-06 01:25:04",
        "resource": [
            {
                "id_user_create": {
                    "$id": "570f8f9df270122e4aa9d4e3"
                },
                "numberPlate": "NBT868",
                "type": "carro",
                "capacidad": 300,
                "startHour": "06:00",
                "endHour": "19:00",
                "_id": {
                    "$id": "571e243ac740cd5336be5c52"
                },
                "updated_at": {
                    "sec": 1462497913,
                    "usec": 233000
                },
                "created_at": {
                    "sec": 1462497913,
                    "usec": 233000
                }
            }
        ],
        "location_id": "572bf25cc740cdb5218b457d",
        "location_name": "Calle 69 68C, 111061 Bogot\u00e1, Colombia",
        "arrival_time": "08:00",
        "finish_time": "09:00",
        "events": [
            {
                "eventType": "pendingEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveringEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveredEvent",
                "message": "La tarea fue entregada con exito",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "approvedEvent",
                "message": "La tarea fue aprobada",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            }
        ]
    },
    {
        "_id": ObjectId("572bf270c740cde2218b45a7"),
        "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
        "type": "pickup",
        "code": "572bf25fc740cdb5218b4587",
        "loadAmount": "15",
        "status": "PENDIENTE",
        "name": "TAREA 33",
        "location": {
            "name": "Avenida Calle 53 66A, 111321 Bogot\u00e1, Colombia",
            "lat": 4.65554,
            "lng": -74.0981
        },
        "start": "08:00",
        "end": "17:00",
        "duration": "60",
        "updated_at": "2016-05-06 01:25:04",
        "created_at": "2016-05-06 01:25:04",
        "resource": [
            {
                "id_user_create": {
                    "$id": "570f8f9df270122e4aa9d4e3"
                },
                "numberPlate": "NBT868",
                "type": "carro",
                "capacidad": 300,
                "startHour": "06:00",
                "endHour": "19:00",
                "_id": {
                    "$id": "571e243ac740cd5336be5c52"
                },
                "updated_at": {
                    "sec": 1462497913,
                    "usec": 247000
                },
                "created_at": {
                    "sec": 1462497913,
                    "usec": 247000
                }
            }
        ],
        "location_id": "572bf25fc740cdb5218b4587",
        "location_name": "Avenida Calle 53 66A, 111321 Bogot\u00e1, Colombia",
        "arrival_time": "09:14",
        "finish_time": "10:14",
        "events": [
            {
                "eventType": "pendingEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveringEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveredEvent",
                "message": "La tarea fue entregada con exito",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "approvedEvent",
                "message": "La tarea fue aprobada",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            }
        ]
    },
    {
        "_id": ObjectId("572bf270c740cde2218b45a5"),
        "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
        "type": "pickup",
        "code": "572bf25fc740cdb5218b4586",
        "loadAmount": "15",
        "status": "PENDIENTE",
        "name": "TAREA 32",
        "location": {
            "name": "Avenida Calle 53 66A, 111321 Bogot\u00e1, Colombia",
            "lat": 4.65554,
            "lng": -74.0981
        },
        "start": "08:00",
        "end": "17:00",
        "duration": "60",
        "updated_at": "2016-05-06 01:25:04",
        "created_at": "2016-05-06 01:25:04",
        "resource": [
            {
                "id_user_create": {
                    "$id": "570f8f9df270122e4aa9d4e3"
                },
                "numberPlate": "NBT868",
                "type": "carro",
                "capacidad": 300,
                "startHour": "06:00",
                "endHour": "19:00",
                "_id": {
                    "$id": "571e243ac740cd5336be5c52"
                },
                "updated_at": {
                    "sec": 1462497913,
                    "usec": 261000
                },
                "created_at": {
                    "sec": 1462497913,
                    "usec": 261000
                }
            }
        ],
        "location_id": "572bf25fc740cdb5218b4586",
        "location_name": "Avenida Calle 53 66A, 111321 Bogot\u00e1, Colombia",
        "arrival_time": "10:14",
        "finish_time": "11:14",
        "events": [
            {
                "eventType": "pendingEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveringEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveredEvent",
                "message": "La tarea fue entregada con exito",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "approvedEvent",
                "message": "La tarea fue aprobada",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            }
        ]
    },
    {
        "_id": ObjectId("572bf270c740cde2218b4591"),
        "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
        "type": "pickup",
        "code": "572bf25bc740cdb5218b457c",
        "loadAmount": "15",
        "status": "PENDIENTE",
        "name": "TAREA 22",
        "location": {
            "name": "Calle 61 56, 111321 Bogot\u00e1, Colombia",
            "lat": 4.65372,
            "lng": -74.08626
        },
        "start": "08:00",
        "end": "17:00",
        "duration": "60",
        "updated_at": "2016-05-06 01:25:04",
        "created_at": "2016-05-06 01:25:04",
        "resource": [
            {
                "id_user_create": {
                    "$id": "570f8f9df270122e4aa9d4e3"
                },
                "numberPlate": "NBT868",
                "type": "carro",
                "capacidad": 300,
                "startHour": "06:00",
                "endHour": "19:00",
                "_id": {
                    "$id": "571e243ac740cd5336be5c52"
                },
                "updated_at": {
                    "sec": 1462497913,
                    "usec": 286000
                },
                "created_at": {
                    "sec": 1462497913,
                    "usec": 286000
                }
            }
        ],
        "location_id": "572bf25bc740cdb5218b457c",
        "location_name": "Calle 61 56, 111321 Bogot\u00e1, Colombia",
        "arrival_time": "11:25",
        "finish_time": "12:25",
        "events": [
            {
                "eventType": "pendingEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveringEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveredEvent",
                "message": "La tarea fue entregada con exito",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "approvedEvent",
                "message": "La tarea fue aprobada",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            }
        ]
    },
    {
        "_id": ObjectId("572bf270c740cde2218b4592"),
        "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
        "type": "dropoff",
        "code": "572bf25bc740cdb5218b457c",
        "loadAmount": "15",
        "status": "PENDIENTE",
        "name": "TAREA 22",
        "location": {
            "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
            "lat": 4.68042,
            "lng": -74.06097
        },
        "start": "08:00",
        "end": "17:00",
        "duration": "60",
        "updated_at": "2016-05-06 01:25:04",
        "created_at": "2016-05-06 01:25:04",
        "resource": [
            {
                "id_user_create": {
                    "$id": "570f8f9df270122e4aa9d4e3"
                },
                "numberPlate": "NBT868",
                "type": "carro",
                "capacidad": 300,
                "startHour": "06:00",
                "endHour": "19:00",
                "_id": {
                    "$id": "571e243ac740cd5336be5c52"
                },
                "updated_at": {
                    "sec": 1462497913,
                    "usec": 300000
                },
                "created_at": {
                    "sec": 1462497913,
                    "usec": 300000
                }
            }
        ],
        "location_id": "572bf25bc740cdb5218b457c",
        "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "arrival_time": "12:53",
        "finish_time": "13:53",
        "events": [
            {
                "eventType": "pendingEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveringEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveredEvent",
                "message": "La tarea fue entregada con exito",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "approvedEvent",
                "message": "La tarea fue aprobada",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            }
        ]
    },
    {
        "_id": ObjectId("572bf270c740cde2218b4594"),
        "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
        "type": "dropoff",
        "code": "572bf25cc740cdb5218b457d",
        "loadAmount": "15",
        "status": "PENDIENTE",
        "name": "TAREA 23",
        "location": {
            "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
            "lat": 4.68042,
            "lng": -74.06097
        },
        "start": "08:00",
        "end": "17:00",
        "duration": "60",
        "updated_at": "2016-05-06 01:25:04",
        "created_at": "2016-05-06 01:25:04",
        "resource": [
            {
                "id_user_create": {
                    "$id": "570f8f9df270122e4aa9d4e3"
                },
                "numberPlate": "NBT868",
                "type": "carro",
                "capacidad": 300,
                "startHour": "06:00",
                "endHour": "19:00",
                "_id": {
                    "$id": "571e243ac740cd5336be5c52"
                },
                "updated_at": {
                    "sec": 1462497913,
                    "usec": 315000
                },
                "created_at": {
                    "sec": 1462497913,
                    "usec": 315000
                }
            }
        ],
        "location_id": "572bf25cc740cdb5218b457d",
        "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "arrival_time": "13:53",
        "finish_time": "14:53",
        "events": [
            {
                "eventType": "pendingEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveringEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveredEvent",
                "message": "La tarea fue entregada con exito",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "approvedEvent",
                "message": "La tarea fue aprobada",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            }
        ]
    },
    {
        "_id": ObjectId("572bf270c740cde2218b45a8"),
        "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
        "type": "dropoff",
        "code": "572bf25fc740cdb5218b4587",
        "loadAmount": "15",
        "status": "PENDIENTE",
        "name": "TAREA 33",
        "location": {
            "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
            "lat": 4.68042,
            "lng": -74.06097
        },
        "start": "08:00",
        "end": "17:00",
        "duration": "60",
        "updated_at": "2016-05-06 01:25:04",
        "created_at": "2016-05-06 01:25:04",
        "resource": [
            {
                "id_user_create": {
                    "$id": "570f8f9df270122e4aa9d4e3"
                },
                "numberPlate": "NBT868",
                "type": "carro",
                "capacidad": 300,
                "startHour": "06:00",
                "endHour": "19:00",
                "_id": {
                    "$id": "571e243ac740cd5336be5c52"
                },
                "updated_at": {
                    "sec": 1462497913,
                    "usec": 329000
                },
                "created_at": {
                    "sec": 1462497913,
                    "usec": 329000
                }
            }
        ],
        "location_id": "572bf25fc740cdb5218b4587",
        "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "arrival_time": "14:53",
        "finish_time": "15:53",
        "events": [
            {
                "eventType": "pendingEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveringEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveredEvent",
                "message": "La tarea fue entregada con exito",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "approvedEvent",
                "message": "La tarea fue aprobada",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            }
        ]
    },
    {
        "_id": ObjectId("572bf270c740cde2218b45a6"),
        "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
        "type": "dropoff",
        "code": "572bf25fc740cdb5218b4586",
        "loadAmount": "15",
        "status": "PENDIENTE",
        "name": "TAREA 32",
        "location": {
            "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
            "lat": 4.68042,
            "lng": -74.06097
        },
        "start": "08:00",
        "end": "17:00",
        "duration": "60",
        "updated_at": "2016-05-06 01:25:04",
        "created_at": "2016-05-06 01:25:04",
        "resource": [
            {
                "id_user_create": {
                    "$id": "570f8f9df270122e4aa9d4e3"
                },
                "numberPlate": "NBT868",
                "type": "carro",
                "capacidad": 300,
                "startHour": "06:00",
                "endHour": "19:00",
                "_id": {
                    "$id": "571e243ac740cd5336be5c52"
                },
                "updated_at": {
                    "sec": 1462497913,
                    "usec": 344000
                },
                "created_at": {
                    "sec": 1462497913,
                    "usec": 344000
                }
            }
        ],
        "location_id": "572bf25fc740cdb5218b4586",
        "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "arrival_time": "15:53",
        "finish_time": "16:53",
        "events": [
            {
                "eventType": "pendingEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveringEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveredEvent",
                "message": "La tarea fue entregada con exito",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "approvedEvent",
                "message": "La tarea fue aprobada",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            }
        ]
    },
    {
        "_id": ObjectId("572bf279c740cde2218b45d2"),
        "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
        "type": "Fin",
        "code": "571d8a2ac740cdeb4ebe5c53",
        "loadAmount": 0,
        "status": "PENDIENTE",
        "name": "Bodega 1",
        "location": {
            "name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
            "lat": 4.68042,
            "lng": -74.06097
        },
        "start": "08:00",
        "end": "18:00",
        "duration": 0,
        "updated_at": "2016-05-06 01:25:13",
        "created_at": "2016-05-06 01:25:13",
        "resource": [
            {
                "id_user_create": {
                    "$id": "570f8f9df270122e4aa9d4e3"
                },
                "numberPlate": "NBT868",
                "type": "carro",
                "capacidad": 300,
                "startHour": "06:00",
                "endHour": "19:00",
                "_id": {
                    "$id": "571e243ac740cd5336be5c52"
                },
                "updated_at": {
                    "sec": 1462497913,
                    "usec": 365000
                },
                "created_at": {
                    "sec": 1462497913,
                    "usec": 365000
                }
            }
        ],
        "location_id": "571d8a2ac740cdeb4ebe5c53",
        "location_name": "Carrera 47A 91-91, 111211 Bogot\u00e1, Colombia",
        "arrival_time": "16:53",
        "events": [
            {
                "eventType": "pendingEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveringEvent",
                "message": ISODate("2016-05-16T13:45:19.687Z"),
                "updateTime": "2016-05-16 09:00:00",
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "deliveredEvent",
                "message": "La tarea fue entregada con exito",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            },
            {
                "eventType": "approvedEvent",
                "message": "La tarea fue aprobada",
                "updateTime": ISODate("2016-05-16T13:45:19.687Z"),
                "location": {
                    "locationName": "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                    "lat": 4.68042,
                    "lng": -74.06097
                }
            }
        ]
    }
]);

print("Insertando los mensajes");

db.messages.insert([
{
        "sender" : "573a1ea02371c4918f836948",
        "receiver" : "5710f3321dd265f3db170140",
        "location" : {
                "locationName" : "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                "lat" : 4.68042,
                "lng" : -74.06097
        },
        "message" : "Buenos dias",
        "created_at" : ISODate("2016-05-22T07:23:26.847Z"),
        "delivered_at" : ISODate("2016-05-22T07:23:26.847Z"),
        "read_at" : ISODate("2016-05-22T07:23:26.847Z")
},
{
        "sender" : "5710f3321dd265f3db170140",
        "receiver" : "573a1ea02371c4918f836948",
        "location" : {
                "locationName" : "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                "lat" : 4.68042,
                "lng" : -74.06097
        },
        "message" : "Hola",
        "created_at" : ISODate("2016-05-22T07:25:26.847Z"),
        "delivered_at" : ISODate("2016-05-22T07:25:26.847Z"),
        "read_at" : ISODate("2016-05-22T07:25:26.847Z")
},
{
        "sender" : "573a1ea02371c4918f836948",
        "receiver" : "5710f3321dd265f3db170140",
        "location" : {
                "locationName" : "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                "lat" : 4.68042,
                "lng" : -74.06097
        },
        "message" : "Como va el viaje?",
        "created_at" : ISODate("2016-05-22T07:27:26.847Z"),
        "delivered_at" : ISODate("2016-05-22T07:27:26.847Z"),
        "read_at" : ISODate("2016-05-22T07:27:26.847Z")
},
{
        "sender" : "5710f3321dd265f3db170140",
        "receiver" : "573a1ea02371c4918f836948",
        "location" : {
                "locationName" : "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                "lat" : 4.68042,
                "lng" : -74.06097
        },
        "message" : "La verdad mal. Me pinche. No se que hacer.",
        "created_at" : ISODate("2016-05-22T07:29:26.847Z"),
        "delivered_at" : ISODate("2016-05-22T07:29:26.847Z"),
        "read_at" : ISODate("2016-05-22T07:29:26.847Z")
},
{
        "sender" : "573a1ea02371c4918f836948",
        "receiver" : "5710f3321dd265f3db170140",
        "location" : {
                "locationName" : "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                "lat" : 4.68042,
                "lng" : -74.06097
        },
        "message" : "Ya vamos a enviar la ayuda",
        "created_at" : ISODate("2016-05-22T07:31:26.847Z"),
        "delivered_at" : ISODate("2016-05-22T07:31:26.847Z"),
},
{
        "sender" : "573a1ea02371c4918f836948",
        "receiver" : "5710f3321dd265f3db170140",
        "location" : {
                "locationName" : "Carrera 47A 91-91, 111211 Bogotá, Colombia",
                "lat" : 4.68042,
                "lng" : -74.06097
        },
        "message" : "Me copia?",
        "created_at" : ISODate("2016-05-22T07:32:26.847Z"),
}
]);

print("Cargadas todos los mensajes");

print("Insertando los eventos");

db.events.insert([
{
		"resource" :{"_id":ObjectId("56f55709efe9e8d575768a60"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf1")),DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf5"))],"login": "SMG845","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db80")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db90"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db50")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
        "task" : {
                "_id" : "572bf270c740cde2218b4593",
                "name": "TAREA 23"
        },
        "eventCategory" : "Planning",
        "eventType" : "checkin",
        "message" : "Se hizo checkin",
        "updateTime" : ISODate("2016-05-22T08:02:26.847Z")
},
{
        "resource" : {"_id":ObjectId("56f55709efe9e8d575768a61"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf1")),DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf5"))],"login": "SMG872","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db81")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db91"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db51")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
        "task" : {
                "_id" : "572bf270c740cde2218b4593",
                "name": "TAREA 23"
        },
        "eventCategory" : "Planning",
        "eventType" : "checkout",
        "message" : "Se hizo checkoout",
        "updateTime" : ISODate("2016-05-22T08:58:26.847Z")
},
{
        "resource" : {"_id":ObjectId("56f55709efe9e8d575768a62"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf1"))],"login": "SMG846","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db82")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db92"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db52")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
        "task" : {
                "_id" : "572bf270c740cde2218b45a7",
                "name": "TAREA 33",
        },
        "eventCategory" : "Planning",
        "eventType" : "checkin",
        "message" : "Se hizo checkin",
        "updateTime" : ISODate("2016-05-22T09:16:26.847Z")
},
{
        "resource" : {"_id":ObjectId("56f55709efe9e8d575768a63"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf1"))],"login": "UYX480","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db83")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db93"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db53")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
        "task" : {
                "_id" : "572bf270c740cde2218b45a7",
                "name": "TAREA 33",
        },
        "eventCategory" : "Planning",
        "eventType" : "checkout",
        "message" : "Se hizo checkoout",
        "updateTime" : ISODate("2016-05-22T10:16:26.847Z")
},
{
        "resource" : {"_id":ObjectId("56f55709efe9e8d575768a64"), "id_company":DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),"id_resourceDefinition":DBRef("resourceDefinitions",ObjectId("570f9f46da1c882fea57db57")),"resourceGroups":[DBRef("resourceGroups",ObjectId("571009a21dd20ebf5a071bf5"))],"login": "SMG847","email" : "soporte@itelca.com.co", "password" : "$2y$10$qRPJNSbMXAACfXIC0/zgj.HnhyW4CO2.9W41Z9lI2Ilof6.x34zIK","customStatus":"SIN CARGA","deviceInstances":[DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db84")),DBRef("deviceInstances",ObjectId("570f9f46da1c882fea57db94"))],"resourceInstances":[{"id_resourceInstances" : DBRef("resourceInstance",ObjectId("570f9f46da1c882fea57db54")),"customAttributes":{"fechaInicio":"2016-01-01","fechaFin":"2016-12-31"}}],"customAttributes" :{"marca" :"KENWORTH","modelo":"2012"}},
        "task" : {
                "_id" : "572bf270c740cde2218b45a5",
                "name": "TAREA 32",
        },
        "eventCategory" : "Planning",
        "eventType" : "checkin",
        "message" : "Se hizo checkin",
        "updateTime" : ISODate("2016-05-22T10:16:26.847Z")
}
]);

print("Cargadas todos los eventos");

print("Insertando las campaigns");

db.campaigns.insert(
    [
        {
            "id_campaign": 20,
            "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
            "name": "TUM",
            "description": "TUM",
            "tags": "TUM",
            "ts_start_date": "2015-12-01",
            "ts_end_date": "2015-12-31",
            "status": "ACTIVO",
            "previous_version": "at8uHgqVmA",
            "actual_version": "m1lOMxnBnN",
            "id_task": DBRef("tasks", ObjectId("572bf279c740cde2218b45d1")),
            "id_resourceInstance": DBRef("resourceInstances", ObjectId("56f55709efe9e8d575768a50"))
        },
        {
            "id_campaign": 21,
            "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
            "name": "TUM",
            "description": "TUM",
            "tags": "TUM",
            "ts_start_date": "2015-12-01",
            "ts_end_date": "2015-12-31",
            "status": "ACTIVO",
            "previous_version": "at8uHgqVmA",
            "actual_version": "m1lOMxnBnN",
            "id_task": DBRef("tasks", ObjectId("572bf270c740cde2218b4593")),
            "id_resourceInstance": DBRef("resourceInstances", ObjectId("56f55709efe9e8d575768a50"))
        },
        {
            "id_campaign": 22,
            "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
            "name": "TUM",
            "description": "TUM",
            "tags": "TUM",
            "ts_start_date": "2015-12-01",
            "ts_end_date": "2015-12-31",
            "status": "ACTIVO",
            "previous_version": "at8uHgqVmA",
            "actual_version": "m1lOMxnBnN",
            "id_task": DBRef("tasks", ObjectId("572bf270c740cde2218b45a7")),
            "id_resourceInstance": DBRef("resourceInstances", ObjectId("56f55709efe9e8d575768a50"))
        },
        {
            "id_campaign": 23,
            "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
            "name": "TUM",
            "description": "TUM",
            "tags": "TUM",
            "ts_start_date": "2015-12-01",
            "ts_end_date": "2015-12-31",
            "status": "ACTIVO",
            "previous_version": "at8uHgqVmA",
            "actual_version": "m1lOMxnBnN",
            "id_task": DBRef("tasks", ObjectId("572bf270c740cde2218b45a5")),
            "id_resourceInstance": DBRef("resourceInstances", ObjectId("56f55709efe9e8d575768a50"))
        },
        {
            "id_campaign": 24,
            "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
            "name": "TUM",
            "description": "TUM",
            "tags": "TUM",
            "ts_start_date": "2015-12-01",
            "ts_end_date": "2015-12-31",
            "status": "ACTIVO",
            "previous_version": "at8uHgqVmA",
            "actual_version": "m1lOMxnBnN",
            "id_task": DBRef("tasks", ObjectId("572bf270c740cde2218b4591")),
            "id_resourceInstance": DBRef("resourceInstances", ObjectId("56f55709efe9e8d575768a50"))
        },
        {
            "id_campaign": 25,
            "id_company" : DBRef("companies", ObjectId("56f55709efe9e8d575768a54")),
            "name": "TUM",
            "description": "TUM",
            "tags": "TUM",
            "ts_start_date": "2015-12-01",
            "ts_end_date": "2015-12-31",
            "status": "ACTIVO",
            "previous_version": "at8uHgqVmA",
            "actual_version": "m1lOMxnBnN",
            "id_task": DBRef("tasks", ObjectId("572bf270c740cde2218b4592")),
            "id_resourceInstance": DBRef("resourceInstances", ObjectId("56f55709efe9e8d575768a50"))
        }
    ]
);

print("Cargadas todos las campaigns");

print("Insertando las forms");

db.forms.insert(
    [
        {
            "id_form": 25,
            "id_theme": 1,
            "id_campaign": 20,
            "name": "TUM",
            "description": "TUM",
            "status": "ACTIVO",
            "sections": null,
            "notifications": null,
            "indicators": null,
            "created_at": null,
            "id_user_create": null,
            "updated_at": null,
            "id_user_update": null,
            "deleted_at": null,
            "variables": null,
            "previous_version": "0ul7d3lmA7",
            "actual_version": "buu5DkaNyJ"
        },
        {
            "id_form": 26,
            "id_theme": 1,
            "id_campaign": 21,
            "name": "TUM",
            "description": "TUM",
            "status": "ACTIVO",
            "sections": null,
            "notifications": null,
            "indicators": null,
            "created_at": null,
            "id_user_create": null,
            "updated_at": null,
            "id_user_update": null,
            "deleted_at": null,
            "variables": null,
            "previous_version": "0ul7d3lmA7",
            "actual_version": "buu5DkaNyJ"
        },
        {
            "id_form": 27,
            "id_theme": 1,
            "id_campaign": 22,
            "name": "TUM",
            "description": "TUM",
            "status": "ACTIVO",
            "sections": null,
            "notifications": null,
            "indicators": null,
            "created_at": null,
            "id_user_create": null,
            "updated_at": null,
            "id_user_update": null,
            "deleted_at": null,
            "variables": null,
            "previous_version": "0ul7d3lmA7",
            "actual_version": "buu5DkaNyJ"
        },
        {
            "id_form": 28,
            "id_theme": 1,
            "id_campaign": 23,
            "name": "TUM",
            "description": "TUM",
            "status": "ACTIVO",
            "sections": null,
            "notifications": null,
            "indicators": null,
            "created_at": null,
            "id_user_create": null,
            "updated_at": null,
            "id_user_update": null,
            "deleted_at": null,
            "variables": null,
            "previous_version": "0ul7d3lmA7",
            "actual_version": "buu5DkaNyJ"
        },
        {
            "id_form": 29,
            "id_theme": 1,
            "id_campaign": 24,
            "name": "TUM",
            "description": "TUM",
            "status": "ACTIVO",
            "sections": null,
            "notifications": null,
            "indicators": null,
            "created_at": null,
            "id_user_create": null,
            "updated_at": null,
            "id_user_update": null,
            "deleted_at": null,
            "variables": null,
            "previous_version": "0ul7d3lmA7",
            "actual_version": "buu5DkaNyJ"
        },
        {
            "id_form": 30,
            "id_theme": 1,
            "id_campaign": 25,
            "name": "TUM",
            "description": "TUM",
            "status": "ACTIVO",
            "sections": null,
            "notifications": null,
            "indicators": null,
            "created_at": null,
            "id_user_create": null,
            "updated_at": null,
            "id_user_update": null,
            "deleted_at": null,
            "variables": null,
            "previous_version": "0ul7d3lmA7",
            "actual_version": "\r"
        }
    ]);

print("Cargadas todos las forms");

print("Insertando las sections");

var id_section = 0;
db.forms.find({}).forEach(function (form) {

    id_section++;
    section = {};
    section.id_section = id_section;
    section.id_form =  form.id_form;
    section.questions =
        [
            {
                "cid": "c1",
                "order": 1,
                "xtype": "textfield",
                "hashtag": "c1",
                "helptext": "Identificador",
                "configuration": {
                    "hidden": false,
                    "readOnly": false,
                    "blankText": "Identificador",
                    "emptyText": "Ej: 4545",
                    "allowBlank": false,
                    "fieldLabel": "Identificador"
                }
            },
            {
                "cid": "c2",
                "order": 2,
                "xtype": "textfield",
                "hashtag": "c2",
                "helptext": "Nombre Comercial",
                "configuration": {
                    "hidden": false,
                    "readOnly": false,
                    "blankText": "Nombre Comercial",
                    "emptyText": "Ej: Coca-Cola",
                    "maxLength": 30,
                    "allowBlank": false,
                    "fieldLabel": "Nombre Comercial"
                }
            },
            {
                "cid": "c3",
                "order": 3,
                "xtype": "textfield",
                "hashtag": "c3",
                "helptext": "Razon Social",
                "configuration": {
                    "hidden": false,
                    "readOnly": false,
                    "blankText": "Razon Social",
                    "emptyText": "Ej: FEMSA",
                    "maxLength": 30,
                    "allowBlank": false,
                    "fieldLabel": "Razon Social"
                }
            },
            {
                "cid": "c4",
                "order": 4,
                "xtype": "combobox",
                "hashtag": "c4",
                "helptext": "Estado",
                "configuration": {
                    "items": [
                        {
                            "id": "Activo",
                            "name": "Activo",
                            "boxLabel": "Activo",
                            "inputValue": "Activo"
                        },
                        {
                            "id": "Inactivo",
                            "name": "Inactivo",
                            "boxLabel": "Inactivo",
                            "inputValue": "Inactivo"
                        },
                        {
                            "id": "Cerrado",
                            "name": "Cerrado",
                            "boxLabel": "Cerrado",
                            "inputValue": "Cerrado"
                        },
                        {
                            "id": "Tiempo",
                            "name": "Tiempo",
                            "boxLabel": "Tiempo Insuficiente",
                            "inputValue": "Tiempo Insuficiente"
                        },
                        {
                            "id": "Acceso",
                            "name": "Acceso",
                            "boxLabel": "No acceso (obras, manifestacion, etc..)",
                            "inputValue": "No acceso"
                        },
                        {
                            "id": "Venta",
                            "name": "Venta",
                            "boxLabel": "No vende Cigarros",
                            "inputValue": "No vende Cigarros"
                        }
                    ],
                    "hidden": false,
                    "readOnly": false,
                    "allowBlank": false,
                    "fieldLabel": "Estado",
                    "multiSelect": false
                }
            },
            {
                "cid": "c5",
                "order": 5,
                "xtype": "combobox",
                "hashtag": "c5",
                "helptext": "Estado",
                "configuration": {
                    "items": [
                        {
                            "id": "Semanal",
                            "name": "Semanal",
                            "boxLabel": "Semanal",
                            "inputValue": "Semanal"
                        },
                        {
                            "id": "Quincenal",
                            "name": "Quincenal",
                            "boxLabel": "Quincenal",
                            "inputValue": "Quincenal"
                        }
                    ],
                    "hidden": false,
                    "readOnly": false,
                    "allowBlank": false,
                    "fieldLabel": "Frecuencia de visita",
                    "multiSelect": false
                }
            },
            {
                "cid": "c6",
                "order": 6,
                "xtype": "combobox",
                "hashtag": "c6",
                "helptext": "Dias de Visita",
                "configuration": {
                    "items": [
                        {
                            "id": "Lunes",
                            "name": "Lunes",
                            "boxLabel": "Lunes",
                            "inputValue": "Lunes"
                        },
                        {
                            "id": "Martes",
                            "name": "Martes",
                            "boxLabel": "Martes",
                            "inputValue": "Martes"
                        },
                        {
                            "id": "Miercoles",
                            "name": "Miercoles",
                            "boxLabel": "Miercoles",
                            "inputValue": "Miercoles"
                        },
                        {
                            "id": "Jueves",
                            "name": "Jueves",
                            "boxLabel": "Jueves",
                            "inputValue": "Jueves"
                        },
                        {
                            "id": "Viernes",
                            "name": "Viernes",
                            "boxLabel": "Viernes",
                            "inputValue": "Viernes"
                        },
                        {
                            "id": "Sabado",
                            "name": "Sabado",
                            "boxLabel": "Sabado",
                            "inputValue": "Sabado"
                        }
                    ],
                    "hidden": false,
                    "readOnly": false,
                    "allowBlank": false,
                    "fieldLabel": "Dias de Visita",
                    "multiSelect": true
                }
            },
            {
                "cid": "c7",
                "order": 7,
                "xtype": "textfield",
                "hashtag": "c7",
                "helptext": "RFC",
                "configuration": {
                    "hidden": false,
                    "readOnly": false,
                    "blankText": "RFC",
                    "emptyText": "Ej: 7NFSYSZ3BK",
                    "maxLength": 13,
                    "allowBlank": false,
                    "fieldLabel": "RFC"
                }
            },
            {
                "cid": "c8",
                "order": 8,
                "xtype": "datefield",
                "hashtag": "c8",
                "helptext": "Fecha de Ingreso",
                "configuration": {
                    "format": "yyyy-MM-dd",
                    "hidden": false,
                    "readOnly": true,
                    "blankText": "La fecha de ingreso es obligatoria",
                    "emptyText": "2016-04-12",
                    "allowBlank": false,
                    "fieldLabel": "Fecha de Ingreso"
                }
            },
            {
                "cid": "c9",
                "order": 9,
                "xtype": "textfield",
                "hashtag": "c9",
                "helptext": "Contacto",
                "configuration": {
                    "hidden": false,
                    "readOnly": false,
                    "blankText": "Contacto",
                    "emptyText": "Ej: Sergio Sinuco",
                    "maxLength": 30,
                    "allowBlank": false,
                    "fieldLabel": "Contacto"
                }
            },
            {
                "cid": "c10",
                "order": 10,
                "xtype": "textfield",
                "hashtag": "c10",
                "helptext": "Telefono",
                "configuration": {
                    "hidden": false,
                    "readOnly": false,
                    "blankText": "Telefono",
                    "emptyText": "Ej: 5538786805",
                    "maxLength": 30,
                    "allowBlank": false,
                    "fieldLabel": "Telefono"
                }
            },
            {
                "cid": "c11",
                "order": 11,
                "xtype": "textfield",
                "hashtag": "c11",
                "helptext": "Movil",
                "configuration": {
                    "hidden": false,
                    "readOnly": false,
                    "blankText": "Movil",
                    "emptyText": "Ej: 5538786805",
                    "maxLength": 30,
                    "allowBlank": false,
                    "fieldLabel": "Movil"
                }
            },
            {
                "cid": "c12",
                "order": 12,
                "xtype": "textfield",
                "hashtag": "c12",
                "helptext": "Mail",
                "configuration": {
                    "vtype": "email",
                    "hidden": false,
                    "readOnly": false,
                    "blankText": "El email es obligatorio",
                    "emptyText": "Ej: info@colcacola.com",
                    "vtypeText": "Formato invalido de Mail",
                    "allowBlank": false,
                    "fieldLabel": "Mail"
                }
            }
        ]
    section.name = "Informacion del cliente";
    db.sections.save(section);

    id_section++;
    section = {};
    section.id_section = id_section;
    section.id_form =  form.id_form;
    section.questions =
        [
            {
                "cid": "c13",
                "order": 13,
                "xtype": "combobox",
                "hashtag": "c13",
                "helptext": "AAA: Minimo de 10 SKU de cigarros y que vende al p\u00fablico m\u00e1s de 210 cajetillas de cigarros por semana. A: Minimo de 10 SKU de cigarros y que vende al p\u00fablico entre 170 y 200 cajetillas de cigarros por semana. B: Minimo de 10 SKU de cigarros y que vende al p\u00fablico entre 100 y 160 cajetillas de cigarros por semana.",
                "configuration": {
                    "items": [
                        {
                            "id": "AAA",
                            "name": "AAA",
                            "boxLabel": "AAA",
                            "inputValue": "AAA"
                        },
                        {
                            "id": "A",
                            "name": "A",
                            "boxLabel": "A",
                            "inputValue": "A"
                        },
                        {
                            "id": "B",
                            "name": "B",
                            "boxLabel": "B",
                            "inputValue": "B"
                        }
                    ],
                    "hidden": false,
                    "readOnly": false,
                    "allowBlank": false,
                    "fieldLabel": "Segmentacion Universal",
                    "multiSelect": false
                }
            },
            {
                "cid": "c14",
                "order": 14,
                "xtype": "textfield",
                "hashtag": "c14",
                "helptext": "Ruta",
                "configuration": {
                    "hidden": false,
                    "readOnly": false,
                    "blankText": "Ruta",
                    "emptyText": "Ej: 111021",
                    "allowBlank": false,
                    "fieldLabel": "Ruta"
                }
            },
            {
                "cid": "c15",
                "order": 15,
                "xtype": "numberfield",
                "hashtag": "c15",
                "helptext": "Codigo Postal",
                "configuration": {
                    "hidden": false,
                    "readOnly": false,
                    "blankText": "Codigo Postal",
                    "emptyText": "Ej: 111021",
                    "allowBlank": false,
                    "fieldLabel": "Codigo Postal"
                }
            },
            {
                "cid": "c16",
                "order": 16,
                "xtype": "combobox",
                "hashtag": "c16",
                "helptext": "Estado",
                "configuration": {
                    "items": [
                        {
                            "id": "D.F.",
                            "name": "D.F.",
                            "boxLabel": "D.F.",
                            "inputValue": "D.F."
                        }
                    ],
                    "hidden": false,
                    "readOnly": false,
                    "allowBlank": false,
                    "fieldLabel": "Estado",
                    "multiSelect": false
                }
            },
            {
                "cid": "c17",
                "order": 17,
                "xtype": "combobox",
                "hashtag": "c17",
                "helptext": "Municipio",
                "configuration": {
                    "items": [
                        {
                            "id": "Alvaro Obregon",
                            "name": "Alvaro Obregon",
                            "boxLabel": "Alvaro Obregon",
                            "inputValue": "Alvaro Obregon"
                        },
                        {
                            "id": "Xochimilco",
                            "name": "Xochimilco",
                            "boxLabel": "Xochimilco",
                            "inputValue": "Xochimilco"
                        }
                    ],
                    "hidden": false,
                    "readOnly": false,
                    "allowBlank": false,
                    "fieldLabel": "Municipio",
                    "multiSelect": false
                }
            },
            {
                "cid": "c18",
                "order": 18,
                "xtype": "combobox",
                "hashtag": "c18",
                "helptext": "Colonia",
                "configuration": {
                    "items": [
                        {
                            "id": "Alvaro Obregon",
                            "name": "Alvaro Obregon",
                            "boxLabel": "Alvaro Obregon",
                            "inputValue": "Alvaro Obregon"
                        },
                        {
                            "id": "Xochimilco",
                            "name": "Xochimilco",
                            "boxLabel": "Xochimilco",
                            "inputValue": "Xochimilco"
                        }
                    ],
                    "hidden": false,
                    "readOnly": false,
                    "allowBlank": false,
                    "fieldLabel": "Colonia",
                    "multiSelect": false
                }
            },
            {
                "cid": "c19",
                "order": 19,
                "xtype": "combobox",
                "hashtag": "c19",
                "helptext": "AAA: Minimo de 10 SKU de cigarros y que vende al p\u00fablico m\u00e1s de 210 cajetillas de cigarros por semana. A: Minimo de 10 SKU de cigarros y que vende al p\u00fablico entre 170 y 200 cajetillas de cigarros por semana. B: Minimo de 10 SKU de cigarros y que vende al p\u00fablico entre 100 y 160 cajetillas de cigarros por semana.",
                "configuration": {
                    "items": [
                        {
                            "id": "AAA",
                            "name": "AAA",
                            "boxLabel": "AAA",
                            "inputValue": "AAA"
                        },
                        {
                            "id": "A",
                            "name": "A",
                            "boxLabel": "A",
                            "inputValue": "A"
                        },
                        {
                            "id": "B",
                            "name": "B",
                            "boxLabel": "B",
                            "inputValue": "B"
                        }
                    ],
                    "hidden": false,
                    "readOnly": false,
                    "allowBlank": false,
                    "fieldLabel": "Segmentacion Propia",
                    "multiSelect": false
                }
            },
            {
                "cid": "c20",
                "order": 20,
                "xtype": "numberfield",
                "hashtag": "c20",
                "helptext": "Captura Coordenadas",
                "configuration": {
                    "hidden": false,
                    "readOnly": false,
                    "blankText": "Captura Coordenadas",
                    "emptyText": "Ej: 111021",
                    "allowBlank": false,
                    "fieldLabel": "Captura Coordenadas"
                }
            },
            {
                "cid": "c21",
                "order": 21,
                "xtype": "datefield",
                "hashtag": "c21",
                "helptext": "Fecha checkin",
                "configuration": {
                    "format": "yyyy-MM-dd",
                    "hidden": true,
                    "readOnly": false,
                    "blankText": "Fecha checkin",
                    "emptyText": "2016-04-12",
                    "allowBlank": false,
                    "fieldLabel": "Fecha checkin"
                }
            },
            {
                "cid": "c22",
                "order": 22,
                "xtype": "numberfield",
                "hashtag": "c22",
                "helptext": "Longitude checkin",
                "configuration": {
                    "hidden": true,
                    "readOnly": false,
                    "blankText": "Longitude checkin",
                    "emptyText": "Ej: 111021",
                    "allowBlank": false,
                    "fieldLabel": "Longitude checkin"
                }
            },
            {
                "cid": "c23",
                "order": 23,
                "xtype": "numberfield",
                "hashtag": "c23",
                "helptext": "Longitude checkin",
                "configuration": {
                    "hidden": true,
                    "readOnly": false,
                    "blankText": "Longitude checkin",
                    "emptyText": "Ej: 111021",
                    "allowBlank": false,
                    "fieldLabel": "Longitude checkin"
                }
            },
            {
                "cid": "c24",
                "order": 24,
                "xtype": "datefield",
                "hashtag": "c24",
                "helptext": "Fecha checkout",
                "configuration": {
                    "format": "yyyy-MM-dd",
                    "hidden": true,
                    "readOnly": false,
                    "blankText": "Fecha checkout",
                    "emptyText": "2016-04-12",
                    "allowBlank": false,
                    "fieldLabel": "Fecha checkout"
                }
            },
            {
                "cid": "c25",
                "order": 25,
                "xtype": "numberfield",
                "hashtag": "c25",
                "helptext": "Latitude checkout",
                "configuration": {
                    "hidden": true,
                    "readOnly": false,
                    "blankText": "Latitude checkout",
                    "emptyText": "Ej: 111021",
                    "allowBlank": false,
                    "fieldLabel": "Latitude checkout"
                }
            },
            {
                "cid": "c26",
                "order": 26,
                "xtype": "numberfield",
                "hashtag": "c26",
                "helptext": "Longitude checkout",
                "configuration": {
                    "hidden": true,
                    "readOnly": false,
                    "blankText": "Longitude checkout",
                    "emptyText": "Ej: 111021",
                    "allowBlank": false,
                    "fieldLabel": "Longitude checkout"
                }
            },
            {
                "cid": "c27",
                "order": 27,
                "xtype": "textfield",
                "hashtag": "c27",
                "helptext": "errorReason",
                "configuration": {
                    "hidden": false,
                    "readOnly": false,
                    "blankText": "errorReason",
                    "emptyText": "Ej: Direccion no encontrada",
                    "allowBlank": false,
                    "fieldLabel": "errorReason"
                }
            }
        ]
    section.name = "Caracterizacion del cliente";

    db.sections.save(section);
});

print("Cargadas todos las sections");