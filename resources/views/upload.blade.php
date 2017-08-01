<!DOCTYPE html>
<html>
<body>

<form action="scheduling/processes/upload?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJsb2dpbiI6InBlZHJvY2FpY2VkbyIsImlkX2NvbXBhbnkiOiI1NmY1NTcwOWVmZTllOGQ1NzU3NjhhNTQiLCJzdWIiOiI1Nzg3YWFlMTQ0OTllMzI0NjAwMzQwNDUiLCJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2ZpZWxkdmlzaW9uX2FwaVwvcHVibGljXC9sb2dpbiIsImlhdCI6MTQ3MDY3NTUzNCwiZXhwIjoxNDcwNzYxOTM0LCJuYmYiOjE0NzA2NzU1MzQsImp0aSI6IjU5ZDg4OWEzYTYyYmMyMjNkODdlMjAxNmMxZDkxMzgwIn0.Acl23lmnEDAAASWzkfK3KqfkEbjI7S_oZwopUJTDmuA" method="post" enctype="multipart/form-data">
  Select image to upload:
	<input type="file" name="file" id="file">
	<textarea id="data" name="data" rows="24" cols="50">
{
	"file": {
		"delimiter": ",",
		"enclosure": "\"",
		"encoding": "UTF-8",
		"formatHour": "hh:mm:ss",
		"formatDate": "YYYY-MM-DD"
	},
	"id_company": "56f55709efe9e8d575768a54",
	"planningConfiguration": {
		"minVehicule": true,
		"minVisitsPerVehicle": 10,
		"traffic": "traffic",
		"shortestDistance": true,
		"balance": true
	},
	"statusConfigurations":[
		{
			"status":"CHECKOUT SIN FORMULARIO",
			"reasons":[
				{
					"type":["web","movil"],
					"label": "NO EXISTE",
					"value": "1",
					"withPhoto":true
				},
				{
					"type":["web"],
					"label": "CERRADO",
					"value": "1",
					"withPhoto":false

				}
			]
		},
		{
			"status":"CHECKOUT CON FORMULARIO",
			"reasons":[
				{
					"type":["web","movil"],
					"label": "FACIL",
					"value": "1",
					"withPhoto":true
				},
				{
					"type":["web"],
					"label": "DIFICIL",
					"value": "1",
					"withPhoto":false

				}
			]
		}
	],
	"resourceInstances": ["570f9f46da1c882fea57db52", "570f9f46da1c882fea57db53"],
	"resourceDefinitions": ["570f9f46da1c882fea57db56", "570f9f46da1c882fea57db57"],
	"resourceGroups": ["571009a21dd20ebf5a071bf0", "571009a21dd20ebf5a071bf1"],
	"actualStep": {
		"_class": "VisitsStep",
		"totalLines": 20,
		"totalProcessed": 19,
		"totalError": 1,
		"totalOK": 19,
		"fileNameError": "scheduled.csv.error",
		"filePathError": "/data/schedule_error/"
	},
	"steps": [{
		"_class": "VisitsStep",
		"totalLines": 20,
		"totalProcessed": 19,
		"totalError": 1,
		"totalOK": 19,
		"fileNameError": "scheduled.csv.error",
		"filePathError": "/data/schedule_error/"
	}, {
		"_class": "PlanningStep",
		"status": "OK",
		"dateAPIRequest": "2016-07-27 23:34:58",
		"dateAPIResponse": "2016-07-27 23:34:58",
		"jsonAPIRequest": "{\"res\":\"1\"}",
		"jsonAPIResponse": "{\"total\":\"2\"}",
		"totalAPIBatchQuery": 1,
		"dateLastAPIBatchQuery": "2016-07-27 23:34:58"
	}, {
		"_class": "RoutingStep",
		"totalRoutes ": 30,
		"totalGenerated": 25,
		"totalErrors": 5
	}, {
		"_class": "TaskGeneratingStep",
		"totalTasks  ": 25,
		"totalCopied": 25
	}],
	"forms":["578e834f1b2407ba79ea8557","579b7e56c465f8098ecc91d3"]
}
	</textarea>
	<input type="submit" value="Upload Image" name="submit">
</form>
</body>
</html>