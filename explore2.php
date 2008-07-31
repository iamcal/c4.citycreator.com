<?
	# $Id$

	include('include/init.txt');

	login_check_loggedin();


	#
	# fetch list of each class
	#

	$class_list = array(
		'buildings'	=> 'name',
		'jobs'		=> 'name_single',
	#	'residence'	=> '',
		'industries'	=> 'name',
		'goods'		=> 'name_plural',
	#	'facility'	=> '',
	);

	$classes = array();
	$smarty->assign_by_ref('classes', $classes);

	foreach ($class_list as $class => $sort){

		$classes[$class] = array();

		$result = db_query("SELECT * FROM class_{$class} ORDER BY $sort ASC");
		while ($row = db_fetch_hash($result)){

			$classes[$class][] = $row;
		}
	}


	#
	# show page
	#

	$smarty->display('page_explore2.txt');
?>