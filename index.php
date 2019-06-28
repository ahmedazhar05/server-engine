<?php
  session_start();
  $c=(isset($_POST['i'])?$_POST['i']:'');
  if ($c=='lout') {
    session_unset();
    session_destroy();
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Learn PHP</title>
  </head>
  <style type="text/css">
    body{
      margin: 0 auto;
      padding: 0 200px;
      font-family: monospace;
    }
    h2{
      text-align: center;
    }
    form#dbsql{
      border:1px solid grey;
      padding: 20px;
      border-radius: 10px;
      overflow: auto;
    }
    input:active{
      box-shadow: 2px solid 5px 5px 5px;
    }
  </style>
  <body>
  	<h2>Database Login</h2>
    <?php
        if(isset($_SESSION['user'],$_SESSION['pass'])){
          header('Location:./enter.php');
          exit;
        }else if (isset($GLOBALS['c']) && $GLOBALS['c'] == 'invalid') {
          echo '<div><b style="color:red">Wrong Login data inputted !</b><br><br></div>';
        }else if (isset($GLOBALS['c']) && $GLOBALS['c'] == 'lout') {
          echo '<div><b style="color:blue">Logged Out Successfully !</b><br><br></div>';
        }
      ?>
  	<form action="./enter.php" method="post" id="dbsql">
      <label for="server">Server : </label><input id="server" type="text" name="server" placeholder="localhost"><br><br>
    	<label for="username">Username : </label><input id="username" type="text" name="username" required><br><br>
  		<label for="password">Password : </label><input id="password" type="password" name="password" ><br><br>
  		<input type="submit" name="submit" value="Connect">
  	</form>
  </body>
</html>
