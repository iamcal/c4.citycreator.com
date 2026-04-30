<?
	include('../include/init.php');
?>

<form method="get" action="dp.php">
	search: <input type="text" name="s">
	<input type="submit" value="Go">
</form>

<?
	if (!empty($_GET['s'])){
		$ret = db_fetch("SELECT * FROM citycreator_users WHERE username=:s OR email=:s", array(
			's' => $_GET['s'],
		));
		foreach ($ret['rows'] as $row){
?>

<hr>

The data we are storing relating to you is as follows:<br>
<br>
user_id : <?=HtmlSpecialChars($row['id'])?><br>
username : <?=HtmlSpecialChars($row['username'])?><br>
email address : <?=HtmlSpecialChars($row['email'])?><br>
date_joined : <?=date('H:i jS F Y',$row['date_create'])?><br>


<?
		}
	}
?>
