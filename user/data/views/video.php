<?php

	$video = new Video_Resource($db, $_POST["video"]);
	if(!is_numeric($video->get_id())){
		die();
	}
	
	
	$smarty->assign('video', $video);
	$template = 'video.tpl';

?>