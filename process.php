<?php
session_start();
require('connection.php');

// options for what the user could do
if(isset($_POST['action']) && $_POST['action'] == 'register') { // register
	register_user($_POST);
} else if(isset($_POST['action']) && $_POST['action'] == 'login') { // login
	login_user($_POST);
} else if(isset($_POST['action']) && $_POST['action'] == 'logout') { // logout
	session_destroy();
	header('location: index.php');
	die();
} else if(isset($_POST['action']) && $_POST['action'] == 'postMessage') { // post messages
	input_post($_POST);
	header('location: wall.php');
} else if(isset($_POST['action']) && $_POST['action'] == 'postComment') { // post comment
	input_comment($_POST);
	header('location: wall.php');
}

// to send post to database
	function input_post($post) {
		$query = "INSERT INTO messages (message, created_at, users_id) VALUES ('{$post['postMessage']}', NOW(), '{$_SESSION['user_id']}')";
		run_mysql_query($query);
		$_SESSION['posted_message'] = 'Post successfully posted!';
	}

// to send comment to database
	function input_comment($post) {
		$query = "INSERT INTO comments (comment, created_at, users_id, messages_id) VALUES ('{$post['postComment']}', NOW(), '{$_SESSION['user_id']}', '{$post['whichMessage']}')";
		run_mysql_query($query);
	}

// to validate registration info
	function register_user($post) {
		$_SESSION['errors'] = array();
		if(empty($post['first_name'])) {
			$_SESSION['errors'][] = "First name can't be blank!";
		}
		if(empty($post['last_name'])) {
			$_SESSION['errors'][] = "Last name can't be blank!";
		}
		if(empty($post['password'])) {
			$_SESSION['errors'][] = "Password can't be blank!";
		}
		if($post['password'] !== $post['confirm_password']) {
			$_SESSION['errors'][]= "Passwords must match!";
		}
		if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
			$_SESSION['errors'][]= "Please use a valid email!";
		if(count($_SESSION['errors']) > 0) {
			header('location: index.php');
			die();
		} else {
			$query = "INSERT INTO users (first_name, last_name, password, email, created_at, updated_at) VALUES ('{$post['first_name']}', '{$post['last_name']}', '{$post['password']}', '{$post['email']}', NOW(), NOW())";
			run_mysql_query($query);
			$_SESSION['success_message'] = 'User successfully created!';
			header('location: index.php');
			die();
		}
	}

// to validate login in database
	function login_user($post) {
		$query = "SELECT * FROM users WHERE users.password = '{$post['password']}' AND users.email = '{$post['email']}'";
		$user = fetch_all($query);
		if(count($user) > 0) {
			$_SESSION['user_id'] = $user[0]['id'];
			$_SESSION['first_name'] = $user[0]['first_name'];
			$_SESSION['logged_in'] = TRUE;
			header('location: wall.php');
		} else {
			$_SESSION['errors'][] = "Can't find a user with those credentials";
			header('location: index.php');
		}
	}

?>