<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Tutorial</title>
</head>
<body>
	<h1>How use the script to create database</h1>
	<h5>Without showing log</h5>

	<?php
		$log =(bool)false;
		include("create_database.php");
		include("create_utilisateur.php");
	?>
	<h5>Showing log</h5>
	<?php
		$log =(bool)true;
		include("create_database.php");
		include("create_utilisateur.php");
	?>
	<!--to had a new user if name is written in the url-->
	<p>
		<?php
		// connect to database louda
			$bdd = new mysqli("localhost","admin","it103","louda");
			if ($bdd->connect_error) {
				die("Connection failed: " . $bdd->connect_error);
			} else {
				echo "Connection successful <br />";
			}

		//create the SQL request
			if (isset($_GET['name'])){
				$request = "INSERT INTO utilisateur (email,mot_de_passe,prenom,nom,pseudo,date_de_naissance) VALUES ('thibaut.robinet@outlook.fr','000000','" . $_GET['name']  . "','ROBINET','Tibs','1998-03-11')";
				echo "$request <br />";

				//try to query SQL request
				if (mysqli_query($bdd,$request)){
					echo "NEW user added <br />";
				} else {
					echo "ERROR : " . $request . " " . mysqli_error($bdd) . "<br />";
				}
			}
			//close connection with louda
			$bdd->close();

		?>
	</p>
</body>
<?php include("footer.php"); ?>
</html>
