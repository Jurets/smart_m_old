<?php

	require_once(DATA_DIR.'/controllers/resource_controller.php');
	
	$res = new Resource_Controller($db);

	$resource_list = Resource::get_all($db);
	$nav->assign_var('resource_list', $resource_list);
	
	$collections_list = Resource_List_Collection::get_all($db);
	$nav->assign_var('collections_list', $collections_list);
	if(isset($_POST["process"])){

		$status = $res->create_list($_POST["name"], $_POST["display_name"], $_POST["header"], $_POST["icon"], $_POST["collection"], $_POST["collection_sort"], $_POST["resource"], $_POST["resource_sort"]);
		
		if($status[0]==false){
			$str = "ERR: ".$status[1];
			$nav->assign_var('error_message', $str);
		}
		else{
			$str = "List was created successfully.";
			$nav->assign_var('success_message', $str);
		}

	}

	$nav->set_template('CREATE_RESOURCE_LISTS');	
	
?>
