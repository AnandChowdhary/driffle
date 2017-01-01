<?php
	include "init.php";
	include "header.php";
	if (isset($_POST["name"])) {
		createCategory($_POST["name"]);
		header("Location: " . site_url() . "hobby");
	}
?>
<?php
	if (isset($_GET["slug"])) {
		if ($_GET["slug"] == "new") {
?>
<h1>create hobby</h1>
<form method="post">
	<p>
		<label>
			Name: 
			<input type="text" name="name">
		</label>
	</p>
	<p>
		<button type="submit">Create</button>
	</p>
</form>
<?php
		} elseif ($_GET["slug"] == "all") {
?>
<h1>all hobbies</h1>
<?php archive(); ?>
<?php
		} else {
?>
<h1><?php echo $_GET["slug"]; ?></h1>
<?php archive("category", $_GET["slug"]); ?>
<?php
		}
	} else {
?>
<h1>hobbies</h1>
<ul>
	<li style="font-weight: bold"><a href="<?php echo site_url(); ?>hobby/new">create</a></li>
	<li style="font-weight: bold"><a href="<?php echo site_url(); ?>hobby/all">all</a></li>
<?php
	$results = DB::query("SELECT title, slug FROM categories");
	foreach ($results as $row) {
?>
	<li><a href="<?php echo site_url() . "hobby/" . $row["slug"] ?>"><?php echo $row["title"] ?></a></li>
<?php
	}
?>
</ul>
<?php } ?>