<?php

	require_once(DATA_DIR.'/controllers/resource_controller.php');
	
	$res = new Resource_Controller($db);
	$nav->assign_var('res', $res);


	
	if(isset($_POST["resource_list"])&&is_numeric($_POST["resource_list"])){
	
		$r = new Resource_List($db, $_POST["resource_list"]);
		if(!is_numeric($r->get_id()))trigger_error("ERROR: Invalid resource list", E_USER_ERROR);
		
		if(isset($_POST["process"])){
			
			$status = $res->edit_list($r->get_id(), $_POST["name"], $_POST["display_name"], $_POST["header"], $_POST["icon"], $_POST["collection"], $_POST["collection_sort"], $_POST["resource"], $_POST["resource_sort"]);
			
			if($status[0]==false){
				$str = "ERR: ".$status[1];
				$nav->assign_var('error_message', $str);
			}
			else{
				$str = "List was updated successfully.";
				$nav->assign_var('success_message', $str);
			}
			
			$r = new Resource_List($db, $_POST["resource_list"]);
	
		}
		$nav->assign_var('r', $r);
		$collection_memberships = $res->get_resource_list_collection_memberships($r);
		$nav->assign_var('collection_memberships', $collection_memberships);
	}
	
	$all_resource_lists = Resource_List::get_all($db);
	$nav->assign_var('all_resource_lists', $all_resource_lists);

	$resources = Resource::get_all($db);
	$nav->assign_var('resources', $resources);
	
	$collections = Resource_List_Collection::get_all($db);
	$nav->assign_var('collections', $collections);

	$nav->set_template('EDIT_RESOURCE_LISTS');	
	
?>
