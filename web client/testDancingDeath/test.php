<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Test Dancing with Death</title>
		<link href="bootstrap.css" type="text/css" rel="STYLESHEET">
		<link href="calendario_dw/calendario_dw-estilos.css" type="text/css" rel="STYLESHEET">
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="functions.js"></script>
		<script type="text/javascript" src="calendario_dw/calendario_dw.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
			   $(".campofecha").calendarioDW();
			})
		</script>
	</head>
	<body>
		<div id="principal" class="container" style="margin-top: 100px;">
			<div class="row">
	        	<div class="col-xs-12 col-sm-12 col-md-12">
	        		<div class="panel panel-default" >
	        			<div class="panel-heading">
							Select date to dance with death
						</div>
						<div id="div1" name="div1" class="panel-body" >
							<div class="form-section">
								<form id="formDiv1" name="formDiv1">
									<div class="row">
										<div class="col-xs-12 col-sm-3 col-md-3" style="margin-left: 90px; padding: 0px 5px 0px 5px; width: 300px;">
											<div class="input-group">
												<label class="control-label" style="width: 260px;">Date</label> 
												<input type="text" id="fecha" name="fecha" class="form-control campofecha" size="12">
											</div>
										</div>
										<div class="col-xs-12 col-sm-1 col-md-1" style="padding: 26px 0px 0px 0px;">
												<input id="enviarForm1" name="enviarForm1" type="button" value="Send" class="btn btn-default" onclick="processFormDiv1();"/>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" >
	        	<div class="col-xs-12 col-sm-12 col-md-12">
	        		<div class="panel panel-default" >
	        			<div class="panel-heading">
							Fill out the form by selecting one of the hours available for the appointment and filling in the personal information fields
						</div>
						<div id="div2" name="div2" class="panel-body" >
							<div class="form-section">
								<form id="formDiv2" name="formDiv2">
									<div class="form-group">
										<div class="row">
											<div class="col-xs-12 col-sm-1 col-md-1" >
												<input id="date" name="date" type="text"  value="" class="form-control" style="display: none;" />
											</div>
											<div class="col-xs-12 col-sm-3 col-md-3" >
												<label class="control-label">Name</label>
												<input id="name" name="name" type="text"  value="" class="form-control" />
											</div>
											<div class="col-xs-12 col-sm-3 col-md-3" >
												<label class="control-label">Mail</label>
												<input id="mail" name="mail" type="text"  value="" class="form-control" />
											</div>
											<div class="col-xs-12 col-sm-3 col-md-3" >
												<label class="control-label">Hour</label>
												<select id="hours" name="hours" class="form-control" ></select>
											</div>
											<div class="col-xs-12 col-sm-1 col-md-1" style="padding: 26px 0px 0px 0px;">
												<input name="enviarForm2" type="button" value="Send" class="btn btn-default" onclick="processFormDiv2();"/>
											</div>
											<div id="respNoAvailavle" class="col-xs-12 col-sm-10 col-md-10" style="margin: 20px 0px 0px 75px; display: none;">
												<div class="panel panel-danger">
													<div id="divAvailableHours" name="divAvailableHours" class="panel-heading"></div>
												</div>
											</div>
										</div>
										
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
	        	<div class="col-xs-12 col-sm-12 col-md-12">
	        		<div id="respSuccess" class="panel panel-info" style="display: none;">
						<div id="div3" name="div3" class="panel-heading"></div>
					</div>
				</div>
			</div>
		</div>	
	</body>
</html>	