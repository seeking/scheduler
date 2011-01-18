<?php
	// The People's Glorious Scheduler
	// Hacked by Ming Chow
	// Last updated on January 17, 2011
	
	session_start();
	if (!isset($_SESSION['login'])) {
		header("Location: index.php");
		exit;
	}
	
	require_once('dblib.php');

	$blocks = getBlocks();
	$times = getTimes();
	$numBlocks = count($blocks);
	$numTimes = count($times);
	$maxShillings = 1000;
	$maxVetos = 2;
?>

<!DOCTYPE html>

<html>

<head>
<title>The People's Glorious Scheduler</title>
<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/> 
<link href="default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	var numBlocks = <?= $numBlocks?>;
	var numTimes = <?= $numTimes?>;
	var maxShillings = <?= $maxShillings?>;
	var maxVetos = <?= $maxVetos?>;
	var numShillings;
	var numVetos;
	
	function initCurrency()
	{
		numShillings = maxShillings;
		numVetos = maxVetos;
		document.getElementById("currency").innerHTML = "<p>You have " + numShillings + " shillings.</p></p>You have " + numVetos + " vetos.</p>";
	}
	
	function updateCurrency()
	{
		numVetos = maxVetos;
		numShillings = maxShillings;
		
		currency = document.getElementById("currency");
		for (i = 1; i <= numTimes; i++) {
			elemName = 'time' + i;
			try {
				elem = document.getElementById(elemName).value;
				value = parseInt(elem);
				if (!isNaN(value)) {
					if (value >= 0) {
						numShillings -= value;
					}
					else {
						document.getElementById(elemName).value = 0;
					}
				}
			}
			catch (e) {}
		}
		for (i = 1; i <= numBlocks; i++) {
			okay = false;
			elemName = 'block' + i;
			try {
				elems = document.getElementById(elemName).getElementsByClassName("veto");
				j = 0;
				while (!okay && j < elems.length) {
					if (elems[j].checked) {
						okay = true;
					}
					else {
						j++;
					}
				}
				if (okay) {
					numVetos -= 1;
				}
			}
			catch (e) {}
		}
		currency.innerHTML = "<p>You have " + numShillings + " shillings.</p></p>You have " + numVetos + " vetos.</p>"
		if (numShillings <= 0) {
			newElem = document.createElement("p");
			newElem.setAttribute("class", "error");
			newElem.appendChild(document.createTextNode("You used up all your shillings!"));
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
		if (numShillings < 0 || numVetos < 0) {
			submit = false;
			alert("Sorry, but you used too many shillings or vetos!");
		}
		else if (numShillings > 0) {
			submit = confirm("You have unspent shillings.  Are you sure you want to submit?");
		}
		if (submit) {
			document.forms['votefrm'].submit();
		}
	}
</script>
</head>

<body onload="initCurrency()">

<h3 class="rt"><a href="logout.php">Logout</a></h3>

<h2>Hello <?= $_SESSION['login'] ?>!</h2>

<?php
	$errors = false;
	$shillingsCheck = 0;
	$vetosCheck = 0;
	if (!empty($_POST)) {
		$schillingCount = 0;
		//$vetoCount = 0;
		for ($count = 1; $count <= $numTimes; $count++) {
			$label = "time$count";
			$val = intval($_POST[$label]);
			if ($val >= 0) {
				$shillingsCheck += $val;
			}
			/*$label = "veto$count";
			if (isset($_POST[$label])) {
				$vetosCheck++;
			}*/
		}
		if ($shillingsCheck < 0 || $shillingsCheck > $maxShillings) {
			$errors = true;
		}
		/*if ($votesCheck < 0 || $votesCheck > $maxVotes) {
			$errors = true;
		}*/
	}
	if (!empty($_POST) && !$errors) {
		insertVotes($_SESSION['login'], $_POST, $times);
		echo '<p>Thanks for your submission.  You can <a href="logout.php">log out</a> now.</p>';
	}
	else {
		
?>

<iframe src="http://www.cs.tufts.edu/~nr/comp105/section.html" width="100%" height="100" /></iframe>

<div id="currency"></div>

<h4>For each time, enter the number of shillings that you would like to spend on it, or veto it.</h4>
<form id="votefrm" action="vote.php" method="post" onreset="initCurrency()">
<?php
	foreach ($blocks as $b) {
		echo "<div id=\"block" . $b['block_id'] . "\">\n";
		$timesByBlock = getTimesByBlock($b['block_id'], $_SESSION['needsnr']);
		echo "<table class=\"blocktbl\">\n";
		foreach ($timesByBlock as $t) {
			echo '<tr><td class="timeslot">Time '. $t['time_id'] . ': ' . $t['time_descr'] . ' (Block ' . $b['block_id'] . ')</td><td class="timebox"><input type="text" name="time' . $t['time_id'] . '" id="time' . $t['time_id'] . '" size="5" value="0" onchange="updateCurrency()" /></td><td class="vetobox">Veto <input type="checkbox" name="veto' . $t['time_id'] . '" class="veto" id="veto' . $t['time_id'] . '" onchange="updateCurrency()" /></td></tr>';
		}
		print "</table>\n";
		echo "</div>\n";
	}
?>
<p><button type="button" onclick="checkSubmission()">Submit</button> <input type="reset" /></p>
</form>

<?php
	}
	require_once('footer.php');
?>
