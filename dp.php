<?
	include('init.txt');
?>

<form>search: <input type="text" name="s"> <input type="submit" value="Go"></form>

<?
	if ($s){
		$result = mysql_query("SELECT * FROM $cfg[db_prefix]_users WHERE username LIKE '%$s%' OR email  LIKE '%$s%'", $db);
		while($row = mysql_fetch_array($result)){
?>

<hr>

The data we are storing relating to you is as follows:<br>
<br>
user_id : <?=$row[id]?><br>
username : <?=$row[username]?><br>
password : <?=$row[password]?><br>
email address : <?=$row[email]?><br>
date_joined : <?=date('H:i jS F Y',$row[date_create])?><br>


<?
		}
	}
?>