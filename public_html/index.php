<?php include('../includes/initialize.php');

// REDIRECT
// On sucess:  will store the redirect stats, then redirect the user
// 			   to the appropriate redirect_url.
// On failure: will return false and the script will continue to execute.
Shortener::redirect();

// CREATE NEW
if(isset($_POST['create_new']) && $_POST['create_new'] == 'Shorten!' && strstr($_SERVER['HTTP_REFERER'], 'mdk.im')) { // [CHANGEME] (referer) //
	$shortener = new Shortener();
	$saved = $shortener->save();
	if($error_message!="") {
		$new_message = $error_message;
	}
}

?>
<!DOCTYPE HTML>
<html>

<head>
	<title>MDK's Awesome URL Shortener</title>
	<link rel="stylesheet" type="text/css" href="<?php directory_path(); ?>/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?php directory_path(); ?>/style.css" />
</head>

<body>
<div id="wrapper">

	<div id="header">
		<ul>
			<li><a href="http://www.rhymeswithmilk.com/" class="todo">back to rhymes with milk</a></li>
			<li><a href="http://sandbox.rhymeswithmilk.com/">back to the sandbox</a></li>
		</ul>
		<h1><a href="<?php directory_path(); ?>">MDK.IM</a></h1>
		<h4>(a place to shorten urls)</h4>
		<ul>
			<li><a href="<?php directory_path(); ?>/#">about</a></li>
			<li><a href="<?php directory_path(); ?>/#">view all shortened urls</a></li>
		</ul>
	</div> <!-- #header -->

	<div id="main">
		
		<div class="main-half">
			<?php 
			// if the form has been submitted and there are no error messages
			if((!isset($new_message) || empty($new_message)) && isset($_POST['create_new']) && $_POST['create_new']=='Shorten!' && strstr($_SERVER['HTTP_REFERER'], 'mdk.im')) : // [CHANGEME] (referer) //
			?>
		
				<div class="main-header">
					<h3 class="dark"><?php echo get_directory_path() . '/' . $shortener->short_url; ?></h3>
				</div>
				<div class="contents">
					<div class="row">
						<p class="light">redirects to <?php echo htmlentities($shortener->redirect_url); ?></p>
					</div>
					<div class="row">
						<p><a href="<?php directory_path(); ?>">shorten another</a></p>
					</div>
					
				</div><!-- .contents -->
				
			<?php
			else : // if the form wasn't submitted, or there was an error
			?>
				<div class="main-header">
					<h3>create new</h3>
					<?php if(isset($new_message) && !empty($new_message)) echo '<div class="row"><h5 class="colorful">{ ' . $new_message . ' }</h5></div>'; ?>
				</div>
				<div class="contents">
			
					<form method="post" action="/mdk.im/public_html/">
						<div class="row">
							<label for="redirect_url">url to shorten <span class="required">*</span></label>
							<input type="text" value="<?php if(isset($_POST['redirect_url'])) echo $_POST['redirect_url']; ?>" id="redirect_url" name="redirect_url" maxlength="256" required />
						</div>
						<div class="row">
							<label for="redirect_url">custom short url <span class="description">(leave blank for auto-generated url)</span></label>
							<span class="description">http://mdk.im/</span> <input type="text" value="<?php if(isset($_POST['short_url'])) echo $_POST['short_url']; ?>" id="short_url" name="short_url" maxlength="64" />
						</div>
						<div class="row">
							<input type="submit" value="Shorten!" name="create_new" />
						</div>
					</form>
				</div><!-- .contents -->
			
			<?php
			endif; // ends if the form was submitted and no errors exist
			?>
			
		</div> <!-- .main-half -->
		
		<div class="main-half">
			<div class="main-header">
				<h3>stats</h3>
				<?php if(isset($message) && !empty($message)) echo '<div class="row"><h5 class="colorful input-column">{ ' . $message . ' }</h5></div>'; ?>
			</div>
			<div class="contents">
				<form method="post" action="/">
					<div class="row">
						<label for="miles">miles <span class="required">*</span></label> <input type="text" value="" id="miles" name="miles" />
					</div>
				</form>
			</div>
		</div> <!-- .main-half -->
		
	</div> <!-- #main -->

	<div id="footer">
		<ul><li><a href="http://sandbox.rhymeswithmilk.com/">back to the sandbox</a></li><li><a href="http://www.rhymeswithmilk.com/" class="todo">back to rhymes with milk</a></li></p>
	</div> <!-- #footer -->
	
</div> <!-- #wrapper -->
</body>

</html>