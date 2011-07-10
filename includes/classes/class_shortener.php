<?php
require_once(INC_PATH.DS."classes".DS."class_databaseobject.php");

class Shortener extends DatabaseObject {

	protected static $table_name = 'shortener';
	protected static $db_fields = array('id','short_url','redirect_url','date_created','is_unique');
	protected static $url_chars = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	
	protected $id;
	public $short_url;
	public $redirect_url;
	public $date_created;
	public $is_unique;
	
	
	function __construct() {
		$this->short_url = static::get_short_url();
		$this->redirect_url = static::get_redirect_url();
		if($this->short_url) { $this->is_unique = TRUE; }
		else { $this->is_unique = FALSE; }
		$this->date_created = date('Y-m-d G:i:s',time());
	}
	
	
	public static function get_shortener_object($args="") {
		// hand arguments
		if(!is_array($args)) {
			parse_str($args, $args_array);
		} else {
			$args_array =& $args;
		}
		
		$sql = "";
		if(isset($args_array['id']) && !empty($args_array['id'])) {
			$sql = "SELECT * FROM ".static::$table_name." WHERE id = '".$args_array['id']."' LIMIT 0,1";
		} elseif(isset($args_array['short_url']) && !empty($args_array['short_url'])) {
			$sql = "SELECT * FROM ".static::$table_name." WHERE short_url = '".$args_array['short_url']."' LIMIT 0,1";
		} elseif(isset($args_array['redirect_url']) && !empty($args_array['redirect_url'])) {
			$sql = "SELECT * FROM ".static::$table_name." WHERE redirect_url = '".$args_array['redirect_url']."' LIMIT 0,1";
		}
		
		if($sql) {
			$shortener_object_array = static::find_by_sql($sql);
			$shortener_object = array_pop($shortener_object_array);
			if(is_object($shortener_object)) {
				return $shortener_object;
			}
		}
		
		return false;
	}
	
	public static function get_short_url($redirect_url="") {
		global $database;
		
		// if no redirect url was given as an argument,
		// check if one was passed in the requested url
		if($redirect_url=="" && isset($_GET['short_url']) && trim($_GET['short_url'])!="") {
			return $database->escape_value($_GET['short_url']);
		}
		// if the redirect url was provided, search for it in the db
		if($redirect_url) {
			$shortener_object = static::get_shortener_object("redirect_url={$redirect_url}");
			if($shortener_object) {
				return $database->escape_value($shortener_object->short_url);
			}
		} elseif(isset($_POST['create_new']) && $_POST['create_new']=='Shorten!' && isset($_POST['short_url']) && trim($_POST['short_url'])!="" ) {
			// if redirect url is empty and the sql query doesn't return anything,
			// check if the create_new form was submitted
			$short_url = $database->escape_value(trim($_POST['short_url']));
			$short_url = str_replace(' ', '_', $short_url);
			return urlencode($short_url);
		}
		// if all of that failed, return false
		return FALSE;
	}
	
	public static function get_redirect_url($short_url="") {
		global $database;
		global $error_message;
		$redirect_url = "";
		
		// first things first, check if the create_new form was submitted
		if(isset($_POST['create_new']) && $_POST['create_new']=='Shorten!' && isset($_POST['redirect_url']) && trim($_POST['redirect_url'])!="" ) {
			// if short url is empty and the sql query doesn't return anything,
			// check if the create_new form was submitted
			$redirect_url = $database->escape_value(trim($_POST['redirect_url']));			
		
		} elseif(isset($_GET['short_url']) && trim($_GET['short_url'])!="") {
			// if short_url is requested in url, then look for redirect_url in database
			$sql = "SELECT * FROM ".static::$table_name." WHERE short_url = '".$_GET['short_url']."' LIMIT 0,1";
			$result_set = $database->query($sql);
			$row = $database->fetch_array($result_set);
			if(!empty($row)) {
				$redirect_url = $row[0]['redirect_url'];
			}
		} else {
		
			// if a short_url is not entered and create_new was not submitted,
			// try getting one from the requested site URL
			if($short_url=="" && static::get_short_url()) {
				$short_url = static::get_short_url();
			}
			// only search for the redirect_url if a short_url was either
			// specified explicitly, or was requested in the site URL
			if($short_url) {
				$shortener_object = static::get_shortener_object("short_url={$short_url}");
				if($shortener_object) {
					$redirect_url = $database->escape_value($shortener_object->redirect_url);
				}
			}
		}
		
		// check if url contains http
		if($redirect_url != "") { 
			if(strpos($redirect_url, 'http://') !== 0 && strpos($redirect_url, 'https://') !== 0 && strpos($redirect_url, 'ftp://') !== 0) {
				$redirect_url = 'http://' . $redirect_url;
			}
			
			// check if url is valid
			if(static::is_valid_url($redirect_url)) {
				return $redirect_url;
			}
		}
		
		// if all of that failed, return false
		$error_message = "Well crap, we could't get a valid URL to shorten. Are you sure you entered it correctly?";
		return FALSE;
	}
	
	
	public static function is_valid_url($url) {
		
		// Unfortunately, the below regex doesn't even work for google search urls.
		/*$regex = "((https?|ftp)\:\/\/)?"; // Scheme
		$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
		$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
		$regex .= "(\:[0-9]{2,5})?"; // Port
		$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 
		if(preg_match("/^$regex$/", $url)) {
			return TRUE;
		} else {
			echo $url . "<br />isn't valid<br />";
			return FALSE;
		}*/
		
		
		// I cannot find another good url validator.
		// Therefore, for now, there is no url validation
		return TRUE;
		
	}
	
	
	public static function redirect() {
		if(!isset($_POST['create_new'])) {
			$shortener_object = static::get_shortener_object('short_url='.static::get_short_url());
			if(is_object($shortener_object)) {
				$shortener_object->store_stats();
				//echo 'you just got redirected to ' . $shortener_object->redirect_url;
				header("Location: ".$shortener_object->redirect_url);
			}
		}
		return false;
	}
	
