<?php
	// The People's Glorious Scheduler
	// Hacked by Ming Chow
	// Last updated on January 8, 2011
	
	session_start();
	if (isset($_SESSION['login'])) {
		header('Location:vote.php');
		exit;
	}
	require_once('header.php');
	if (isset($_GET['error'])) {
		echo '<h3 class="error">Login incorrect!</h3>';
	}
	elseif (isset($_GET['logout'])) {
		echo '<h3>You have successfully logged out!</h3>';
	}
?>
<noscript>
	<h3>You do not have JavaScript enabled.  Good luck with that.</h3>
</noscript>

<form id="login" method="post" action="login.php">
<p>Login<br/><input type="text" id="login" name="login" /></p>
<p>Password<br/><input type="password" id="password" name="password"/></p>
<p><input type="submit" value="Submit" id="submit"> <input type="reset" id="reset"></p>
</form>

<?php
	require_once('footer.php');
?>
