 <?php
	session_start();
	header("access-control-allow-origin: *");
	require_once("../config.php");
	require_once(DATA_DIR.'/models/course_model.php');
	require_once(DATA_DIR.'/models/resource_model.php');
	require_once(RESOURCE_DATA_DIR.'/controllers/ratings_controller.php');

	if(!isset($_POST["user"])||!isset($_POST["hashed_user"])||!isset($_POST["course"])||!isset($_POST["lesson"])){
		die();
	}

	if($_POST["hashed_user"]!=sha1(XSRF_SALT.$_POST["user"])){
		trigger_error("ERROR: XSRF Forgery");
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

	
	$resource = new Resource($db, $_POST["resource_id"]);
	if(!is_numeric($resource->get_id())){
		trigger_error("ERROR: Invalid course or target");
		die();
	}
	$stmt = $db->prepare("INSERT INTO resource_access_log (user_id, course_id, lesson_id, resource_id, timestamp) VALUES (?, ?, ?, ?, NOW())");
	$stmt->bind_param('siii', $_POST["user"], $course->get_id(), $_POST["lesson"], $resource->get_id());
	$stmt->execute();
	header("Location: ".$resource->get_url());
?>