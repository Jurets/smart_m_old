<?php

	require_once(DATA_DIR.'/models/resource_model.php');
	require_once(DATA_DIR.'/controllers/resource_controller.php');

	Report_Controller::register('Video Resource Usage', 'video_resource_usage', array(1, 2, 3, 11, 12));

	class video_resource_usage extends Report{
		private $resource_list;
		private $user_list;
		
		private function get_event_count($resource, $lesson, $event){
			$count = 0;
			foreach($resource->get_events() as $c){
				if($c->get_course()->get_id()==$this->course->get_id()&&$c->get_lesson_id()==$lesson->get_id()&&array_key_exists($c->get_user_id(), $this->user_list)&&$c->get_event()==$event){
						$count++;
						continue;
				}
			}
			return $count;
		}
		
		private function get_resources_for_lesson($lesson){
			$return = array();
			foreach($this->resource_list as $r){
				foreach($r->get_events() as $c){
					if($c->get_course()->get_id()==$this->course->get_id()&&$c->get_lesson_id()==$lesson->get_id()){
						$return[] = $r;
						continue(2);
					}
				}
				foreach($r->get_ratings() as $c){
					if($c->get_course()->get_id()==$this->course->get_id()&&$c->get_lesson_id()==$lesson->get_id()){
						$return[] = $r;
						continue(2);
					}
				}
			}
			return $return;
		}
		
		
		private function get_ratings($resource, $lesson){
			$result['avg'] = 0;
			$result['total'] = 0;
			$result[1] = 0;
			$result[2] = 0;
			$result[3] = 0;
			$result[4] = 0;
			$result[5] = 0;
			foreach($resource->get_ratings() as $r){
				if($r->get_course()->get_id()==$this->course->get_id()&&$r->get_lesson_id()==$lesson->get_id()&&array_key_exists($r->get_user_id(), $this->user_list)){
					$result['avg'] = $result['avg']+$r->get_rating();
					$result['total']++;
					$result[$r->get_rating()]++;
				}
			}
			if($result['total']==0)return $result;
			$result['avg'] = round($result['avg']/$result['total'],2);
			return $result;
		}
		
		protected function build(){
			
			$report = array();
			$report['header'][0] = "Lesson";
			$report['header'][1] = "Resource Name";
			$report['header'][2] = "Plays";
			$report['header'][3] = "Pauses";
			$report['header'][4] = "Completions";
			$report['header'][5] = "Ratings";
			$report['header'][6] = "Avg. Rating";
			$report['header'][7] = "1-Star";
			$report['header'][8] = "2-Star";
			$report['header'][9] = "3-Star";
			$report['header'][10] = "4-Star";
			$report['header'][11] = "5-Star";
			
			$row = 0;
			$content = new Course_Content($this->db, $this->course);
			$lessons = $content->get_lessons();
			
			$this->resource_list = Video_Resource::get_all($this->db);
			$this->user_list = Course_User::get_all($this->db, $this->google_admin, $this->course);
			
			
			foreach($lessons as $l){
					
				$resources = $this->get_resources_for_lesson($l);
				foreach($resources as $r){
					$report['content'][$row][0] = $l->get_unit()->get_index().".".$l->get_index();
					$report['content'][$row][1] = $r->get_internal_title();
					$report['content'][$row][2] = $this->get_event_count($r, $l, 1);
					$report['content'][$row][3] = $this->get_event_count($r, $l, 2);
					$report['content'][$row][4] = $this->get_event_count($r, $l, 0);
					$ratings = $this->get_ratings($r, $l);
					$report['content'][$row][5] = $ratings['total'];
					$report['content'][$row][6] = $ratings['avg'];
					$report['content'][$row][7] = $ratings['1'];
					$report['content'][$row][8] = $ratings['2'];
					$report['content'][$row][9] = $ratings['3'];
					$report['content'][$row][10] = $ratings['4'];
					$report['content'][$row][11] = $ratings['5'];
					
					$row++;
				}
			}
						
			return $report;
		}
	}
?>