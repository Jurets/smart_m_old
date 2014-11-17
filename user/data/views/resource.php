<?php	
	$r = new Resource($db, $_POST["resource"]);
	if(!is_numeric($r->get_id())){
		trigger_error("Invalid Resource ID", E_USER_ERROR);
		die();
	}
	
	$smarty->assign('r', $r);
	$template = 'resource.tpl';
	
?>