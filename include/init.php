<?
	$cfg = array();

	$cfg['db_host']			= 'localhost';
	$cfg['db_name']			= 'citycreator';
	$cfg['db_user']			= 'citycreator';
	$cfg['db_pass']			= trim(file_get_contents(__DIR__.'/../secrets/mysql_password'));

	include('lib_db.php');

	$cfg[pickup_url] = 'http://www.citycreator.com/pickup.city';
	$cfg[login_url]  = 'http://www.citycreator.com/login.city';
	$cfg[share_url]  = 'http://www.citycreator.com/members.city';
	$cfg[cp_path] = '/var/www/html/citycreator.com/www/cp';

	function gen_check(){
		$length = 10;
		$out = '';
		for($i=0; $i<$length; $i++){
			$out .= chr(rand(ord('a'),ord('z')));
		}
		return $out;
	}

	function send_card($id){

		$card_row = db_single(db_fetch("SELECT * FROM citycreator_cards WHERE id=:id", array(
			'id' => $id,
		)));

		$msg = '';
		$msg .= "Someone has made you a special e-card at citycreator.com\n";
		$msg .= "\n";
		$msg .= "Click here to see it:\n";
		$msg .= "{$GLOBALS['cfg']['pickup_url']}?c={$card_row['id']}.{$card_row['check']}\n";
		$msg .= "\n";
		$msg .= "(If you can't click the link above, try to copy and paste it into\n";
		$msg .= " your browser)\n";
		$msg .= "\n";
		$msg .= "----------------------------------------------------------------------\n";
		$msg .= "This mail was sent to you by citycreator.com\n";
		$msg .= "\n";

		mail($card_row['friend_email'], "You've got an ecard!", $msg, "From: City Creator <cards@citycreator.com>\nReply-to: \"{$card_row['your_name']}\" <{$card_row['your_email']}>");
	}

	function send_password($email, $username, $password){
		global $cfg;

		$msg = '';
		$msg .= "You asked to be reminded of your citycreator.com login details:\n";
		$msg .= "\n";
		$msg .= "Username: $username\n";
		$msg .= "Password: $password\n";
		$msg .= "\n";
		$msg .= "Click here to log in:\n";
		$msg .= "$cfg[login_url]\n";
		$msg .= "\n";
		$msg .= "(If you can't click the link above, try to copy and paste it into\n";
		$msg .= " your browser)\n";
		$msg .= "\n";
		$msg .= "----------------------------------------------------------------------\n";
		$msg .= "This mail was sent to you by citycreator.com\n";
		$msg .= "\n";

		mail($email, "City Creator login reminder", $msg, "From: City Creator <admin@citycreator.com>");
	}

	function send_share($friend_email, $your_name, $your_email, $share_id, $message){
		global $db, $cfg;

		$msg = '';
		$msg .= "A citycreator.com member has designed a city and would like\n";
		$msg .= "to share it with you.\n";
		$msg .= "\n";
		$msg .= "Citycreator.com is a free website which lets you design\n";
		$msg .= "cities and share them with your friends. It's easy to use\n";
		$msg .= "and fun too!\n";
		$msg .= "\n";
		$msg .= "When you click on the link below, you will be asked to signup\n";
		$msg .= "if you haven't already. This is free, and the process is very\n";
		$msg .= "simple - just choose a username and password.\n";
		$msg .= "\n";
		$msg .= "Click on this link to start:\n";
		$msg .= "$cfg[share_url]?share_id=$share_id\n";
		$msg .= "\n";
		$msg .= "(If you can't click the link above, try to copy and paste it into\n";
		$msg .= " your browser)\n";
		$msg .= "\n";
		$msg .= "This city design was sent to you by $your_name ($your_email)\n";
		$msg .= "\n";

		if ($message){
			$msg .= "They also attached a message for you:\n";
			$msg .= "$message\n";
			$msg .= "\n";
		}
		$msg .= "----------------------------------------------------------------------\n";
		$msg .= "This mail was sent to you by citycreator.com\n";
		$msg .= "\n";

		mail($friend_email, "You've been sent a city design!", $msg, "From: City Creator <cities@citycreator.com>\nReply-to: \"$your_name\" <$your_email>");

	}


	function get_city($city){

		return db_single(db_fetch("SELECT * FROM citycreator_cities WHERE id=:id",array(
			'id' => $city ? $city : 1,
		)));
	}

	function dumper($foo){
		echo "<pre style=\"text-align: left;\">";
		if (is_resource($foo)){
			var_dump($foo);
		}else{
			echo HtmlSpecialChars(var_export($foo, 1));
		}
		echo "</pre>\n";
	}

