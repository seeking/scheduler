<?php
	// The People's Glorious Scheduler
	// Hacked by Ming Chow
	// Last updated on January 10, 2011
	
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
?>

<!DOCTYPE html>

<html>

<head>
<title>The People's Glorious Scheduler</title>
<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/> 
<link href="default.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("p#bids").click(function() {
			$("div#bid_results").slideToggle('slow');
		});
		$("p#vetos").click(function() {
			$("div#veto_results").slideToggle('slow');
		});
	});
</script>
</head>

<body>

<h3 class="rt"><a href="logout.php">Logout</a></h3>
<h2>Hello <?= $_SESSION['login'] ?> (Administrator)!</h2>

<p><a href="vote.php">View Scheduling</a></p>

<p id="bids"><a href="#">View Bids</a></p>
<div id="bid_results">
<?php
	$times = getTimes();
	foreach ($times as $b) {
		echo "<h4>Time " . $b['time_id']. ": " . $b['time_descr'] . " (" . $b['instructor'] . "; Block " . $b['block_id'] . ")</h4>\n";		$results = getBidsForTime($b['time_id']);
		$sum = 0;
		if (!empty($results)) {
			echo "<table class=\"results\"><tr><th>Login</th><th>shillings Bid</th><th>Date Voted</th>\n";
			foreach ($results as $r) {
				echo "<tr><td>" . $r['login'] . "</td>\n<td>" . $r['num_shillings'] . "</td>\n<td>" . $r['voted_on'] . "</td></tr>\n";
				$sum += $r['num_shillings'];
			}
			echo "</table>\n";
			echo "<p>Total number of shillings used for this time: $sum</p>\n";
		}
		else {
			echo "<ul><li>Nothing</li></ul>\n";
		}
	}
?>
</div>

<p id="vetos"><a href="#">View Vetos</a></p>
<div id="veto_results">
<?php
	foreach ($times as $b) {
		echo "<h4>Time " . $b['time_id']. ": " . $b['time_descr'] . " (" . $b['instructor'] . "; Block " . $b['block_id'] . ")</h4>\n";
		$results = getVetosForTime($b['time_id']);
		$sum = 0;
		if (!empty($results)) {
			echo "<table class=\"results\"><tr><th>Login</th><th>Date Voted</th>\n";
			foreach ($results as $r) {
				echo "<tr><td>" . $r['login'] . "</td>\n<td>" . $r['voted_on'] . "</td></tr>\n";
				$sum += 1;
			}
			echo "</table>\n";
			echo "<p>Total number of vetos for this time: $sum</p>\n";
		}
		else {
			echo "<ul><li>Nothing</li></ul>\n";
		}
	}
?>
</div>
</div>

<?php
	require_once('footer.php');
?>
