<?
	include('../include/init.php');

	$s = trim($_GET['s'] ?? '');
?>

<form>search: <input type="text" name="s" value="<?=HtmlSpecialChars($s)?>"> <input type="submit" value="Go"></form>

<?
	if ($s){
		$ret = db_fetch("SELECT * FROM citycreator_users WHERE username LIKE :q OR email LIKE :q", array(
			'q' => '%'.$s.'%',
		));
		foreach ($ret['rows'] as $row){
?>

<hr>

The data we are storing relating to you is as follows:<br>
<br>
user_id : <?=(int)($row['id'] ?? 0)?><br>
username : <?=HtmlSpecialChars($row['username'] ?? '')?><br>
password : <?=HtmlSpecialChars($row['password'] ?? '')?><br>
email address : <?=HtmlSpecialChars($row['email'] ?? '')?><br>
date_joined : <?=date('H:i jS F Y', $row['date_create'] ?? 0)?><br>


<?
		}
	}
?>
