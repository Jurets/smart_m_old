<?php
	require_once(DATA_DIR.'/models/course_model.php');
	require_once(DATA_DIR.'/models/resource_model.php');
	
	class Ratings_Controller{
		private $db;
		private $lesson_id;
		private $user_id;
		private $course;
		private $target;
		private $timestamp;
		
		public function __construct($db, $widget_id, $course, $lesson_id, $user_id){
			$this->db = $db;
			$detail = explode("_", $widget_id);
			if($detail[1]=="resource"){
				$this->target = new Resource($this->db, $detail[2]);
				
			}
			if($detail[1]=="video"){
				$this->target = new Video_Resource($this->db, $detail[2]);
			}
			if(!is_numeric($this->target->get_id())){
				trigger_error("ERROR!!!", E_USER_ERROR);
				die();
			}
			$this->course = $course;
			$this->lesson_id = $lesson_id;
			$this->user_id = $user_id;
		}
		
		public function add_rating($rating){
			foreach($this->target->get_ratings() as $r){
				if($r->get_user_id()==$this->user_id&&$r->get_course()->get_id()==$this->course->get_id())return;
			}
			$r = new Resource_Rating();
			$r->set_lesson_id($this->lesson_id);
			$r->set_user_id($this->user_id);
			$r->set_course($this->course);
			$r->set_rating($rating);
			$this->target->add_rating($r);
		}
		
		public function get_average(){
			if(count($this->target->get_ratings())==0)return -1;
			$sum = 0;
			foreach($this->target->get_ratings() as $r){
				$sum = $sum + $r->get_rating();
			}
			$average = $sum/count($this->target->get_ratings());
			$average = round($average, 2);
			return $average;
		}
		
		public function get_user_rating(){
			if(count($this->target->get_ratings())==0)return 0;
			foreach($this->target->get_ratings() as $r){
				if($r->get_user_id()==$this->user_id&&$r->get_course()->get_id())return $r->get_rating();
			}
			return 0;
		}
		
	}

?>