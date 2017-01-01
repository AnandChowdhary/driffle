<style>
body { max-width: 960px; margin: 100px auto }
</style>
<div style="margin-bottom: 100px">
<strong>driffle</strong><br>
<a href="<?php echo site_url(); ?>">home</a> |
<a href="<?php echo site_url(); ?>new">new</a> |
<a href="<?php echo site_url(); ?>hobby">hobbies</a> |
<a href="<?php echo site_url(); ?>profile/<?php echo $_SESSION["username"]; ?>">profile</a> |
<a href="<?php echo site_url(); ?>logout">logout</a>
<br>Popular users:
<?php
$results = DB::query("SELECT username FROM user ORDER BY followers DESC LIMIT 5");
foreach ($results as $row) {
	echo "<a href='" . site_url() . "profile/" . $row["username"] . "'>" . $row["username"] . "</a> | ";
}
?>
</div>