	private function store_stats() {
		global $database;
	
		$referer = "";
		if(isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		} else {
			$referer = 'http://mdk.im/';
		}
		$sql = "INSERT INTO redirect_stats (
					shortener_id,
					referer,
					redirect_time
				) VALUES ( " .
					$this->id .", '".
					$referer ."',
					NOW()
				)";
		$database->query($sql);
	}
	
	protected static function get_next_short_url($short_url="", $offset=1) {
		$url_chars =& static::$url_chars;
		$all_char_count = count($url_chars);
		if($short_url=="") {
			$short_url = static::get_last_generated_short_url();
		}
		$short_url_count = strlen($short_url);
		
		// if the index is positive...
		if($short_url_count >= $offset) {
			// if the last char of the short_url is the last char of the url_chars array
			if($short_url[$short_url_count - $offset] == $url_chars[$all_char_count - 1]) {
				// set the last char of short_url to the first char of url_chars...
				$short_url[$short_url_count - $offset] = $url_chars[0];
				// then move on to the second-to-last char of short_url and increment it
				$short_url = static::get_next_short_url($short_url, $offset+1);
				return $short_url;
			}
			// increment the last char of short_url normally
			$char_to_increment = $short_url[$short_url_count - $offset];
			$index = array_search($char_to_increment, $url_chars);
			$short_url[$short_url_count - $offset] = $url_chars[$index+1];
			return $short_url;
		} else {
			// if the index is negative, then it means that every char in the short_url string
			// has been incremented already, so now it needs an extra character
			// (e.g. tens -> hundreds, hundreds -> thousands, etc.)
			return $short_url . $url_chars[0];
		}
	}
	
	protected static function get_last_generated_short_url($row_num=0) {
		$sql = "SELECT short_url, is_unique
				FROM " . static::$table_name . "
				ORDER BY id DESC
				LIMIT {$row_num},1";
		
		$result = static::find_by_sql($sql);
		if(isset($result[0])) {
			$shortener_object = $result[0];
			if($shortener_object->is_unique) {
				$shortener_object->short_url = static::get_last_generated_short_url(++$row_num);
			}
			return $shortener_object->short_url;
		} else {
			return FALSE;
		}
	}
	
	public function save() {
		global $error_message;
		// find or set short_url
		if(!$this->short_url) {
			$this->short_url = static::get_next_short_url();
		}
		
		// find out if the short url already exists
		$sql = "SELECT * FROM " . static::$table_name . " WHERE short_url = '" . $this->short_url . "' LIMIT 0,1";
		if($previously_entered = static::find_by_sql($sql)) {
			$previous_object = $previously_entered[0];
			// if the short_url and redirect_url match the existing entry, then return that entry
			if($previous_object->redirect_url == $this->redirect_url) {
				return $previous_object;
			} else {
			// if the entered short_url & redirect_url are different than the existing entry, generate an error.
				$error_message = "That short URL already exists in the database. Please select a different short URL, or allow us to generate one for you.";
				return FALSE;
			}
		} elseif($this->redirect_url) {
			// else if redirect_url exists, save that shit
			return parent::save();
		} else {
			// if everything else went wrong
			return FALSE;
		}
	}

}

?>