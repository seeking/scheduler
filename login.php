<?php
	// The People's Glorious Scheduler
	// Hacked by Ming Chow
	// Last updated on January 8, 2011

	session_start();
	require_once("dblib.php");
	$access = getLogin($_POST['login'], $_POST['password']);
	if (!empty($access)) {
		$_SESSION['login'] = $access['login'];
		if ($access['admin'] == 1) {
			$_SESSION['admin'] = $access['login'];
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
