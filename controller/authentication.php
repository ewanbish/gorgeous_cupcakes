<?php
	//start session management
	session_start();
	//connect to the database
	require('../model/database.php');
	//retrieve the functions
	require('../model/functions_users.php');
	
	//retrieve the username and password entered into the form
	$username = $_POST['username'];
	$password = $_POST['password']; 
	
	//call the retrieve_salt() function
	$result = retrieve_salt($username);
	
	if (!$result) {
    $_SESSION['error'] = 'User does not exist.';
    header('location:../view/login_form.php');
    exit();
	}

	//retrieve the random salt from the database
	$salt = $result['salt'];
	//generate the hashed password with the salt value
    $password = hash('sha256', $password.$salt); 
	
	//call the login() function
	$count = login($username, $password);
	
	//if there is one matching record
	if($count == 1)
{ 
    $_SESSION['user'] = $username;
    $_SESSION['success'] = 'Hello ' . $username . '. Have a great day!';
    header('location:../view/products.php');
    exit(); // 👈 add this line
}
else
{
    $_SESSION['error'] = 'Incorrect username or password. Please try again.';
    header('location:../view/login_form.php');
    exit(); // 👈 and this one
}
?>