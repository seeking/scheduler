<?php
	// The People's Glorious Scheduler
	// Hacked by Ming Chow
	// Last updated on January 8, 2011

	session_start();
	require_once("dblib.php");
	$access = getLogin($_POST['login'], $_POST['password']);
	if ($access != false) {
		$_SESSION['login'] = $access;
		$admin = checkAdmin($access);
		if ($admin) {
			$_SESSION['admin'] = $access;
			header("Location: admin.php");
			exit;
		}
		else {
			header("Location: vote.php");
			exit;
		}
	}
	else {
		header("Location: index.php?error");
		exit;
	}
?>
