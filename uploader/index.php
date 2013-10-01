<!DOCTYPE html>
<html>
<head>
	<title>Uploader Plugin</title>
	
	<!-- libraries -->
	<script type="text/javascript" src="scripts/jquery-1.8.3.js"></script>


	<script type="text/javascript" src="scripts/base.js"></script>
	<link rel="stylesheet" media="screen" type="text/css" href="css/base.css"/>

	<link rel="shortcut icon" href="images/favicon.ico" />
</head>
<body>

	<div id="container">
		<div id="main">
			<div id="pageTitle" align="center">Uploader :)</div>
				
			<div id="create">[+]</div>

			<div id="pageOperations">
				<div id="createEvent">
					<form id="createEventForm" name="createEventForm" method="post" action="">			
						<div id="progressbar"><div id="fillAmount"></div></div>	
						<input type="file" id="regFiles" name="files[]" multiple>
						<div id="dndFiles" class="uploadArea" name="files[]">
							<div id="backText">[Drop Docs Here]</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="clearer"></div>
</body>
</html>