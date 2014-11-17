<?php
	 ini_set('max_execution_time',0);
	 ini_set('memory_limit', '2048M');
	require('config.php');
	require_once(DATA_DIR.'/controllers/security_controller.php');
	require_once(DATA_DIR.'/controllers/course_controller.php');
	
		set_include_path(get_include_path() . PATH_SEPARATOR . DATA_DIR.'/includes');
	require_once 'Google/Client.php';
	require_once 'Google/Service/Datastore.php';
	
	$db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
	if ($db->connect_errno) {
    	trigger_error("Failed to connect to MySQL: " . $mysqli->connect_error, E_USER_ERROR);
	}


	$cc = new Course_Controller($db);
	$results = $cc->sync_announcement_lists();
	print_r($results);

class Event_Log{
			private $db;
			private $google_session;
			private $partition_id;
			private $course;
			private $datastore;
			private $lessons;
			private $units;
			private $users;
			private $events;
		
			public function __construct($db, $google_session, $course){
				$this->db = $db;
				$this->google_session = $google_session;
				$this->course = $course;
				$this->datastore = new Google_Service_Datastore($this->google_session->get_session());
	
				$this->partition_id = new Google_Service_Datastore_PartitionId();
				$this->partition_id->setDatasetId($this->course->get_datastore_instance());
				$this->partition_id->setNamespace($this->course->get_datastore_name());
				$this->users = Course_User::get_all($db, $google_session, $course, false);
				$content = new Course_Content($db, $course);
				$this->units = $content->get_units();
				$this->lessons = $content->get_lessons();
				
				$stmt = $this->db->prepare("DELETE FROM events WHERE course_id=?");
				$stmt->bind_param('i', $this->course->get_id());
				$stmt->execute();
				
				$this->build();
			}
			private function build(){
				$status = true;
				$records = 0;
				 while($status){
					try{
						$query = new Google_Service_Datastore_GqlQuery();
						$query->setAllowLiteral(true);
						$query->setQueryString("SELECT * FROM EventEntity LIMIT 5000 OFFSET ".$records);
						
					
						$run_query = new Google_Service_Datastore_RunQueryRequest();
						$run_query->setGqlQuery($query);
						$run_query->setPartitionId($this->partition_id);
						$results = $this->datastore->datasets->runQuery($this->course->get_datastore_instance(), $run_query);
						
						$results = $results->getBatch()->getEntityResults();
						foreach($results as $r){
							$this->process_entity($r->getEntity());
							$records++;
						}
						if(count($results)<5000)$status = false;
					}
					catch (Google_Exception $e) {
					    // Other error.
					    print "An error occurred: (" . $e->getCode() . ") " . $e->getMessage() . "\n";
					  }
					echo $records."\n";
					 
				}
			}
			
			private function process_entity($entity){
				$key = $entity->getKey();
				$key = $key->getPath();
				$properties = $entity->getProperties();
				
				$user_id = $properties['user_id']->getStringValue();
				if(!array_key_exists($user_id, $this->users))return;
				if($this->users[$user_id]->is_excluded())return;
				
				$source = $properties['source']->getStringValue();
				if($source!="enter-page")return;
				
				$datetime = $properties['recorded_on']->getDateTimeValue();
				$datetime = strtotime($datetime);

				
				$event = json_decode($properties['data']->getStringValue(), true);
				$url = array();
				parse_str(parse_url($event['location'], PHP_URL_QUERY), $url);
				
				if(!array_key_exists('unit', $url)&&!array_key_exists('lesson', $url))return;
				
				if(!array_key_exists('lesson', $url)){
					if(!array_key_exists($url['unit'], $this->units))return;
					$lesson = array_values($this->units[$url['unit']]->get_lessons())[0];
					$lesson = $lesson->get_id();
				}
				else $lesson = $url['lesson'];
				
				if(!array_key_exists($lesson, $this->lessons))return;
				
				$stmt = $this->db->prepare("INSERT INTO events (user_id, course_id, lesson_id, datetime) VALUES (?, ?, ?, FROM_UNIXTIME(?))");
				$stmt->bind_param('siii', $user_id, $this->course->get_id(), $lesson, $datetime);
				$stmt->execute();
				
				
			}
		}
$course_list = Course::get_all_courses($db);
foreach($course_list as $c){
	if(strlen($c->get_url())>0)
		new Event_Log($db, new Google_Admin_Session($db), $c);
}
?>