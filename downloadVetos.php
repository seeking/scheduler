<?php
	// The People's Glorious Scheduler
	// Hacked by Ming Chow
	// Last updated on January 20, 2011
	
	session_start();
	if (!isset($_SESSION['login']) && !isset($_SESSION['admin'])) {
		header("Location: index.php");
		exit;
	}
	
	require_once('dblib.php');
	
	// A triple-check for same-domain attack
	unset($_SESSION['admin']);
	$admin = checkAdmin($_SESSION['login']);
	if ($admin) {
		$_SESSION['admin'] = $_SESSION['login'];
	}
	else {
		header("Location: index.php");
		exit;
	}
	header('Content-type: text/csv');
	header('Content-disposition: attachment;filename=vetos.csv');
	$times = getTimes();
	foreach ($times as $b) {
		$slot = "Time " . $b['time_id']. ": " . $b['time_descr'] . " (" . $b['instructor'] . "; Block " . $b['block_id'] . ")";
		$results = getVetosForTime($b['time_id']);
		$sum = 0;
		if (!empty($results)) {
			foreach ($results as $r) {
				echo '"' . $r['login'] . '",' . $b['time_id'] . ',"' . $slot . "\"\n";
			}
		}
	}
?>
