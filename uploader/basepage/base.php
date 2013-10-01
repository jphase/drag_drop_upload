<!DOCTYPE html>
<?php

	session_start();


	//if user is not signed in
	if(!isset($_SESSION['session_name']))
	{
		header("Location: ../../splash.php");
	}

	//if user logs out
	if(isset($_POST['logout']))
	{
		session_start();
		unset($_SESSION);
		session_destroy();
		header("Location: ../../splash.php");
		exit;
	}

?>
<html>
<head>
	<title>Personal Organizer</title>
	
	<!-- libraries -->
	<script type="text/javascript" src="../../scripts/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="../../scripts/jquery-migrate-1.1.0.js"></script>

	<script type="text/javascript" src="../../scripts/base.js"></script>
	<link rel="stylesheet" media="screen" type="text/css" href="../../css/base.css"/>

	<link rel="shortcut icon" href="../../images/favicon.ico" />
</head>
<body>

	<div id="container">
		<div id="main">
			<div id="settings">
				<div id="items">
					<form id="logoutForm" method="post" action="">
						<input id="logoutButton" name="logout" type="submit" value="Logout">
					</form>
					<a href="../../options.php">
						<div id="option">Options</div>
					</a>
				</div>
			</div>
			<div id="pageTitle" align="center">
				<?php
					$pageName=basename($_SERVER['PHP_SELF']);
					$courseCode=strtoupper(preg_replace("\".php\"", "", $pageName));
					$grade;

					if (preg_match("/1/", $courseCode))
					{
						$grade="Gr.9";
					}
					else if (preg_match("/2/", $courseCode))
					{
						$grade="Gr.10";
					}
					else if (preg_match("/3/", $courseCode))
					{
						$grade="Gr.11";
					}
					else if (preg_match("/4/", $courseCode))
					{
						$grade="Gr.12";
					}

					#######database connection########
					$host="localhost"; // Host name 
					$username="root"; // Mysql username 
					$password=""; // Mysql password 
					$db_name="userdatabase"; // Database name 
					$tbl_name="pagescreated"; // Table name 
					$user = $_SESSION['session_name'];
					
					// Connect to server and select database.
					mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
					mysql_select_db("$db_name") or die("cannot select DB");
					#######database connection########

					$query=mysql_query("SELECT * FROM $tbl_name WHERE courseCode='$courseCode'");
					$count=mysql_num_rows($query);
					
					if ($count == 1)
					{
						while($result = mysql_fetch_array($query))
		  				{
		  					$courseName=ucwords($result['courseName']);
		  					$teacher=ucwords($result['teacher']);
		  					$title=$teacher."'s ".$grade." ".$courseName;
		  					echo $title;
		  				}
	  				}
				?>
			</div>
			<?php 

				#######database connection########
				$host="localhost"; // Host name 
				$username="root"; // Mysql username 
				$password=""; // Mysql password 
				$db_name="userdatabase"; // Database name 
				$tbl_name="users"; // Table name 
				$user = $_SESSION['session_name'];
				
				// Connect to server and select database.
				mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
				mysql_select_db("$db_name") or die("cannot select DB");
				#######database connection########

				$sql="SELECT * FROM $tbl_name WHERE username='$user' && admin='1'";
				$result=mysql_query($sql);
				
				// Mysql_num_row is counting table row
				$count=mysql_num_rows($result);
				
				if ($count == 1)
				{
					echo"<div id=\"create\">[+]</div>";		
				}
								
			?>
			<!-- TODO: used to create events -->
			<div id="pageOperations">
				<div id="createEvent">
					<form id="createEventForm" name="createEventForm" method="post" action="">
						<p class="field" align="center">
							<input name="eventTitle" type="textbox" id="eventTitle" placeholder="[Title]"/>
						</p>
						<select id="postType" name="postType">
							<option value="--" selected="selected">-Select Post Type-</option>
							<option value="assignment">Assignment</option>
							<option value="test">Test</option>
							<option value="quiz">Quiz</option>
							<option value="project">Project</option>
						</select>
						<p class="field">
							<textarea name="eventDescription" id="eventDescription" placeholder="Notes About This Post"></textarea>
						</p>
					
						<div id="progressbar"><div id="fillAmount"></div></div>
						
						<div id="dndFiles" class="uploadArea" name="files[]">
							<div id="backText">[Drop Docs Here]</div>
						</div>
						<input type="file" id="regFiles" name="files[]" multiple>
						
						<input type="submit" name="userPost" value="Submit Post" id="buttons">
					</form>
				</div>
				<div id="postsContainer">
				
				</div>
			</div>
		</div>
	</div>
	<div class="clearer"></div>
</body>
</html>