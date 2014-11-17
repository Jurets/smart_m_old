<?php

	require_once(DATA_DIR.'/controllers/resource_controller.php');
	
	$res = new Resource_Controller($db);
	

	if(isset($_POST["process"])){
	
		$chapters = array();
		$minutes = $_POST["minutes"];
		$seconds = $_POST["seconds"];
		$titles = $_POST["chapter_titles"];
		foreach($titles as $key=>$elem){
			if(strlen($elem)==0)continue;
			if(!is_numeric($minutes[$key]))$m = 0;
			else $m = $minutes[$key];
			if(!is_numeric($seconds[$key]))$s = 0;
			else $s = $seconds[$key];
			
			$s = ($m*60)+$s;
			$chapters[$s] = $elem;
		}
		$status = $res->create_video_resource($_POST["video_id"], $_POST["internal_title"], $_POST["internal_notes"], $_POST["title"], $_POST["description"], $_POST["transcript"], $_POST["podcast_url"], $chapters);
		if($status[0]==false){
			$str = "ERR: ".$status[1];
			$nav->assign_var('error_message', $str);
		}
		else{
			$str = "Resource was created successfully.  The ID for the video (for inclusion in MOOC courses is): ".$status[1]->get_id();
			$nav->assign_var('success_message', $str);
		}
		
	}

	
	$nav->set_template('CREATE_VIDEO_RESOURCES');

?>