<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>My First HTML Form</title>
</head>
<body>
	<form method="POST">
    <p>
        <label for="username">Username</label>      
        <input id="username" name="username" type="text" placeholder="username">
    </p>
    <p>
        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="passwords">
    </p>
    <!-- <p>
        <input type="submit" name="Login" value="Login">
    </p> -->
    <p>
        <button type="Submit">Login</button>
    </p>

</form>


<?php
	var_dump($_GET);
	var_dump($_POST);
?>
</body>
</html>