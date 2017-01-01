<?php
	include "init.php";
	if (isset($_SESSION["username"])) {
		header("Location: index.php");
	}
	if (isset($_POST["username"])) {
		signUp($_POST["username"], $_POST["password"], $_POST["email"], $_POST["name"]);
	}
?>
<form method="post">
	<p>
		<label>
			Email: 
			<input type="email" name="email">
		</label>
	</p>
	<p>
		<label>
			Name: 
			<input type="text" name="name">
		</label>
	</p>
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
	</p>
</form>