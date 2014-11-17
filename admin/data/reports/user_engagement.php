<?php

	require_once(DATA_DIR.'/models/resource_model.php');
	require_once(DATA_DIR.'/controllers/resource_controller.php');

	Report_Controller::register('User Engagement', 'user_engagement', array(1, 2, 3, 11, 12));

	class user_engagement extends Report{
		private $resource_list;
		private $user_list;
		
		
		protected function build(){

			$this->user_list = Course_User::get_all($this->db, $this->google_admin, $this->course);
			
			$report = array();
			$report['header'][0] = "User";
			$report['header'][1] = "Role";
			$report['header'][2] = "Course Views";
			$report['header'][3] = "Resource Clicks";
			$report['header'][4] = "Resource Ratings";
			$report['header'][5] = "Video Plays";
			$report['header'][6] = "Video Ratings";
			$report['header'][7] = "Discussions Viewed";
			$report['header'][8] = "Discussions";
			$report['header'][9] = "Comments";
			$report['header'][10] = "Reactions";
			$results = array();
			
			$stmt = $this->db->prepare("SELECT user_id FROM events WHERE course_id=?");
			$stmt->bind_param('i', $this->course->get_id());
			$stmt->execute();
			$res = $stmt->get_result();	
			while($row = $res->fetch_assoc()){
				if(isset($results[$row['user_id']]['views']))$results[$row['user_id']]['views']++;
				else $results[$row['user_id']]['views'] = 1;
			}

			$stmt = $this->db->prepare("SELECT user_id FROM resource_access_log WHERE course_id=?");
			$stmt->bind_param('i', $this->course->get_id());
			$stmt->execute();
			$res = $stmt->get_result();	
			while($row = $res->fetch_assoc()){
				if(isset($results[$row['user_id']]['resource_clicks']))$results[$row['user_id']]['resource_clicks']++;
				else $results[$row['user_id']]['resource_clicks'] = 1;
			}

			$stmt = $this->db->prepare("SELECT user_id FROM resource_ratings WHERE course_id=?");
			$stmt->bind_param('i', $this->course->get_id());
			$stmt->execute();
			$res = $stmt->get_result();	
			while($row = $res->fetch_assoc()){
				if(isset($results[$row['user_id']]['resource_ratings']))$results[$row['user_id']]['resource_ratings']++;
				else $results[$row['user_id']]['resource_ratings'] = 1;
			}

			$stmt = $this->db->prepare("SELECT user_id FROM resource_video_access_log WHERE course_id=? AND event_type='1'");
			$stmt->bind_param('i', $this->course->get_id());
			$stmt->execute();
			$res = $stmt->get_result();	
			while($row = $res->fetch_assoc()){
				if(isset($results[$row['user_id']]['video_plays']))$results[$row['user_id']]['video_plays']++;
				else $results[$row['user_id']]['video_plays'] = 1;
			}

			$stmt = $this->db->prepare("SELECT user_id FROM resource_video_ratings WHERE course_id=?");
			$stmt->bind_param('i', $this->course->get_id());
			$stmt->execute();
			$res = $stmt->get_result();	
			while($row = $res->fetch_assoc()){
				if(isset($results[$row['user_id']]['video_ratings']))$results[$row['user_id']]['video_ratings']++;
				else $results[$row['user_id']]['video_ratings'] = 1;
			}

			$stmt = $this->db->prepare("SELECT ua.ForeignUserKey FROM ".$this->course->get_forum_database_name().".GDN_DiscussionViewAccessLog dl, ".$this->course->get_forum_database_name().".GDN_UserAuthentication ua WHERE dl.UserID=ua.UserID");
			$stmt->execute();
			$res = $stmt->get_result();	
			while($row = $res->fetch_assoc()){
				if(isset($results[$row['ForeignUserKey']]['discussion_views']))$results[$row['ForeignUserKey']]['discussion_views']++;
				else $results[$row['ForeignUserKey']]['discussion_views'] = 1;
			}

			$stmt = $this->db->prepare("SELECT ua.ForeignUserKey FROM ".$this->course->get_forum_database_name().".GDN_Discussion dl, ".$this->course->get_forum_database_name().".GDN_UserAuthentication ua WHERE dl.InsertUserID=ua.UserID");
			$stmt->execute();
			$res = $stmt->get_result();	
			while($row = $res->fetch_assoc()){
				if(isset($results[$row['ForeignUserKey']]['new_discussions']))$results[$row['ForeignUserKey']]['new_discussions']++;
				else $results[$row['ForeignUserKey']]['new_discussions'] = 1;
			}

			$stmt = $this->db->prepare("SELECT ua.ForeignUserKey FROM ".$this->course->get_forum_database_name().".GDN_Comment dl, ".$this->course->get_forum_database_name().".GDN_UserAuthentication ua WHERE dl.InsertUserID=ua.UserID");
			$stmt->execute();
			$res = $stmt->get_result();	
			while($row = $res->fetch_assoc()){
				if(isset($results[$row['ForeignUserKey']]['new_comments']))$results[$row['ForeignUserKey']]['new_comments']++;
				else $results[$row['ForeignUserKey']]['new_comments'] = 1;
			}

			$stmt = $this->db->prepare("SELECT ua.ForeignUserKey FROM ".$this->course->get_forum_database_name().".GDN_PeregrineReactions dl, ".$this->course->get_forum_database_name().".GDN_UserAuthentication ua WHERE dl.InsertUserID=ua.UserID");
			$stmt->execute();
			$res = $stmt->get_result();	
			while($row = $res->fetch_assoc()){
				if(isset($results[$row['ForeignUserKey']]['reactions']))$results[$row['ForeignUserKey']]['reactions']++;
				else $results[$row['ForeignUserKey']]['reactions'] = 1;
			}



			$row = 0;
			ksort($results);
			unset($results[date("Y-m-d")]);
			
			foreach($this->user_list as $key=>$elem){
				$report['content'][$row][0] = $elem->get_name();
				$report['content'][$row][1] = $elem->get_reg_field('role');

				if(isset($results[$key]['views']))$report['content'][$row][2] = $results[$key]['views'];
				else $report['content'][$row][2] = 0;

				if(isset($results[$key]['resource_clicks']))$report['content'][$row][3] = $results[$key]['resource_clicks'];
				else $report['content'][$row][3] = 0;

				if(isset($results[$key]['resource_ratings']))$report['content'][$row][4] = $results[$key]['resource_ratings'];
				else $report['content'][$row][4] = 0;

				if(isset($results[$key]['video_plays']))$report['content'][$row][5] = $results[$key]['video_plays'];
				else $report['content'][$row][5] = 0;

				if(isset($results[$key]['video_ratings']))$report['content'][$row][6] = $results[$key]['video_ratings'];
				else $report['content'][$row][6] = 0;

				if(isset($results[$key]['discussion_views']))$report['content'][$row][7] = $results[$key]['discussion_views'];
				else $report['content'][$row][7] = 0;

				if(isset($results[$key]['new_discussions']))$report['content'][$row][8] = $results[$key]['new_discussions'];
				else $report['content'][$row][8] = 0;

				if(isset($results[$key]['new_comments']))$report['content'][$row][9] = $results[$key]['new_comments'];
				else $report['content'][$row][9] = 0;

				if(isset($results[$key]['reactions']))$report['content'][$row][10] = $results[$key]['reactions'];
				else $report['content'][$row][10] = 0;


				$row++;
			}

						
			return $report;
		}
	}
?>