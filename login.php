<?php
	// The People's Glorious Scheduler
	// Hacked by Ming Chow
	// Last updated on January 17, 2011

	session_start();
	require_once("dblib.php");
	$access = getLogin($_POST['login'], $_POST['password']);
	if (!empty($access)) {
		$_SESSION['login'] = $access['login'];
		if ($access['needsnr'] == 1) {
			$_SESSION['needsnr'] = 1;
		}
		else {
			$_SESSION['needsnr'] = 0;
		}
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
