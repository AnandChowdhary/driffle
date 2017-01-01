<?php
	include "init.php";
	$username = $_GET["username"];
	$profile = DB::queryFirstRow("SELECT followers, following, username, name, shortbio, listfollowers, listfollowing FROM user WHERE username=%s", $username);
	$counter = DB::count();
	if ($counter == 0) {
		header("Location: ../404");
	}
	if (isset($_POST["follow"])) {
		followUser($_POST["follow"]);
		header("Location: ?followed");
	}
	if (isset($_POST["unfollow"])) {
		unfollowUser($_POST["unfollow"]);
		header("Location: ?unfollowed");
	}
	include "header.php";
?>
<h1><?php echo $profile["name"]; ?></h1>
<h2>@<?php echo $profile["username"]; ?></h2>
<p><?php echo $profile["shortbio"]; ?></p>
<table style="width: 100%">
	<thead>
		<tr>
			<td style="width: calc(100%/3)">posts</td>
			<td style="width: calc(100%/3)">followers</td>
			<td style="width: calc(100%/3)">following</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php
				DB::query("SELECT id FROM posts WHERE author=%s", $profile["username"]);
				echo DB::count();
			?></td>
			<td><?php
				echo $profile["followers"];
				$list = $profile["listfollowers"];
				echo "<ul>";
				foreach (unserialize($list) as $key) {
					echo "<li>" . $key . "</li>";
				}
				echo "</ul>";
			?></td>
			<td><?php
				echo $profile["following"];
				$list = $profile["listfollowing"];
				echo "<ul>";
				foreach (unserialize($list) as $key) {
					echo "<li>" . $key . "</li>";
				}
				echo "</ul>";
			?></td>
		</tr>
	</tbody>
</table>
<?php if ($profile["username"] == $_SESSION["username"]) { ?>
<button>Edit</button>
<?php } else if (in_array($_SESSION["username"], unserialize($profile["listfollowers"]))) { ?>
<form method="post">
<input type="hidden" name="unfollow" value="<?php echo $profile["username"]; ?>">
<button type="submit">Unfollow</button>
</form>
<?php } else { ?>
<form method="post">
<input type="hidden" name="follow" value="<?php echo $profile["username"]; ?>">
<button type="submit">Follow</button>
</form>
<?php } ?>
<hr>
<?php
	archive("author", $profile["username"]);
?>