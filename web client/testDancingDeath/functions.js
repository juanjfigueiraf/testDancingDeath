			var processFormDiv1 = function () {
				// Creación de la petición HTTP
				var req = new XMLHttpRequest();
				// Petición HTTP GET asíncrona hacia el archivo fotos.json del servidor
				req.open("GET", "http://50.63.161.212:8000/app_dev.php/date/search/" + $( "#fecha" ).val(), true);
				// Gestor del evento que indica el final de la petición (la respuesta se ha recibido)
				req.addEventListener("load", function() {
				  // La petición ha tenido éxito
				  if (req.status >= 200 && req.status < 400) {
				      console.log(" La respuesta es: " + req.responseText);

				      var objHoursAvail = JSON.parse(req.responseText);

				      //alert(" La cantidad de horas es: " + objHoursAvail.ResponseData[4]);
				      $('#date').val(objHoursAvail.ResponseData[0]);

				      $('#hours').children('option').remove();
				      $('#name').val("");
				      $('#mail').val("");
				      $("#div3").html("");
				      $("#respSuccess").hide();
				      $("#divAvailableHours").html("");
				      $("#respNoAvailavle").hide();

				      for (var i = 1; i < 11; i++) {

				      	if (objHoursAvail.ResponseData[i] != null) 
				      	{
				      		$('#hours').append('<option value="' + objHoursAvail.ResponseData[i] + 
				      		            '" selected="selected">' + objHoursAvail.ResponseData[i] + '</option>');
				      	}
				  	}

				  	if (objHoursAvail.ErrorMessage != null) 
				      	{
				      		$("#respNoAvailavle").show();
				      		$("#divAvailableHours").html(objHoursAvail.ErrorMessage);
				      	}

				  } else {
				    // Se muestran informaciones sobre el problema ocasionado durante el tratamiento de la petición
				    console.error(" El error es: " +  req.status + " " + req.statusText);
				  }
				}); 
				// Gestor del evento que indica que la petición no ha podido llegar al servidor
				req.addEventListener("error", function(){
				  console.error("Error de red"); // Error de conexión
				}); 
				// Envío de la petición
				req.send(null);

	        };

	        var processFormDiv2 = function () {
				// Creación de la petición HTTP
				var req = new XMLHttpRequest();
				// Configuracion de parametros a enviar en el cuero de la solicitud JSON
				var params = {"name" : "" + $( "#name" ).val() + "", 
							  "mail" : "" + $( "#mail" ).val() + "", 
							  "date" : "" + $( "#date" ).val() + "", 
							  "hour" : "" + $( "#hours" ).val() + ""};
				// Petición HTTP GET asíncrona hacia el archivo fotos.json del servidor
				req.open("POST", "http://50.63.161.212:8000/app_dev.php/date/create", true);
				// Gestor del evento que indica el final de la petición (la respuesta se ha recibido)
				req.addEventListener("load", function() {
				  // La petición ha tenido éxito
				  if (req.status >= 200 && req.status < 400) {
				      console.log(" La respuesta es: " + req.responseText);

				      var objSuccessMnsj = JSON.parse(req.responseText);
				      $("#respSuccess").show();
				      $("#div3").html(objSuccessMnsj.ResponseData);
				  } else {
				    // Se muestran informaciones sobre el problema ocasionado durante el tratamiento de la petición
				    console.error(" El error es: " +  req.status + " " + req.statusText);
				  }
				}); 
				// Gestor del evento que indica que la petición no ha podido llegar al servidor
				req.addEventListener("error", function(){
				  console.error("Error de red"); // Error de conexión
				}); 
				// Envío de la petición
				req.send(JSON.stringify(params));

	        };