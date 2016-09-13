<?php
session_start();
require('connection.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>This is The Wall</title>
</head>

<style type="text/css">
	* {
		margin: 0px;
		padding: 0px;
	}
	#mainBar {
		border-bottom: 1px solid black;
		width: 800px;
	}
	#mainBar > *{
		display: inline-block;
		padding: 10px;
	}
	#logo {
		font-weight: bold;
		width: 480px;
	}
	#welcome {
		width: 200px;
	}
	.posting {
		border: 1px solid black;
		height: 100px;
		width: 600px;
	}
	.button {
		margin-left: 500px;
	}
	#mainPost {
		width: 700px;
		margin: 30px;
	}
	#postList {
		width: 700px;
		margin: 30px;
	}
</style>

<body>

<!-- header -->
	<div id="mainBar">
		<div id="logo"><h2>CodingDojo Wall</h2></div>
		<div id="welcome"><h3>Welcome <?= $_SESSION['first_name']; ?>!</h3></div>
		<form action="process.php" method="post"><input type="hidden" name="action" value="logout">
		<input type="submit" value="logout"></form>
	</div>

<!-- post a message -->
	<div id="mainPost">
	<h3>Post a message:</h3>
	<form action="process.php" method="post">
		<input type="hidden" name="action" value="postMessage">
		<textarea rows="5" cols="100" type="text" name="postMessage"></textarea>
		<p><input class="button" type="submit" value="Post a message"></p>
	</form>
		<?php
		if(isset($_SESSION['posted_message'])) {
			echo "<p>{$_SESSION['posted_message']} </p>";
			unset($_SESSION['posted_message']);
		}
		?>
	</div>

<!-- list of posts and comments -->
	<div id=postList>
	<?php
		$query = "SELECT users.id, messages.id mid, users.first_name, messages.created_at, message FROM messages JOIN users ON users.id=messages.users_id WHERE users.id=" . $_SESSION['user_id'] . " ORDER BY messages.created_at DESC;";
		$posts = fetch_all($query);
		foreach ($posts as $post) {
			echo $post['first_name'] . " said on " . $post['created_at'] . ": " . $post['message'] . "<br>";
			$query2 = "SELECT users.first_name, comments.created_at, comment FROM comments JOIN messages ON messages.id=comments.messages_id JOIN users ON users.id=messages.users_id WHERE messages.id=" . $post['mid'] . " ORDER BY comments.created_at ASC;";
			$comments = fetch_all($query2);
			foreach ($comments as $comment) {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $comment['first_name'] . " commented at " . $comment['created_at'] . " on this: " . $comment['comment'] . "<br>";
			}
			?>
			<p class=green>post a comment response:</p>
			<form action="process.php" method="post">
				<input type="hidden" name="action" value="postComment">
				<input type="hidden" name="whichMessage" value="<?= $post['mid'] ?>">
				<textarea rows="3" cols="100" type="text" name="postComment"></textarea>
				<p><input class="button" type="submit" value="Post a comment"></p>
			</form>
			<br><br>
	<?php
		}
	?>
	</div>

</body>
</html>