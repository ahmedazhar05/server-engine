<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
  	<head>
    	<title>Learn PHP</title>
  	</head>
  	<style type="text/css">
  		body{
  			margin: 0 auto;
  			font-family: monospace;
  			font-size:15px;
  			padding: 0 200px;
  		}
  		form#dbsql{
  			margin-bottom: 10px;
  		}
  		div#hints{
			font-size:13px;
  		}
  		form#dbsql, div#hints{
  			border:1px solid grey;
  			padding: 20px;
  			border-radius: 10px;
  			overflow: auto;
  		}
  		table#results, #results td, #results th{
  			border:1px solid black;
  			border-collapse:collapse;
  		}
  		#results td, #results th{
  			padding:5px;
  		}
  		button#logout{
  			float: right;
  			position: absolute;
  			top: 25px;
  			right: 200px;
  			padding: 10px;
  		}
  	</style>
    <body>
	  	<div id="redir"></div>
    	<script type="text/javascript">
			function redir(str) {
				document.getElementById('redir').innerHTML='<form style="display:none;" position="absolute" method="post" action="./"><input id="instruction" type="submit" name="i" value='+str+'></form>';
				document.getElementById('instruction').click();
			}
		</script>
    	<?php
			$user=$password=$db="";
			$server='localhost';//default host
    			if (isset($_POST['server']) && strlen($_POST['server']) != 0){
    				$server=$_POST['server'];
    			}
			if (isset($_SESSION['user'],$_SESSION['pass'])){
				$user=$_SESSION['user'];
				$password=$_SESSION['pass'];
			} elseif (isset($_POST['username'],$_POST['password'])){
				$user=$_SESSION['user']=$_POST['username'];
				$password=$_SESSION['pass']=$_POST['password'];
			} else {
				header('Location:./');
				exit;
			}
			if(isset($_POST['database']))
				$db=$_POST['database'];
			$conn = new mysqli($server,$user,$password,$db);
			if ($conn->connect_error){
				session_unset();
				session_destroy();
				echo '<script type="text/javascript">redir("invalid");</script>';
				//header('Location:./index.php?i=invalid');
				exit;
			}
			else{
				echo '<h2 style="color:limegreen;margin-block-end:0px;">Connected to Database !</h2><h4 style="margin-block-start:0px;">Logged in as : '.$user.'@'.$server.'</h4>';
			}
			if(isset($_POST['textquery'])){
				$sql=$_POST['textquery'];
				unset($_POST['textquery']);
				$q=$conn->query($sql);
				if ($q === TRUE) {
					echo '<b><h3 style="font-style:bold;">Query successfully processed</b> : '.$sql.'</h3>';
				} else if ($q != FALSE) {
					$test=$q->field_count;
					echo "Field count = ".$test.".&nbsp;&nbsp;&nbsp;&nbsp;";
					$test=$q->num_rows;
					echo "Number of Rows = ".$test.".<br><br>";
					echo '<b><h3 style="font-style:bold;">Query successfully processed</b> : '.$sql.'</h3><h3>RESULT :</h3><table id="results">';
					//First Row START
					$i=0;
					echo "<tr>";
					while ($field = $q->fetch_field()) {
						printf('<th>'.$field->name.'<br>('.$field->type.')</th>');
					}
					//First Row END
					echo "</tr>";
					while ($field = $q->fetch_array(MYSQLI_NUM)){
						echo "<tr>";
						$i=0;
						while ($i != $q->field_count) {
							echo '<td>'.$field[$i++].'</td>';
						}
						echo "</tr>";
					}
					echo "</table><br><br>";
				} else {
					echo '<b><h3 style="font-style:bold;">Error while processing</b> : ' . $conn->error.'</h3>';
				}
				//$q->close();
			}
			$conn->close();
		?>
		<button id="logout" onclick="redir('lout');">Log Out</button>
	    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" id="dbsql">
	    	<label for="commands">Enter an SQL Command :</label><br>
	    	<textarea id="commands" rows="5" cols="50" placeholder="Input a MySQL command here...
SELECT * FROM test;" name="textquery" wrap="hard" autofocus required></textarea><br><br>
			<label for="database">Database Selected :</label>
			<select id="database" name="database" form="dbsql">
			  	<option value="">None</option>
			  	<?php
			  		$conn = new mysqli("localhost",$GLOBALS['user'],$GLOBALS['password']);
			  		$result = $conn->query('SHOW DATABASES;');
					while($row = $result->fetch_array())
						echo '<option value='.$row[0].'>'.$row[0].'</option>';
					$result->close();
					$conn->close();
				?>
			</select>
			<br><br>
			<input type="submit" name="submit" value="Process">
	  	</form>
	  	<div id="hints">
	  		<h3 style="margin-block-start:0px;">Example : </h3>
	  		<ul>
	  			<li><span>CREATE DATABASE base;</span></li>
	  			<li><span>DROP DATABASE base;</span></li>
	  			<li><span>CREATE TABLE Persons (PersonID int NOT NULL AUTO_INCREMENT, LastName varchar(255),FirstName varchar(255),Address varchar(255), City varchar(255), 
	    PRIMARY KEY(PersonID));</span></li>
	  			<li><span>INSERT INTO Persons (LastName, FirstName, Address, City) VALUES ('Cardinal', 'Tom B. Erichsen', 'Skagen 21', 'California');</span></li>
	  			<li><span>INSERT INTO Persons (LastName, FirstName, Address, City) VALUES ('Ahmed', 'Azhar', '19 A, Ripon Street', 'Kolkata');</span></li>
	  			<li><span>SELECT * FROM Persons WHERE PersonID > 0;</span></li>
	  			<li><span>SELECT User FROM mysql.user;</span></li>
	  		</ul>
	  		Or you can look up commands from : <a target="_blank" href="http://g2pc1.bu.edu/~qzpeng/manual/MySQL%20Commands.htm">http://g2pc1.bu.edu/...</a>
	  	</div>
	  	<br><br>
	</body>
</html>
