<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Enter the Wall</title>
</head>
<style type="text/css">
	* {
		font-family: sans-serif;
	}
	.mainBox {
		width: 300px;
		height: 200px;
		border: 1px solid gray;
		display: inline-block;
		vertical-align: top;
		padding: 10px;
	}
	.error {
		color: red;
	}
	.success {
		color: green;
	}
</style>
<body>

<!-- registration -->
	<div class="mainBox">
		<h2>Registration:</h2>
		<form action="process.php" method="post">
			<input type="hidden" name="action" value="register">
			First name: <input type="text" name="first_name"><br>
			Last name: <input type="text" name="last_name"><br>
			Email address: <input type="text" name="email"><br>
			Password: <input type="password" name="password"><br>
			Confirm Password: <input type="password" name="confirm_password"><br>
			<input type="submit" value="register">
		</form>
	</div>

<!-- login -->
	<div class="mainBox">
		<h2>Login:</h2>
		<form action="process.php" method="post">
			<input type="hidden" name="action" value="login">
			Email address: <input type="text" name="email"><br>
			Password: <input type="password" name="password"><br>
			<input type="submit" value="login">
		</form>
	</div>

<!-- show errors (if any) -->
	<div>
		<?php
		if(isset($_SESSION['errors'])) {
			foreach ($_SESSION['errors'] as $error) {
				echo "<p class='error'>{$error} </p>";
			}
			unset($_SESSION['errors']);
		}
		if(isset($_SESSION['success_message'])) {
			echo "<p class='success'>{$_SESSION['success_message']} </p>";
			unset($_SESSION['success_message']);
		}
		?>
	</div>
	
</body>
</html>