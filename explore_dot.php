<?
	# $Id: explore_dot.php 2 2007-11-21 17:54:11Z iamcal $

	include('include/init.txt');

	login_check_loggedin();


	mysql_select_db('play2', $GLOBALS[db]);

	$dot = "\"C:\Program Files\ATT\Graphviz\bin\dot.exe\"";
	$tempname = dirname(__FILE__).'\temp.txt';

	$fh = fopen($tempname, 'w');

	fwrite($fh, "digraph play_goods_and_industries {\n");
	fwrite($fh, "\n");
	fwrite($fh, "\tcenter=true;\n");
	fwrite($fh, "\t// overlap=scale;\n");
	fwrite($fh, "\tsplines=true;\n");
	fwrite($fh, "\t// concentrate=true;\n");
	fwrite($fh, "\tlabel=\"a map of industries and goods in play\";\n");
	fwrite($fh, "\n");

	###############################################

	fwrite($fh, "\t// goods -> industries\n");
	fwrite($fh, "\n");

	$result = db_query("SELECT * FROM  industry_input");
	while ($row = db_fetch_hash($result)){

		$label = ($row[units_per_tick] > 1) ? " [ label = \"x$row[units_per_tick]\" ]" : '';

		fwrite($fh, "\tgoods_$row[goods_class_id] -> industry_$row[industry_class_id]$label;\n");
	}
	fwrite($fh, "\n");

	###############################################

	fwrite($fh, "\t// industries -> goods\n");
	fwrite($fh, "\n");

	$result = db_query("SELECT * FROM industry_output");
	while ($row = db_fetch_hash($result)){

		$label = ($row[units_per_tick] > 1) ? " [ label = \"x$row[units_per_tick]\" ]" : '';

		fwrite($fh, "\tindustry_$row[industry_class_id] -> goods_$row[goods_class_id]$label;\n");
	}
	fwrite($fh, "\n");

	###############################################

	fwrite($fh, "\t// goods\n");
	fwrite($fh, "\n");

	$result = db_query("SELECT * FROM goods_class");
	while ($row = db_fetch_hash($result)){

		fwrite($fh, "\tgoods_$row[id]\t[ label=\"".AddSlashes($row[name_plural])."\",shape=ellipse ];\n");
	}
	fwrite($fh, "\n");

	###############################################

	fwrite($fh, "\t// industry\n");
	fwrite($fh, "\n");

	$result = db_query("SELECT * FROM industry_class");
	while ($row = db_fetch_hash($result)){

		fwrite($fh, "\tindustry_$row[id]\t[ label=\"".AddSlashes($row[name_single])."\",shape=box ];\n");
	}
	fwrite($fh, "\n");

	###############################################

	fwrite($fh, "}\n");

	fclose($fh);


	if ($_GET[debug]){
		header('Content-type: text/plain');
		echo implode('', file($tempname));
	}else{
		header('Content-type: image/png');
		passthru("$dot -Tpng $tempname");
	}

	unlink($tempname);
?>