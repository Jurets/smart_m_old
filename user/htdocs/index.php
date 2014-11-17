 <?php
	session_start();
	header("access-control-allow-origin: *");
	require_once("../config.php");
	require_once(DATA_DIR.'/models/course_model.php');
	require_once(DATA_DIR.'/models/resource_model.php');
	require_once(DATA_DIR.'/includes/smarty/Smarty.class.php');
	
	$smarty = new Smarty();
	$smarty->setTemplateDir(RESOURCE_DATA_DIR.'/templates');
	$smarty->setCompileDir(RESOURCE_DATA_DIR.'/templates_c');

	if(!isset($_POST["user"])||!isset($_POST["hashed_user"])||!isset($_POST["course"])||!isset($_POST["lesson"])){
		die();
	}

	if($_POST["hashed_user"]!=sha1(XSRF_SALT.$_POST["user"])){
		trigger_error("ERROR: XSRF Forgery");
		die();
	}

	if(!is_numeric($_POST["course"])){
		trigger_error("ERROR: Invalid course or target");
		die();
	}
	
	$db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
	if ($db->connect_errno) {
    	trigger_error("Failed to connect to MySQL: " . $mysqli->connect_error, E_USER_ERROR);
	}
	
	$course = new Course($db, $_POST["course"]);
	if(!is_numeric($course->get_id())){
		trigger_error("ERROR: Invalid course or target");
		die();
	}
	
	if(isset($_POST["collection"])){
		require_once(RESOURCE_DATA_DIR.'/views/collection.php');
	}
	if(isset($_POST["resource"])){
		require_once(RESOURCE_DATA_DIR.'/views/resource.php');
	}
	if(isset($_POST["video"])){
		require_once(RESOURCE_DATA_DIR.'/views/video.php');
	}
	
	if(isset($_POST["video_transcript_id"])&&is_numeric($_POST["video_transcript_id"])){
		require_once(RESOURCE_DATA_DIR.'/views/transcript.php');
		
	}
	
	$smarty->assign('course', $course);
	$smarty->assign('user', $_POST["user"]);
	$smarty->assign('hashed_user', $_POST["hashed_user"]);
	$smarty->assign('lesson', $_POST["lesson"]);
	
	
	$smarty->display($template);

	
?>