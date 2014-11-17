<?php

	require_once(DATA_DIR.'/controllers/resource_controller.php');
	
	$res = new Resource_Controller($db);
	

	$resource_types = Resource_Type::get_all($db);
	$nav->assign_var('resource_types', $resource_types);
	
	$resource_lists = Resource_List::get_all($db);
	$nav->assign_var('resource_lists', $resource_lists);
	
	
	
	if(isset($_POST["process"])){
		
		$status = $res->create_resource($_POST["name"], $_POST["url"], $_POST["author"], $_POST["organization"], $_POST["description"], $_POST["type"], $_POST["tags"], $_POST["copyright"], $_POST["time"], $_POST["embed"], $_POST["list"], $_POST["sort"]);
		
		if($status[0]==false){
			$str = "ERR: ".$status[1];
			$nav->assign_var('error_message', $str);
		}
		else{
			$str = "Resource was created successfully.  The ID for the resource (for inclusion in MOOC courses is): ".$status[1]->get_id();
			$nav->assign_var('success_message', $str);
		}
		
	}

	
	$nav->set_template('CREATE_RESOURCES');

?>