<?php
	// The People's Glorious Scheduler
	// Hacked by Ming Chow
	// Last updated on January 8, 2011
	
	session_start();
	if (isset($_SESSION['login'])) {
		session_destroy();
		header("Location: index.php?logout");
		exit;
	}
	header("Location: index.php");
?>
