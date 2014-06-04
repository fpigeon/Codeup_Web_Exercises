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
            <label>Copy to your sent item?<input type="checkbox" id="sent" name="sent" value="yes" checked></label>
            <br>
            <button type="submit">Send</button>
        </p>    
    </form>
    
    <h2>Multiple Choice Test</h2>
    <form method="GET">
    
       What is your favorite Linux distro?<br>
       <label for="ubuntu"><input type="radio" id="ubuntu" name="linux" value="ubuntu">Ubuntu</label>
       <label for="red_hat"><input type="radio" id="red_hat" name="linux" value="red_hat">Red Hat</label>
       <label for="open_suse"><input type="radio" id="open_suse" name="linux" value="open_suse">Open Suse</label>
       <br>
       What is your favorite Desktop Environment?<br>
       <label for="gnome"><input type="checkbox" id="gnome" name="de[]" value="gnome">Gnome</label>
       <label for="kde"><input type="checkbox" id="kde" name="de[]" value="kde">KDE</label>
       <label for="xfce"><input type="checkbox" id="xfce" name="de[]" value="xfce">XFCE</label>
       <label for="other"><input type="checkbox" id="other" name="de[]" value="other">Other</label>
       <br>
       <label for="os">What operating systems have you used?</label>
       <br>
       <select id="os" name="os[]" multiple>
       <option value="linux">Linux</option>
       <option value="osx">OS X</option>
       <option value="windows">Windows</option>
       </select>
       <br>
       <button type="submit">Submit Answers</button>
        
    </form>
    
    <h2>Select Testing</h2>
    <form method="GET">
        Have installed an operating system from scratch?<br>
        <select id="os_install" name="os_install">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
        <br>
        <button type="OS Answers">OS Answers</button>
    </form>

<?php
    echo 'GET' . PHP_EOL;
	var_dump($_GET) . PHP_EOL;
    echo 'POST' . PHP_EOL;    
	var_dump($_POST);
?>
</body>
</html>