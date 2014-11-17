 <?php
	session_start();
	header("access-control-allow-origin: *");
	require_once("../config.php");
	require_once(DATA_DIR.'/models/course_model.php');
	require_once(DATA_DIR.'/models/resource_model.php');
	require_once(RESOURCE_DATA_DIR.'/controllers/ratings_controller.php');

	if(!isset($_POST["function"])||!isset($_POST["user"])||!isset($_POST["hashed_user"])||!isset($_POST["course"])||!isset($_POST["lesson"])){
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
	
	if($_POST["function"]=="rate"){
		$rc = new Ratings_Controller($db, $_POST["widget_id"], $course, $_POST["lesson"], $_POST["user"]);
		if(!isset($_POST["fetch"])){
			preg_match('/star_([1-5]{1})/', $_POST['clicked_on'], $match);
			$rc->add_rating($match[1]);
		}
		$data['rating'] = $rc->get_user_rating();
		if($data['rating']==0)$data['average'] = -1;
		else $data['average'] = $rc->get_average();
		echo json_encode($data);
		
	}
	
	if($_POST["function"]=="video_chapter"){
		$video = explode("_",$_POST["video"]);
		$stmt = $db->prepare("INSERT INTO resource_video_access_log (user_id, course_id, lesson_id, video_id, timestamp, timer) VALUES (?, ?, ?, ?, NOW(), ?)");
		$stmt->bind_param('siiii', $_POST["user"], $course->get_id(), $_POST["lesson"], $video[1], $_POST["timestamp"]);
		$stmt->execute();
	}
	
	if($_POST["function"]=="video_seek_event"){
		$video = explode("_",$_POST["video"]);
		$stmt = $db->prepare("INSERT INTO resource_video_access_log (user_id, course_id, lesson_id, video_id, timestamp, timer, event_type) VALUES (?, ?, ?, ?, NOW(), ?, ?)");
		$stmt->bind_param('siiiii', $_POST["user"], $course->get_id(), $_POST["lesson"], $video[1], $_POST["timestamp"], $_POST["event"]);
		$stmt->execute();
	}
	
	if($_POST["function"]=="log_video_event"){
		$video = explode("_",$_POST["video"]);
		$stmt = $db->prepare("INSERT INTO resource_video_access_log (user_id, course_id, lesson_id, video_id, timestamp, event_type) VALUES (?, ?, ?, ?, NOW(), ?)");
		$stmt->bind_param('siiii', $_POST["user"], $course->get_id(), $_POST["lesson"], $video[1],  $_POST["event"]);
		$stmt->execute();
	}

?>
