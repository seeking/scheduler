<?php
	// The People's Glorious Scheduler
	// Hacked by Ming Chow
	// Last updated on January 8, 2011
	
	$myUserName = 'webapps';
	$myPassword = 'iddqdiddqd';
	$myDBName = 'scheduling';
	$myHost = 'localhost';
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
		if (preg_match("/^[a-z0-9]{2,15}/", $login)) {
			return $login;
		}
		return false;
	}
	
	function getBlocks()
	{
		global $db;
		$results = array();
		$result = mysql_query("SELECT block_id, time_descr, instructor FROM schedule_blocks WHERE active = 1 ORDER BY block_id");
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
	
	function insertVotes ($login, $theForm, $blocks)
	{
		// Assume $theForm is legitimate
		global $db;
		mysql_query("UPDATE vote_sessions SET active = 0 WHERE login = '" . mysql_real_escape_string($login) . "'");
		$numBlocks = count($blocks);
		mysql_query("INSERT INTO vote_sessions (login, voted_on) VALUES ('$login', NOW())");
		$voteSessionID = mysql_insert_id();
		for ($count = 1; $count <= $numBlocks; $count++) {
			$label = "block$count";
			$val = intval($theForm[$label]);
			if ($val > 0) {
				mysql_query("INSERT INTO bids (vote_session_id, block_id, num_schillings) VALUES ($voteSessionID, $count, $val)");
			}
			$label = "veto$count";
			if (isset($theForm[$label])) {
				mysql_query("INSERT INTO vetos (vote_session_id, block_id) VALUES ($voteSessionID, $count)");
			}
		}
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
	
	function getBidsForBlock ($block)
	{
		global $db;
		$results = array();
		$result = mysql_query("SELECT s.login, b.num_schillings, DATE_FORMAT(s.voted_on, '%l/%d/%Y %h:%i:%s %p') AS voted_on FROM vote_sessions s INNER JOIN bids b ON (s.vote_session_id = b.vote_session_id) WHERE s.active = 1 AND b.block_id = $block ORDER BY s.login");
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
	
	function getVetosForBlock ($block)
	{
		global $db;
		$results = array();
		$result = mysql_query("SELECT s.login, DATE_FORMAT(s.voted_on, '%l/%d/%Y %h:%i:%s %p') AS voted_on FROM vote_sessions s INNER JOIN vetos v ON (s.vote_session_id = v.vote_session_id) WHERE s.active = 1 AND v.block_id = $block ORDER BY s.login");
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
?>
