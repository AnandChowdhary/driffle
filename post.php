<?php
	include "init.php";
	$post = DB::queryFirstRow("SELECT * FROM posts WHERE slug=%s", $_GET["slug"]);
	include "header.php";
?>
<h1><?php echo $post["title"]; ?></h1>
<p>Posted by <?php echo $post["author"]; ?> on <?php echo $post["postedon"]; ?> tagged <?php echo $post["tags"]; ?>.</p>
<div><?php echo $post["content"]; ?></div>