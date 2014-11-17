<?php
	

	class Course{
		private $db;
		private $id;
		private $title;
		private $short_code;
		private $url;
		private $datastore_instance;
		private $datastore_name;
		private $forum_database_name;
		private $notification_distribution_list;
		private $admin_email;

		function __construct($db, $id=NULL){
			$this->db = $db;
			if($id!=NULL)$this->build($id);
		}

		public function set_title($title){$this->title = $title;}
		public function set_short_code($short_code){$this->short_code = $short_code;}
		public function set_datastore_instance($instance_name){$this->datastore_instance = $instance_name;}
		public function set_datastore_name($datastore_name){$this->datastore_name = $datastore_name;}
		public function set_forum_database_name($database_name){$this->forum_database_name = $database_name;}
		public function set_notification_distribution_list($list){$this->notification_distribution_list = $list;}
		public function set_admin_email($email){$this->admin_email = $admin_email;}
		public function set_url($url){$this->url = $url;}

		public function get_id(){return $this->id;}
		public function get_title(){return $this->title;}
		public function get_short_code(){return $this->short_code;}
		public function get_datastore_instance(){return $this->datastore_instance;}
		public function get_datastore_name(){return $this->datastore_name;}
		public function get_forum_database_name(){return $this->forum_database_name;}
		public function get_notification_distribution_list(){return $this->notification_distribution_list;}
		public function get_admin_email(){return $this->admin_email;}
		public function get_url(){return $this->url;}

		public function save(){
			if ($this->id==NULL){
				$stmt = $this->db->prepare("INSERT INTO courses (course_title, course_short_code, datastore_instance, datastore_name, forum_database_name, notification_distribution_list) VALUES (?, ?, ?, ?, ?, ?)");
				$stmt->bind_param("ssssss", $this->title, $this->short_code, $this->datastore_instance, $this->datastore_name, $this->forum_database_name, $this->notification_distribution_list);
				$stmt->execute();
				$this->id = $this->db->insert_id;
			}
			else{
				$stmt = $this->db->prepare("UPDATE courses SET course_title=?, course_short_code=?, datastore_instance=?, datastore_name=?, forum_database_name=?, notification_distribution_list=? WHERE id=?");
				$stmt->bind_param("ssssssi", $this->title, $this->short_code, $this->datastore_instance, $this->datastore_name, $this->forum_database_name, $this->notification_distribution_list, $this->id);
				$stmt->execute();
			}
		}

		public function delete(){
			$stmt = $this->db->prepare("DELETE FROM courses WHERE id=?");
			$stmt->bind_param("i", $this->id);
			$stmt->execute();
			unset($this);
		}

		private function build($id){
			$stmt = $this->db->prepare("SELECT * FROM courses WHERE id=?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$res = $stmt->get_result();
			$row = $res->fetch_assoc();
			$this->id = $row['id'];
			$this->title = $row['course_title'];
			$this->datastore_instance = $row['datastore_instance'];
			$this->short_code = $row['course_short_code'];
			$this->datastore_instance = $row['datastore_instance'];
			$this->datastore_name = $row['datastore_name'];
			$this->forum_database_name = $row['forum_database_name'];
			$this->notification_distribution_list = $row['notification_distribution_list'];
			$this->admin_email = $row['admin_email_address'];
			$this->url = $row['course_url'];
			return;

		}

		public static function get_all_courses($db){
			$stmt = $db->prepare("SELECT DISTINCT id FROM courses ORDER BY course_short_code");
			$stmt->execute();
			$courses = array();
			$res = $stmt->get_result();	
			while($row = $res->fetch_assoc()){
				$courses[] = new Course($db, $row['id']);
			}
			return $courses;
		}

	}

?>