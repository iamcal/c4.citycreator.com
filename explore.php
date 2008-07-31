<?
	# $Id: explore.php 2 2007-11-21 17:54:11Z iamcal $

	include('include/init.txt');

	login_check_loggedin();


	mysql_select_db('play2', $GLOBALS[db]);


	#
	# fetch list of each class
	#

	$class_list = array('building', 'job', 'residence', 'industry', 'goods', 'facility');

	$classes = array();
	$smarty->assign_by_ref('classes', $classes);

	foreach ($class_list as $class){

		$classes[$class] = array();

		$result = db_query("SELECT * FROM {$class}_class ORDER BY name_single ASC");
		while ($row = db_fetch_hash($result)){

			$classes[$class][] = $row;
		}
	}


	#
	# show page
	#

	$smarty->display('page_explore.txt');
?>