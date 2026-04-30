<?php
	$cfg['user'] = array();


	#
	# process cookies
	#

	if (isset($_COOKIE['u'])){
		list($id, $ts, $hash) = array_pad(explode('-', $_COOKIE['u']), 3, null);

		$row = db_single(db_fetch("SELECT * FROM citycreator_users WHERE id=:id", array(
			'id' => $id,
		)));

		$test = login_gen_hash($row['id'] ?? 0, $row['password'] ?? '', $ts);

		if ($test == $hash){

			$cfg['user'] = $row;
		}
	}



	#
	# perform a login?
	#

	$login_attempted = false;
	$login_failed = false;

	if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] && $_POST['password']){

		$login_attempted = true;
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);

		$row = db_single(db_fetch("SELECT * FROM citycreator_users WHERE username=:username", array(
			'username' => $username,
		)));

		if (isset($row['id']) && $row['id'] && password_verify($password, $row['password'])){
			login_set_user($row);
		}else{
			$login_failed = true;
		}
	}


	#
	# unset old cookies?
	#

	if (isset($_COOKIE['cookie_username'])) setcookie('cookie_username', '', time() - (24*60*60), '/', '.citycreator.com');
	if (isset($_COOKIE['cookie_password'])) setcookie('cookie_password', '', time() - (24*60*60), '/', '.citycreator.com');


	#
	# set a test cookie (to check cookies are enabled)
	#

	if (!isset($_COOKIE['t'])){
		setcookie('t', 1, time() + (365*24*60*60), '/', '.citycreator.com');
	}



	function login_gen_hash($id, $password, $ts){

		return hash_hmac('sha256', $id.$ts, $password);
	}

	function login_set_user($row){

		$GLOBALS['cfg']['user'] = $row;

		$ts = time();
		$hash = login_gen_hash($row['id'], $row['password'], $ts);
		$cookie = "{$row['id']}-{$ts}-{$hash}";

		setcookie('u', $cookie, time() + (365*24*60*60), '/', '.citycreator.com');

		if (!isset($_COOKIE['t'])){
			header("location: no_cookies.city");
			exit;
		}
	}

	function login_unset(){

		setcookie('u', '', time() - (24*60*60), '/', '.citycreator.com');
	}
