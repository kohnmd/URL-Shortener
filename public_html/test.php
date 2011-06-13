<?php include('../includes/initialize.php'); ?>
<?php
	
if(isset($_POST['submit'])) {
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	//// Check database to see if username/password exist.
	$found_user = User::authenticate($username, $password);
	
	pre_print(array($database->last_query, $found_user));
	
	if($found_user) {
		$message = "user exists";
	} else {
		// username/password combo was not found in the database
		$message = "user does NOT exist";
	}
	
	
} else {
	
	$message = "";
	
}
?>
<?php include('layout/header.php'); ?>

			<section id="sign_in" class="main_half centered">
				<header>
					<h2>sign in</h2>
					<?php if($message!="") { ?><h5 class="colorful">{ <?php echo $message; ?> }</h5><?php } ?>
				</header>
				<article>
					<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
						<div class="row"><label for="username">Username or Email</label><input type="text" name="username" id="username" /></div>
						<div class="row"><label for="password">Password</label><input type="password" name="password" id="password" /></div>
						<div class="row"><input type="submit" value="Sign In" name="submit" /></div>
					</form>
				</article>
			</section>

<?php include('layout/footer.php'); ?>