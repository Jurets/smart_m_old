<?php
	class Resource_Type{
		private $db;
		private $id;
		private $name;
		private $icon_path;
		
		public function __construct($db, $id=NULL){
			$this->db = $db;
			if($id!=NULL)$this->build($id);
		}
		
		public function get_id(){return $this->id;}
		public function get_name(){return $this->name;}
		public function get_icon_path(){return $this->icon_path;}
		public function set_name($name){$this->name = $name;}
		public function set_icon_path($icon_path){$this->icon_path = $icon_path;}
		
		private function build($id){
			$stmt  = $this->db->prepare("SELECT * FROM resource_types WHERE id=?");
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$res = $stmt->get_result();
			$row = $res->fetch_assoc();
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->icon_path = $row['icon_path'];
		}
		
		public function save(){
			if(!isset($this->id)||empty($this->id)){
				$stmt = $this->db->prepare("INSERT INTO resource_types (name, icon_path) VALUES (?, ?)");
				$stmt->bind_param('ss', $this->name, $this->icon_path);
				$stmt->execute();
				$this->id = $this->db->insert_id;
			}
			else{
				$stmt = $this->db->prepare("UPDATE resource_types SET name=?, icon_path=? WHERE id=?");
				$stmt->bind_param('ssi', $this->name, $this->icon_path, $this->id);
				$stmt->execute();
			}	
		}
		
		public function delete(){
			if(isset($this->id)&&!empty($this->id)){
				$stmt = $this->db->prepare("DELETE FROM resource_types WHERE id=?");
				$stmt->bind_param('i', $this->id);
				$stmt->execute();
			}
			unset($this);
			return;
		}
		
		public static function get_all($db){
			$stmt = $db->prepare("SELECT DISTINCT id FROM resource_types ORDER BY name");
			$stmt->execute();
			$res = $stmt->get_result();
			$types = array();
			while($row = $res->fetch_assoc()){
				$types[] = new Resource_Type($db, $row['id']);
			}
			return $types;
		}
		
		
	}

	class Resource{
		private $db;
		private $id;
		private $title;
		private $url;
		private $desription;
		private $author;
		private $organization;
		private $type;
		private $copyright;
		private $time_estimate;
		private $embed_code;
		private $sort_order;
		private $ratings = array();
		private $clicks = array();
		private $tags = array();
		
		public function __construct($db, $id=NULL){
			$this->db = $db;
			if($id!=NULL)$this->build($id);
		}

		public function get_id(){return $this->id;}
		public function get_title(){return $this->title;}
		public function get_url(){return $this->url;}
		public function get_description(){return $this->description;}
		public function get_author(){return $this->author;}
		public function get_organization(){return $this->organization;}
		public function get_sort_order(){return $this->sort_order;}
		public function get_type(){return $this->type;}
		public function get_copyright(){return $this->copyright;}
		public function get_time_estimate(){return $this->time_estimate;}
		public function get_embed_code(){return $this->embed_code;}
		public function get_tags(){return $this->tags;}

		public function set_id($author){$this->id=$id;}
		public function set_title($title){$this->title=$title;}
		public function set_url($url){$this->url=$url;}
		public function set_description($description){$this->description=$description;}
		public function set_author($author){$this->author=$author;}
		public function set_organization($organization){$this->organization=$organization;}
		public function set_sort_order($sort_order){$this->sort_order = $sort_order;}
		public function set_type($type){$this->type = $type;}
		public function set_copyright($copyright){$this->copyright = $copyright;}
		public function set_embed_code($embed_code){$this->embed_code = $embed_code;}
		public function set_time_estimate($time_estimate){$this->time_estimate = $time_estimate;}
		
		public function add_tag($tag){
			foreach($this->tags as $t){
				if($tag==$t)return;
			}
			$this->tags[] = $t;
		}
		
		public function delete_tag($tag){
			foreach($this->tag as $key=>$t){
				if($t==tag){
					unset($this->tag[$key]);
					return;
				}
			}
		}
		
		public function set_tags($tags){$this->tags = $tags;}

		private function build($id){
			$stmt = $this->db->prepare("SELECT * FROM resources WHERE id=?");
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$res = $stmt->get_result();
			$row = $res->fetch_assoc();
			$this->id = $row['id'];
			$this->title = $row['title'];
			$this->url = $row['url'];
			$this->description = $row['description'];
			$this->author = $row['author'];
			$this->organization = $row['organization'];
			$this->time_estimate = $row['time_estimate'];
			$this->copyright = $row['copyright'];
			$this->embed_code = $row['embed_code'];
			$this->type = new Resource_Type($this->db, $row['type_id']);
			
			
			$stmt = $this->db->prepare("SELECT tag FROM resource_item_tags WHERE resource_id=?");
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$res = $stmt->get_result();
			while($row = $res->fetch_assoc()){
				$this->tags[] = $row['tag'];
			}
			return;
		}
		
		private function set_ratings(){
			$this->ratings = array();
			$stmt = $this->db->prepare("SELECT course_id, lesson_id, user_id, rating, timestamp FROM resource_ratings WHERE resource_id=?");
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$res = $stmt->get_result();
			while($row = $res->fetch_assoc()){
				$r = new Resource_Rating();
				$r->set_course(new Course($this->db, $row['course_id']));
				$r->set_lesson_id($row['lesson_id']);
				$r->set_user_id($row['user_id']);
				$r->set_timestamp($row['timestamp']);
				$r->set_rating($row['rating']);
				$this->ratings[] = $r;
			}
			
		}
		
		public function add_rating($rating){
			foreach($this->ratings as $r){
				if($rating->get_user_id()==$r->get_user_id()&&$rating->get_course()->get_id()==$r->get_course()->get_id())return;
			}
			$stmt = $this->db->prepare("INSERT INTO resource_ratings (resource_id, course_id, lesson_id, user_id, timestamp, rating) VALUES (?, ?, ?, ?, NOW(), ?)");
			$stmt->bind_param('iiisi', $this->id, $rating->get_course()->get_id(), $rating->get_lesson_id(), $rating->get_user_id(), $rating->get_rating());
			$stmt->execute();
			$this->ratings[] = $rating;
			return;
		}
		
		public function get_ratings(){
			if(count($this->ratings)==0)$this->set_ratings();
			return $this->ratings;
		}
		
		public function get_click_data(){
			if(count($this->clicks)==0)$this->set_click_data();
			return $this->clicks;
		}
		
		public function set_click_data(){
			$this->clicks = array();
			$stmt = $this->db->prepare("SELECT course_id, lesson_id, user_id, timestamp FROM resource_access_log WHERE resource_id=?");
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$res = $stmt->get_result();
			while($row = $res->fetch_assoc()){
				$r = new Resource_Click();
				$r->set_course(new Course($this->db, $row['course_id']));
				$r->set_lesson_id($row['lesson_id']);
				$r->set_user_id($row['user_id']);
				$r->set_timestamp($row['timestamp']);
				$this->clicks[] = $r;
			}
		}
		
		

		public function save(){
			if(!isset($this->id)||empty($this->id)){
				$stmt = $this->db->prepare("INSERT INTO resources (title, url, description, author, organization, type_id, embed_code, copyright, time_estimate, created_by, creation_date, last_updated_by, last_update_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, NOW())");
				$stmt->bind_param('sssssississ', $this->title, $this->url, $this->description, $this->author, $this->organization, $this->type->get_id(), $this->embed_code, $this->copyright, $this->time_estimate, $_SESSION['email'], $_SESSION['email']);
				$stmt->execute();
				$this->id = $this->db->insert_id;
				
				foreach($this->tags as $t){
					$stmt = $this->db->prepare("INSERT INTO resource_item_tags (resource_id, tag) VALUES (?, ?)");
					$stmt->bind_param('is', $this->id, $t);
					$stmt->execute();
				}
			}
			else{
				$stmt = $this->db->prepare("UPDATE resources SET title=?, url=?, description=?, author=?, organization=?, type_id=?, copyright=?, time_estimate=?, embed_code=?, last_updated_by=?, last_update_date=NOW() WHERE id=?");
				$stmt->bind_param('sssssisissi', $this->title, $this->url, $this->description, $this->author, $this->organization, $this->type->get_id(), $this->copyright, $this->time_estimate, $this->embed_code, $_SESSION['email'], $this->id);
				$stmt->execute();
				
				$stmt = $this->db->prepare("DELETE FROM resource_item_tags WHERE resource_id=?");
				$stmt->bind_param('i', $this->id);
				$stmt->execute();
				
				foreach($this->tags as $t){
					$stmt = $this->db->prepare("INSERT INTO resource_item_tags (resource_id, tag) VALUES (?, ?)");
					$stmt->bind_param('is', $this->id, $t);
					$stmt->execute();
				}
			}
		}
		
		public static function get_all($db){
			$stmt = $db->prepare("SELECT DISTINCT id FROM resources ORDER BY title");
			$stmt->execute();
			$resources = array();
			$res = $stmt->get_result();
			while($row = $res->fetch_assoc()){
				$resources[] = new Resource($db, $row['id']);
			}
			return $resources;
		}

	}
	
	class Resource_Rating{
		private $user;
		private $course;
		private $lesson;
		private $rating;
		private $timestamp;
		
		public function set_course($course){$this->course = $course;}
		public function set_user_id($user){$this->user = $user;}
		public function set_lesson_id($lesson){$this->lesson = $lesson;}
		public function set_rating($rating){$this->rating = $rating;}
		public function set_timestamp($timestamp){$this->timestamp = $timestamp;}
		public function get_rating(){return $this->rating;}
		public function get_user_id(){return $this->user;}
		public function get_lesson_id(){return $this->lesson;}
		public function get_course(){return $this->course;}
		public function get_timestamp(){return $this->timestamp;}
		
	}
	
	class Resource_Click{
		private $user_id;
		private $course;
		private $lesson_id;
		private $timestamp;
		private $timer;
		private $event;
		public function set_course($course){$this->course = $course;}
		public function set_user_id($user){$this->user_id = $user;}
		public function set_lesson_id($lesson){$this->lesson = $lesson;}
		public function set_timestamp($timestamp){$this->timestamp = $timestamp;}
		public function set_event($event){$this->event = $event;}
		public function set_timer($timer){$this->timer = $timer;}
		public function get_user_id(){return $this->user_id;}
		public function get_lesson_id(){return $this->lesson;}
		public function get_course(){return $this->course;}
		public function get_timestamp(){return $this->timestamp;}
		public function get_event(){return $this->event;}
		public function get_timer(){return $this->timer;}
	}
	
	class Resource_List{
		private $db;
		private $id;
		private $name;
		private $display_name;
		private $heading;
		private $icon_path;
		private $sort_order;
		private $items = array();
		
		public function __construct($db, $id=NULL){
			$this->db = $db;
			if($id!=NULL)$this->build($id);
		}
		
		public function get_id(){return $this->id;}
		public function get_name(){return $this->name;}
		public function get_display_name(){return $this->display_name;}
		public function get_heading(){return $this->heading;}
		public function get_sort_order(){return $this->sort_order;}
		public function get_items(){return $this->items;}
		public function get_icon_path(){return $this->icon_path;}
		public function set_name($name){$this->name = $name;}
		public function set_display_name($name){$this->display_name = $name;}
		public function set_heading($heading){$this->heading = $heading;}
		public function set_sort_order($sort_order){$this->sort_order = $sort_order;}
		public function set_icon_path($icon_path){$this->icon_path = $icon_path;}
		public function add_item($item){
			foreach($this->items as $key=>$i){
				if($i->get_id()==$item->get_id())unset($this->items[$key]);
			}
			$this->items[] = $item;
			$this->save_items_list();
		}
		
		public function pick_resource_item($resource_id){
			foreach($this->items as $elem){
				if($elem->get_id()==$resource_id)return $elem;
			}
			return false;
		}
		
		public function remove_item($item){
			foreach($this->items as $key=>$i){
				if($i->get_id()==$item->get_id())unset($this->items[$key]);
			}
			$this->save_items_list();
		}
		
		private function save_items_list(){
			$stmt = $this->db->prepare("DELETE FROM resource_list_items WHERE resource_list_section_id=?");
			$stmt->bind_param('i', $this->get_id());
			$stmt->execute();
			foreach($this->items as $i){
				$stmt = $this->db->prepare("INSERT INTO resource_list_items (resource_list_section_id, resource_id, sort_order) VALUES (?, ?, ?)");
				$stmt->bind_param('iii', $this->get_id(), $i->get_id(), $i->get_sort_order());
				$stmt->execute();
			}
			
			$this->build_items_list();
		}
		
		private function build($id){
			$stmt = $this->db->prepare("SELECT * FROM resource_lists WHERE id=?");
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$res = $stmt->get_result();
			$row = $res->fetch_assoc();
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->heading = $row['heading'];
			$this->display_name = $row['display_name'];
			$this->icon_path = $row['icon_path'];

			$this->build_items_list();			
		}
		
		private function build_items_list(){
			$this->items = array();
			$stmt = $this->db->prepare("SELECT DISTINCT resource_id, sort_order FROM resource_list_items WHERE resource_list_section_id=? ORDER BY sort_order");
			$stmt->bind_param('i', $this->get_id());
			$stmt->execute();
			$res = $stmt->get_result();
			$i=0;
			while($row = $res->fetch_assoc()){
				$this->items[$i] = new Resource($this->db, $row['resource_id']);
				$this->items[$i]->set_sort_order($row['sort_order']);
				$i++;
			}
		}
		
		public function save(){
			if(!isset($this->id)||$this->id==NULL){
				$stmt = $this->db->prepare("INSERT INTO resource_lists (name, heading, display_name, icon_path) VALUES (?, ?, ?, ?)");
				$stmt->bind_param('ssss', $this->name, $this->heading, $this->display_name, $this->icon_path);
				$stmt->execute();
				$this->id = $this->db->insert_id;
			}
			else{
				$stmt = $this->db->prepare("UPDATE resource_lists SET name=?, heading=?, display_name=?, icon_path=? WHERE id=?");
				$stmt->bind_param('ssssi', $this->name, $this->heading, $this->display_name, $this->icon_path, $this->id);
				$stmt->execute();
			}
			$this->save_items_list();
		}
		
		public static function get_all($db){
			$stmt = $db->prepare("SELECT id FROM resource_lists");
			$stmt->execute();
			$res = $stmt->get_result();
			$lists = array();
			while($row = $res->fetch_assoc()){
				$lists[] = new Resource_List($db, $row['id']);
			}
			return $lists;
		}
	}
	
	class Resource_List_Collection{
		private $db;
		private $id;
		private $name;
		private $display_name;
		private $heading;
		private $sort_order;
		private $lists = array();
		
		public function __construct($db, $id=NULL){
			$this->db = $db;
			if($id!=NULL)$this->build($id);
		}
		
		public function get_id(){return $this->id;}
		public function get_name(){return $this->name;}
		public function get_display_name(){return $this->display_name;}
		public function get_heading(){return $this->heading;}
		public function get_lists(){return $this->lists;}
		public function set_name($name){$this->name = $name;}
		public function set_display_name($name){$this->display_name = $name;}
		public function set_heading($heading){$this->heading = $heading;}
		public function add_list($item){
			foreach($this->lists as $key=>$i){
				if($i->get_id()==$item->get_id()){
					$this->lists[$key] = $item;
					$this->save_items_list();
					return;
				}
			}
			$this->lists[] = $item;
			$this->save_items_list();
		}
		
		public function remove_list($item){
			foreach($this->lists as $key=>$elem){
				if($item->get_id()==$elem->get_id())unset($this->lists[$key]);
			}
			$this->save_items_list();
		}
		
		
		private function save_items_list(){
			$stmt = $this->db->prepare("DELETE FROM resource_list_collection_items WHERE collection_id=?");
			$stmt->bind_param('i', $this->get_id());
			$stmt->execute();
			
			foreach($this->lists as $i){
				$stmt = $this->db->prepare("INSERT INTO resource_list_collection_items (collection_id, resource_list_id, sort_order) VALUES (?, ?, ?)");
				$stmt->bind_param('iii', $this->get_id(), $i->get_id(), $i->get_sort_order());
				$stmt->execute();
			}
			
			$this->build_items_list();
		}
		
		public function pick_list($list_id){
			foreach($this->lists as $elem){
				if($elem->get_id()==$list_id)return $elem;
			}
			return false;
		}
		
		
		
		private function build($id){
			$stmt = $this->db->prepare("SELECT DISTINCT id, name, display_name, heading FROM resource_list_collections WHERE id=?");
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$res = $stmt->get_result();
			$row = $res->fetch_assoc();
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->display_name = $row['display_name'];
			$this->heading = $row['heading'];

			$this->build_items_list();			
		}
		
		private function build_items_list(){
			$this->items = array();
			$stmt = $this->db->prepare("SELECT resource_list_id, sort_order FROM resource_list_collection_items WHERE collection_id=? ORDER BY sort_order");
			$stmt->bind_param('i', $this->get_id());
			$stmt->execute();
			$res = $stmt->get_result();
			$i=0;
			while($row = $res->fetch_assoc()){
				$this->lists[$i] = new Resource_List($this->db, $row['resource_list_id']);
				$this->lists[$i]->set_sort_order($row['sort_order']);
				$i++;
			}
		}
		
		public function save(){
			if(!isset($this->id)||$this->id==NULL){
				$stmt = $this->db->prepare("INSERT INTO resource_list_collections (name, display_name, heading) VALUES (?, ?, ?)");
				$stmt->bind_param('sss', $this->name, $this->display_name, $this->heading);
				$stmt->execute();
				$this->id = $this->db->insert_id;
			}
			else{
				$stmt = $this->db->prepare("UPDATE resource_list_collections SET name=?, display_name=?, heading=? WHERE id=?");
				$stmt->bind_param('sssi', $this->name, $this->display_name, $this->heading, $this->id);
				$stmt->execute();
			}
			$this->save_items_list();
		}
		
		public static function get_all($db){
			$stmt = $db->prepare("SELECT id FROM resource_list_collections ORDER BY name");
			$stmt->execute();
			$res = $stmt->get_result();
			$collections = array();
			while($row = $res->fetch_assoc()){
				$collections[] = new Resource_List_Collection($db, $row['id']);
			}
			return $collections;
		}
		
	}
	
	class Video_Resource{
		private $db;
		private $id;
		private $youtube_id;
		private $internal_title;
		private $internal_notes;
		private $title;
		private $description;
		private $transcript;
		private $podcast_url;
		private $ratings = array();
		private $events = array();
		private $chapters = array();
		
		public function __construct($db, $id=null){
			$this->db = $db;
			if($id!=null)$this->build($id);
		}
		
		private function build($id){
			$stmt = $this->db->prepare("SELECT * FROM resource_videos WHERE id=?");
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$res = $stmt->get_result();
			$row = $res->fetch_assoc();
			$this->id = $row['id'];
			$this->youtube_id = $row['video_id'];
			$this->internal_title = $row['internal_title'];
			$this->internal_notes = $row['internal_notes'];
			$this->title = $row['title'];
			$this->description = $row['description'];
			$this->transcript = $row['transcript'];
			$this->podcast_url = $row['podcast_url'];
			$this->build_chapters();
			return;
		}
		
		private function build_chapters(){
			$this->chapters = array();
			$stmt = $this->db->prepare("SELECT * FROM resource_video_chapters WHERE video_id=? ORDER BY timecode");
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$res = $stmt->get_result();
			while($row = $res->fetch_assoc()){
				$this->chapters[$row['timecode']] = $row['title'];
			}
			return;
		}
		
		private function set_ratings(){
			$this->ratings = array();
			$stmt = $this->db->prepare("SELECT course_id, lesson_id, user_id, rating, timestamp FROM resource_video_ratings WHERE video_id=?");
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$res = $stmt->get_result();
			while($row = $res->fetch_assoc()){
				$r = new Resource_Rating();
				$r->set_course(new Course($this->db, $row['course_id']));
				$r->set_lesson_id($row['lesson_id']);
				$r->set_user_id($row['user_id']);
				$r->set_timestamp($row['timestamp']);
				$r->set_rating($row['rating']);
				$this->ratings[] = $r;
			}
			
		}
		
		public function add_rating($rating){
			foreach($this->ratings as $r){
				if($rating->get_user_id()==$r->get_user_id()&&$rating->get_course()->get_id()==$r->get_course()->get_id())return;
			}
			$stmt = $this->db->prepare("INSERT INTO resource_video_ratings (video_id, course_id, lesson_id, user_id, timestamp, rating) VALUES (?, ?, ?, ?, NOW(), ?)");
			$stmt->bind_param('iiisi', $this->id, $rating->get_course()->get_id(), $rating->get_lesson_id(), $rating->get_user_id(), $rating->get_rating());
			$stmt->execute();
			$this->ratings[] = $rating;
			return;
		}
		
		public function get_ratings(){
			if(count($this->ratings)==0)$this->set_ratings();
			return $this->ratings;
		}
		
		private function set_events(){
			$this->events = array();
			$stmt = $this->db->prepare("SELECT course_id, lesson_id, user_id, timestamp, timer, event_type FROM resource_video_access_log WHERE video_id=?");
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			$res = $stmt->get_result();
			while($row = $res->fetch_assoc()){
				$r = new Resource_Click();
				$r->set_course(new Course($this->db, $row['course_id']));
				$r->set_lesson_id($row['lesson_id']);
				$r->set_user_id($row['user_id']);
				$r->set_timestamp($row['timestamp']);
				$r->set_timer($row['timer']);
				$r->set_event($row['event_type']);
				$this->events[] = $r;
			}
		}
		
		public function get_events(){
			if(count($this->events)==0)$this->set_events();
			return $this->events;
		}
		
		
		public function get_id(){return $this->id;}
		public function get_video_id(){return $this->youtube_id;}
		public function get_internal_title(){return $this->internal_title;}
		public function get_internal_notes(){return $this->internal_notes;}
		public function get_title(){return $this->title;}
		public function get_description(){return $this->description;}
		public function get_transcript(){return $this->transcript;}
		public function get_podcast_url(){return $this->podcast_url;}
		public function get_chapters(){return $this->chapters;}
		
		public function set_video_id($param){$this->youtube_id = $param;}
		public function set_internal_title($param){$this->internal_title = $param;}
		public function set_internal_notes($param){$this->internal_notes = $param;}
		public function set_title($param){$this->title = $param;}
		public function set_description($param){$this->description = $param;}
		public function set_transcript($param){$this->transcript = $param;}
		public function set_podcast_url($param){$this->podcast_url = $param;}
		public function set_chapters($param){$this->chapters = $param;}
		
		public function save(){
			if(!is_numeric($this->id)){
				$stmt = $this->db->prepare("INSERT INTO resource_videos (video_id, internal_title, internal_notes, title, description, podcast_url, transcript) VALUES (?, ?, ?, ?, ?, ?, ?)");
				$stmt->bind_param('sssssss', $this->youtube_id, $this->internal_title, $this->internal_notes, $this->title, $this->description, $this->podcast_url, $this->transcript);
				$stmt->execute();
				$this->id = $this->db->insert_id;
			}
			else{
				$stmt = $this->db->prepare("UPDATE resource_videos SET video_id=?, internal_title=?, internal_notes=?, title=?, description=?, transcript=?, podcast_url=? WHERE id=?");
				$stmt->bind_param('sssssssi', $this->youtube_id, $this->internal_title, $this->internal_notes, $this->title, $this->description, $this->transcript, $this->podcast_url, $this->id);
				$stmt->execute();
			}
			
			$stmt = $this->db->prepare("DELETE FROM resource_video_chapters WHERE video_id=?");
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			foreach($this->chapters as $time=>$title){
				$stmt = $this->db->prepare("INSERT INTO resource_video_chapters (video_id, timecode, title) VALUES (?, ?, ?)");
				$stmt->bind_param('sis', $this->id, $time, $title);
				$stmt->execute();
			}
		}
		
		public static function get_all($db){
			$stmt = $db->prepare("SELECT id FROM resource_videos ORDER BY internal_title");
			$stmt->execute();
			$res = $stmt->get_result();
			$videos = array();
			while($row = $res->fetch_assoc()){
				$videos[] = new Video_Resource($db, $row['id']);
			}
			return $videos;
			
		}
		
		
		
	}

?>