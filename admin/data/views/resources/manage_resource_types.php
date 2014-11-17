<?php

	require_once(DATA_DIR.'/controllers/resource_controller.php');
	$res = new Resource_Controller($db);
	
	if(isset($_POST["new"])){
		$new_icon = $_POST["new_icon"];
		foreach($_POST["new"] as $key=>$n){
		echo $new_icon[$key];
			$res->create_resource_type($n, $new_icon[$key]);
		}
	}
	
	if(isset($_POST["update"])){
		$update_icons = $_POST["update_icon"];
		foreach($_POST["update"] as $key=>$elem){
			$res->update_resource_type($key, $elem, $update_icons[$key]);
		}
	}
	
	if(isset($_POST["delete"])){
		foreach($_POST["delete"] as $d){
			$res->delete_resource_type($d);
		}
	}
	
	$type_list = Resource_Type::get_all($db);
	$nav->assign_var('type_list', $type_list);
	
	$nav->set_template('MANAGE_RESOURCE_TYPES');

		
?>