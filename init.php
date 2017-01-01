<?php

	session_start();

	require_once "class.php";
	DB::$user = "anand";
	DB::$password = "";
	DB::$dbName = "driffle";

	function site_url() {
		return "http://localhost:8888/driffle/";
	}

	function archive($type, $a, $b) {
		if ($type == "author") {
			$results = DB::query("SELECT slug, postedon, content, title FROM posts WHERE author=%s", $a);
		} elseif ($type == "category") {
			$results = DB::query("SELECT slug, postedon, content, title FROM posts WHERE tags=%s", $a);
		} elseif ($type == "both") {
			$results = DB::query("SELECT slug, postedon, content, title FROM posts WHERE author=%s AND category=%s", $a, $b);
		} else {
			$results = DB::query("SELECT slug, postedon, content, title FROM posts");
		}
		foreach ($results as $row) {
			if (substr_count($row["content"], " ") > 99) {
				$short = implode(" ", array_slice(explode(" ", $row["content"]), 0, 100));
				$short .= "&hellip;";
			} else {
				$short = $row["content"];
			}
		?>
			<article>
				<h3><?php echo $row["title"]; ?></h3>
				<div><?php echo $short; ?></div>
				<p><a href="<?php echo site_url(); ?>post/<?php echo $row["slug"]; ?>">Continue Reading</a></p>
			</article>
		<?php }
	}

	function followUser($user) {
		// For account you're going to follow
		$account = DB::queryFirstRow("SELECT followers, listfollowers FROM user WHERE username=%s", $user);
		$followers = intval($account["followers"]);
		if ($account["listfollowers"] == "[]") {
			$listFollowers = array();
		} else {
			$listFollowers = unserialize($account["listfollowers"]);
		}
		array_push($listFollowers, $_SESSION["username"]);
		DB::update("user", array(
			"followers" => ($followers + 1),
			"listfollowers" => serialize($listFollowers)
		), "username=%s", $user);
		// For your account
		$account = DB::queryFirstRow("SELECT following, listfollowing FROM user WHERE username=%s", $_SESSION["username"]);
		$followers = intval($account["following"]);
		if ($account["listfollowing"] == "[]") {
			$listFollowers = array();
		} else {
			$listFollowers = unserialize($account["listfollowing"]);
		}
		array_push($listFollowers, $user);
		DB::update("user", array(
			"following" => ($followers + 1),
			"listfollowing" => serialize($listFollowers)
		), "username=%s", $_SESSION["username"]);
	}

	function unfollowUser($user) {
		// For account you're going to follow
		$account = DB::queryFirstRow("SELECT followers, listfollowers FROM user WHERE username=%s", $user);
		$followers = intval($account["followers"]);
		if ($account["listfollowers"] == "[]") {
			$listFollowers = array();
		} else {
			$listFollowers = unserialize($account["listfollowers"]);
		}
		if (($key = array_search($_SESSION["username"], $listFollowers)) !== false) {
			unset($listFollowers[$key]);
		}
		DB::update("user", array(
			"followers" => ($followers - 1),
			"listfollowers" => serialize($listFollowers)
		), "username=%s", $user);
		// For your account
		$account = DB::queryFirstRow("SELECT following, listfollowing FROM user WHERE username=%s", $_SESSION["username"]);
		$followers = intval($account["following"]);
		if ($account["listfollowing"] == "[]") {
			$listFollowers = array();
		} else {
			$listFollowers = unserialize($account["listfollowing"]);
		}
		if (($key = array_search($user, $listFollowers)) !== false) {
			unset($listFollowers[$key]);
		}
		DB::update("user", array(
			"following" => ($followers - 1),
			"listfollowing" => serialize($listFollowers)
		), "username=%s", $_SESSION["username"]);
	}

	function checkLogin() {
		if (!isset($_SESSION["username"])) {
			header("Location: ./login");
		}
	}

	function savePost($title, $tags, $content) {
		$slug = strtolower(str_replace(" ", "-", $title));
		$letters = "QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890";
		$letters = str_shuffle($letters);
		$slug .= ("-" . substr($letters, 0, 6));
		DB::insert("posts", array(
			"slug" => $slug,
			"author" => $_SESSION["username"],
			"title" => $title,
			"tags" => $tags,
			"content" => $content,
			"postedon" => date("Y-m-d h:i:sa")
		));
		header("Location: " . site_url() . "post/" . $slug);
	}

	function logIn($username, $password) {
		$account = DB::queryFirstRow("SELECT password FROM user WHERE username=%s", $username);
		if ($account["password"] == md5($password)) {
			$_SESSION["username"] = $username;
			header("Location: ./");
		}
	}

	function signUp($username, $password, $email) {
		DB::query("SELECT * FROM user WHERE email=%s", $email);
		$counter = DB::count();
		if ($counter > 0) {
			header("Location: ./register?emailExists");
		} else {
			DB::query("SELECT * FROM user WHERE username=%s", $username);
			$counter = DB::count();
			if ($counter > 0) {
				header("Location: ./register?usernameExists");
			} else {
				DB::insert("user", array(
					"username" => $username,
					"password" => md5($password),
					"name" => $name,
					"email" => $email
				));
				$_SESSION["username"] = $username;
				header("Location: ./");
			}
		}
	}

	function createCategory($name) {
		$slug = str_replace(" ", "-", strtolower($name));
		DB::insert("categories", array(
			"slug" => $slug,
			"title" => $name
		));
	}

	function logOut() {
		session_unset();
		session_destroy();
		header("Location: ./");
	}

	function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

?>