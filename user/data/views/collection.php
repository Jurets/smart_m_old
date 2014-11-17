<?php	
	$collection = new Resource_List_Collection($db, $_POST["collection"]);
	if(!is_numeric($collection->get_id())){
		trigger_error("Invalid Collection ID", E_USER_ERROR);
		die();
	}
	
	$smarty->assign('collection', $collection);
	$template = 'collection.tpl';
	
?>