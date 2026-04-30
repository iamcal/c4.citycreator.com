<?
	$cfg = array();

	$cfg['db_host']			= 'localhost';
	$cfg['db_name']			= 'c4.citycreator';
	$cfg['db_user']			= 'c4.citycreator';
	$cfg['db_pass']			= trim(file_get_contents(__DIR__.'/../secrets/mysql_password'));

	include('lib_db.php');

	$cfg['pickup_url'] = 'http://c4.citycreator.com/pickup.city';
	$cfg['login_url']  = 'http://c4.citycreator.com/login.city';
	$cfg['share_url']  = 'http://c4.citycreator.com/members.city';
	$cfg['reset_url']  = 'http://c4.citycreator.com/reset.city';

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
		$msg .= "{$GLOBALS['cfg']['pickup_url']}?c=".($card_row['id'] ?? '').".".($card_row['check'] ?? '')."\n";
		$msg .= "\n";
		$msg .= "(If you can't click the link above, try to copy and paste it into\n";
		$msg .= " your browser)\n";
		$msg .= "\n";
		$msg .= "----------------------------------------------------------------------\n";
		$msg .= "This mail was sent to you by citycreator.com\n";
		$msg .= "\n";

		mail($card_row['friend_email'] ?? '', "You've got an ecard!", $msg, "From: City Creator <cards@citycreator.com>\nReply-to: \"".($card_row['your_name'] ?? '')."\" <".($card_row['your_email'] ?? '').">");
	}

	function get_password_reset_code($user){

		$ts = time();
		$hmac = hash_hmac("sha256", $user['id'].$ts, $user['password']);
		$hmac = substr($hmac, 0, 20);

		return "{$user['id']}.{$ts}.{$hmac}";
	}

	function verify_password_reset_code($code){

		list($id, $ts, $hmac) = explode('.', $code, 3);

		$user = db_single(db_fetch("SELECT * FROM citycreator_users WHERE id=:id", array(
			'id' => $id,
		)));

		if (!isset($user['id']) || !$user['id']) return null;

		$test_hmac = hash_hmac("sha256", $user['id'].$ts, $user['password']);
		$test_hmac = substr($test_hmac, 0, 20);

		if ($test_hmac != $hmac) return null;

		return $user;
	}

	function send_password_reset($user){

		$code = get_password_reset_code($user);

		$msg = '';
		$msg .= "You asked to reset your citycreator.com password:\n";
		$msg .= "\n";
		$msg .= "Username: {$user['username']}\n";
		$msg .= "\n";
		$msg .= "Click here to reset your password:\n";
		$msg .= "{$GLOBALS['cfg']['reset_url']}?c={$code}\n";
		$msg .= "\n";
		$msg .= "(If you can't click the link above, try to copy and paste it into\n";
		$msg .= " your browser)\n";
		$msg .= "\n";
		$msg .= "If you did not request this reset, please ignore this email.\n";
		$msg .= "\n";
		$msg .= "----------------------------------------------------------------------\n";
		$msg .= "This mail was sent to you by citycreator.com\n";
		$msg .= "\n";

		mail($user['email'], "City Creator password reset", $msg, "From: City Creator <admin@citycreator.com>");
	}

	function send_share($friend_email, $your_name, $your_email, $share_id, $message){

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
		$msg .= "{$GLOBALS['cfg']['share_url']}?share_id={$share_id}\n";
		$msg .= "\n";
		$msg .= "(If you can't click the link above, try to copy and paste it into\n";
		$msg .= " your browser)\n";
		$msg .= "\n";
		$msg .= "This city design was sent to you by {$your_name} ({$your_email})\n";
		$msg .= "\n";

		if ($message){
			$msg .= "They also attached a message for you:\n";
			$msg .= "{$message}\n";
			$msg .= "\n";
		}
		$msg .= "----------------------------------------------------------------------\n";
		$msg .= "This mail was sent to you by citycreator.com\n";
		$msg .= "\n";

		mail($friend_email, "You've been sent a city design!", $msg, "From: City Creator <cities@citycreator.com>\nReply-to: \"{$your_name}\" <{$your_email}>");
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

