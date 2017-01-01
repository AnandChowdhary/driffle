<?php
	include "init.php";
	if (isset($_SESSION["username"])) {
		header("Location: index.php");
	}
	if (isset($_POST["username"])) {
		logIn($_POST["username"], $_POST["password"]);
	}
?>
<form method="post">
	<p>
		<label>
			Username: 
			<input type="text" name="username">
		</label>
	</p>
	<p>
		<label>
			Password: 
			<input type="password" name="password">
		</label>
	</p>
	<p>
		<button type="submit">Log in</button>
		<a href="./register">Register</a>
	</p>
</form>