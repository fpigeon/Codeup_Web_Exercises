<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>My First HTML Form</title>
</head>
<body>
    <h2>User Login</h2>
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
        <button type="submit">Login</button>
    </p>

    </form>
    
    <!-- email form to, from, subject, body, and a send button -->
    <h2>Compose an Email</h2>
    <form method="POST">
        <p>
            <label for="to">to</label>
            <input id="to" name="to" type="email">
            <br>
            <label for="from">from</label>
            <input id="from" name="from" type="email">
            <br>
            <label for="subject">subject</label>
            <input id="subject" name="subject" type="text">
            <br>
            <textarea name="body" id="body" cols="30" rows="10" placeholder="body here"></textarea>
            <br>
            <button type="submit">Send</button>
        </p>    
    </form>


<?php
	var_dump($_GET);
	var_dump($_POST);
?>
</body>
</html>