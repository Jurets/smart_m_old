<?php

	require_once(DATA_DIR.'/controllers/resource_controller.php');
	
	$res = new Resource_Controller($db);
	$nav->assign_var('res', $res);
	
	$collections = Resource_List_Collection::get_all($db);
	$nav->assign_var('collections', $collections);
	
	$resource_lists = Resource_List::get_all($db);
	$nav->assign_var('resource_lists', $resource_lists);
	
	if(isset($_POST["collection"])&&is_numeric($_POST["collection"])){
	
		$c = new Resource_List_Collection($db, $_POST["collection"]);
		if(!is_numeric($c->get_id()))trigger_error("ERROR: Invalid collection", E_USER_ERROR);
	
		if(isset($_POST["process"])){
			$status = $res->edit_collection($c->get_id(), $_POST["name"], $_POST["display_name"], $_POST["header"], $_POST["list"], $_POST["sort"]);
			
			if($status[0]==false){
				$str = "ERR: ".$status[1];
				$nav->assign_var('error_message', $str);
			}
			else{
				$str = "Collection was updated successfully.  Use the following ID for inclusion in CourseBuilder: ".$status[1]->get_id();
				$nav->assign_var('success_message', $str);
			}
	
			$c = new Resource_List_Collection($db, $_POST["collection"]);
		}
		
		$nav->assign_var('c', $c);
	
	}
	

	
	$nav->set_template('EDIT_RESOURCE_COLLECTIONS');

?>