<?php
	include "init.php";
	checkLogin();
	if (isset($_POST["title"])) {
		savePost($_POST["title"], $_POST["tags"], $_POST["content"]);
	}
	include "header.php";
?>
<h1>new</h1>
<form method="post">
	<p>
		<label>
			Title
			<input type="text" name="title">
		</label>
	</p>
	<p>
		<label>
			Hobby
			<select name="tags">
<?php
	$results = DB::query("SELECT title, slug FROM categories");
	foreach ($results as $row) {
?>
	<option value="<?php echo $row["slug"] ?>"><?php echo $row["title"] ?></option>
<?php
	}
?>
			</select>
		</label>
	</p>
	<p>
		<label>
			Content
			<textarea name="content"></textarea>
		</label>
	</p>
	<p>
		<button type="submit">Save</button>
	</p>
</form>