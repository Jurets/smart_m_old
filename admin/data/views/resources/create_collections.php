<?php

	require_once(DATA_DIR.'/controllers/resource_controller.php');
	
	$res = new Resource_Controller($db);
	
	$resource_lists = Resource_List::get_all($db);
	$nav->assign_var('resource_lists', $resource_lists);
	
	if(isset($_POST["process"])){
		$status = $res->create_collection($_POST["name"], $_POST["display_name"], $_POST["header"], $_POST["list"], $_POST["sort"]);
		
		if($status[0]==false){
			$str = "ERR: ".$status[1];
			$nav->assign_var('error_message', $str);
		}
		else{
			$str = "Collection was created successfully.  Use the following ID for inclusion in CourseBuilder: ".$status[1]->get_id();
			$nav->assign_var('success_message', $str);
		}

		
	}
	

	
	$nav->set_template('CREATE_COLLECTIONS');

?>