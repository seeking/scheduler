<?php
	// The People's Glorious Scheduler
	// Hacked by Ming Chow
	// Last updated on January 8, 2011
	
	session_start();
	if (!isset($_SESSION['login'])) {
		header("Location: index.php");
		exit;
	}
	
	require_once('dblib.php');

	$blocks = getBlocks();
	$numBlocks = count($blocks);
	$maxSchillings = 1000;
	$maxVetos = 2;
?>

<!DOCTYPE html>

<html>

<head>
<title>The People's Glorious Scheduler</title>
<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/> 
<link href="default.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript">
	var numBlocks = <?= $numBlocks?>;
	var maxSchillings = <?= $maxSchillings?>;
	var maxVetos = <?= $maxVetos?>;
	var numSchillings;
	var numVetos;
	
	function initCurrency()
	{
		numSchillings = maxSchillings;
		numVetos = maxVetos;
		document.getElementById("currency").innerHTML = "<p>You have " + numSchillings + " schillings.</p></p>You have " + numVetos + " vetos.</p>";
	}
	
	function updateCurrency()
	{
		numVetos = maxVetos;
		numSchillings = maxSchillings
		currency = document.getElementById("currency");
		for (i = 1; i <= numBlocks; i++) {
			elemName = 'block' + i;
			elem = document.getElementById(elemName).value;
			value = parseInt(elem);
			if (!isNaN(value)) {
				numSchillings -= value;
			}
			elemName = 'veto' + i;
			if (document.getElementById(elemName).checked) {
				numVetos -= 1;
			}	
		}
		currency.innerHTML = "<p>You have " + numSchillings + " schillings.</p></p>You have " + numVetos + " vetos.</p>"
		if (numSchillings <= 0) {
			newElem = document.createElement("p");
			newElem.setAttribute("class", "error");
			newElem.appendChild(document.createTextNode("You used up all your schillings!"));
			currency.appendChild(newElem);
		}
		if (numVetos <= 0) {
			newElem = document.createElement("p");
			newElem.setAttribute("class", "error");
			newElem.appendChild(document.createTextNode("You used up all your vetos!"));
			currency.appendChild(newElem);
		}
	}
	
	function checkSubmission()
	{
		submit = true;
		if (numSchillings < 0 || numVetos < 0) {
			submit = false;
			alert("Sorry, but you used too many schillings or vetos!");
		}
		else if (numSchillings > 0) {
			submit = confirm("You have unspent schillings.  Are you sure you want to submit?");
		}
		if (submit) {
			document.forms['vote'].submit();
		}
	}
</script>
</head>

<body onload="initCurrency()">

<h3 class="rt"><a href="logout.php">Logout</a></h3>

<h2>Hello <?= $_SESSION['login'] ?>!</h2>

<?php
	$errors = false;
	$schillingsCheck = 0;
	$vetosCheck = 0;
	if (!empty($_POST)) {
		$schillingCount = 0;
		$vetoCount = 0;
		for ($count = 1; $count <= $numBlocks; $count++) {
			$label = "block$count";
			$schillingsCheck += intval($_POST[$label]);
			$label = "veto$count";
			if (isset($_POST[$label])) {
				$vetosCheck++;
			}
		}
		if ($schillingsCheck < 0 || $schillingsCheck > $maxSchillings) {
			$errors = true;
		}
		if ($votesCheck < 0 || $votesCheck > $maxVotes) {
			$errors = true;
		}
	}
	if (!empty($_POST) && !$errors) {
		insertVotes($_SESSION['login'], $_POST, $blocks);
		echo '<p>Thanks for your submission.  You can <a href="logout.php">log out</a> now.</p>';
	}
	else {
		
?>

<iframe src="http://www.cs.tufts.edu/~nr/comp105/section.html" width="100%" height="100" /></iframe>

<div id="currency"></div>

<h4>For each block, enter the number of schillings that you would like to spend on it, or veto it.</h4>
<form id="vote" action="vote.php" method="post" onreset="initCurrency()">
<table>
<?php

	foreach ($blocks as $b) {
		print '<tr><td>Block '. $b['block_id'] . ': ' . $b['time_descr'] . ' (' . $b['instructor']. ') </td><td><input type="text" name="block' . $b['block_id'] . '" id="block' . $b['block_id'] . '" size="5" value="0" onchange="updateCurrency()" /></td><td>Veto <input type="checkbox" name="veto' . $b['block_id'] . '" id="veto' . $b['block_id'] . '" onchange="updateCurrency()" /></td></tr></p>';
	}
?>
</table>
<p><button type="button" onclick="checkSubmission()">Submit</button> <input type="reset" /></p>
</form>

<?php
	}
	require_once('footer.php');
?>
