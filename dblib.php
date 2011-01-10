<?php
	// The People's Glorious Scheduler
	// Hacked by Ming Chow
	// Last updated on January 10, 2011
	
	// Hacking attempted?
	if (!isset($_SERVER['HTTP_REFERER'])) {
		header("Location: index.php");
		exit;
	}
	
	$myUserName = '';
	$myPassword = '';
	$myDBName = '';
	$myHost = '';
	$db = mysql_connect($myHost, $myUserName, $myPassword);
	if (!$db) {
		die('Cannot connect to the database: ' . mysql_error());
	}
	else {
		mysql_select_db($myDBName) or die("Unable to select database");
	}

	function getDB()
	{
		global $db;
		return $db;
	}

	function getLogin ($login, $password)
	{
		$row = array();
		global $db;
		$result = mysql_query("SELECT login, admin FROM users WHERE login = '" . mysql_real_escape_string($login) . "' AND password = SHA1('" . mysql_real_escape_string($password) . "')");
		if (!result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			die($message);
		}
		else {
			$row = mysql_fetch_assoc($result);
		}
		mysql_free_result($result);
		return $row;
	}
	
	function getTimes()
	{
		global $db;
		$results = array();
		$result = mysql_query("SELECT time_id, time_descr, instructor, block_id FROM schedule_times ORDER BY time_id");
		if (!result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			die($message);
		}
		else {
			while ($row = mysql_fetch_assoc($result)) {
				array_push($results, $row);
			}
		}
		mysql_free_result($result);
		return $results;
	}
	
	function getBlocks()
	{
		global $db;
		$results = array();
		$result = mysql_query("SELECT block_id FROM schedule_blocks ORDER BY block_id");
		if (!result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			die($message);
		}
		else {
			while ($row = mysql_fetch_assoc($result)) {
				array_push($results, $row);
			}
		}
		mysql_free_result($result);
		return $results;
	}

	function getTimesByBlock ($block)
	{
		global $db;
		$results = array();
		$result = mysql_query("SELECT time_id, time_descr, instructor FROM schedule_times WHERE block_id = $block ORDER BY time_id");
		if (!result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			die($message);
		}
		else {
			while ($row = mysql_fetch_assoc($result)) {
				array_push($results, $row);
			}
		}
		mysql_free_result($result);
		return $results;
	}
		
	function insertVotes ($login, $theForm, $times)
	{
		// Precondition: Assume $theForm is legitimate
		global $db;
		mysql_query("UPDATE vote_sessions SET active = 0 WHERE login = '" . mysql_real_escape_string($login) . "'");
		$numTimes = count($times);
		mysql_query("INSERT INTO vote_sessions (login, voted_on) VALUES ('$login', NOW())");
		$voteSessionID = mysql_insert_id();
		for ($count = 1; $count <= $numTimes; $count++) {
			$label = "time$count";
			$val = intval($theForm[$label]);
			if ($val > 0) {
				mysql_query("INSERT INTO bids (vote_session_id, time_id, num_shillings) VALUES ($voteSessionID, $count, $val)");
			}
			$label = "veto$count";
			if (isset($theForm[$label])) {
				mysql_query("INSERT INTO vetos (vote_session_id, time_id) VALUES ($voteSessionID, $count)");
			}
		}
	}
	
	function getBidsForTime ($time)
	{
		global $db;
		$results = array();
		$result = mysql_query("SELECT s.login, b.num_shillings, DATE_FORMAT(s.voted_on, '%l/%d/%Y %h:%i:%s %p') AS voted_on FROM vote_sessions s INNER JOIN bids b ON (s.vote_session_id = b.vote_session_id) WHERE s.active = 1 AND b.time_id = $time ORDER BY s.login");
		if (!result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			die($message);
		}
		else {
			while ($row = mysql_fetch_assoc($result)) {
				array_push($results, $row);
			}
		}
		mysql_free_result($result);
		return $results;
	}
	
	function getVetosForTime ($time)
	{
		global $db;
		$results = array();
		$result = mysql_query("SELECT s.login, DATE_FORMAT(s.voted_on, '%l/%d/%Y %h:%i:%s %p') AS voted_on FROM vote_sessions s INNER JOIN vetos v ON (s.vote_session_id = v.vote_session_id) WHERE s.active = 1 AND v.time_id = $time ORDER BY s.login");
		if (!result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			die($message);
		}
		else {
			while ($row = mysql_fetch_assoc($result)) {
				array_push($results, $row);
			}
		}
		mysql_free_result($result);
		return $results;
	}

	function checkAdmin ($login)
	{
		$admin = false;
		global $db;
		$result = mysql_query("SELECT * FROM users WHERE login = '" . mysql_real_escape_string($login) . "' AND admin = 1 ");
		if (!result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			die($message);
		}
		else {
			$row = array();
			$row = mysql_fetch_array($result);
			if (!empty($row)) {
				$admin = true;
			}
		}
		mysql_free_result($result);
		return $admin;
	}
?>
