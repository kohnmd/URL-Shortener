<?php include('../includes/initialize.php');

// REDIRECT
// On sucess:  will store the redirect stats, then redirect the user
// 			   to the appropriate redirect_url.
// On failure: will return false and the script will continue to execute.
//Shortener::redirect();

// CREATE NEW
if(isset($_POST['create_new']) && $_POST['create_new'] == 'Shorten!') {
	$shortener = new Shortener();
	/*$saved = $shortener->save();
	pre_print($saved);
	if($error_message!="") {
		echo $error_message;
	}*/
	
	pre_dump($shortener->redirect_url);
	
	
$regex = "((https?|ftp)\:\/\/)?"; // Scheme
$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
$regex .= "(\:[0-9]{2,5})?"; // Port
$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 
if(preg_match("/^$regex$/", $shortener->redirect_url)) {
    echo $shortener->redirect_url." = ".'<font color="blue">Valid URL</font>';
} else {
    echo $shortener->redirect_url." = ".'<font color="red">Invalid URL</font>';
}
	
	
	
	echo $error_message;
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
			<div class="main-header">
				<h3>create new</h3>
				<?php if(isset($message) && !empty($message)) echo '<div class="row"><h5 class="colorful input-column">{ ' . $message . ' }</h5></div>'; ?>
			</div>
			<div class="contents">
				<form method="post" action="/">
					<div class="row">
						<label for="redirect_url">url to shorten <span class="required">*</span></label>
						<input type="text" value="" id="redirect_url" name="redirect_url" maxlength="256" required />
					</div>
					<div class="row">
						<label for="redirect_url">custom short url <span class="description">(leave blank for auto-generated url)</span></label>
						<span class="description">http://mdk.im/</span> <input type="text" value="" id="short_url" name="short_url" maxlength="64" />
					</div>
					<div class="row">
						<input type="submit" value="Shorten!" name="create_new" />
					</div>
				</form>
			</div>
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