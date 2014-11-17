<?php

	$res = new Video_Resource($db, $_POST["video_transcript_id"]);
	if(!is_numeric($res->get_id())){
		trigger_error("Invalid Resource ID", E_USER_ERROR);
		die();
	}
	
	$stmt = $db->prepare("INSERT INTO resource_video_access_log (user_id, course_id, lesson_id, video_id, timestamp, event_type) VALUES (?, ?, ?, ?, NOW(), 300)");
	$stmt->bind_param('siii', $_POST["user"], $_POST["course"], $_POST["lesson"], $_POST["video_transcript_id"]);
	$stmt->execute();
	
	$smarty->assign('res', $res);
	$template = "transcript.tpl";
	
?>