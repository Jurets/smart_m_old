<?php

	require_once(DATA_DIR.'/models/resource_model.php');
	
	class Resource_Controller{
	
		private $db;
		
		public function __construct($db){
			$this->db = $db;
		}
		
		public function create_resource_type($name, $icon_path){
			if(!isset($name)||strlen($name)==0){
				$status[0]=false;
				$status[1]="Name is required field.";
				return $status;
			}
			$type_list = Resource_Type::get_all($this->db);
			foreach($type_list as $t){
				if($t->get_name()==$name){
					$status[0]=false;
					$status[1]="Resource name must be unique.";
					return $status;
				}
			}
			
			$r = new Resource_Type($this->db);
			$r->set_name($name);
			$r->set_icon_path($icon_path);
			$r->save();
			$status[0] = true;
			$status[1] = $r;
			return $status;
		}
		
		public function update_resource_type($id, $name, $icon_path){
			$elem = new Resource_Type($this->db, $id);
			if($elem->get_id()==NULL)return;
			$elem->set_name($name);
			$elem->set_icon_path($icon_path);
			$elem->save();
			return;
		}
		
		public function delete_resource_type($id){
			$elem = new Resource_Type($this->db, $id);
			if($elem->get_id()==NULL)return;
			$elem->delete();
		}
		
		public function create_collection($name, $display_name, $heading, $list, $sort){
			if(!isset($name)||strlen($name)==0){
				$status[0]=false;
				$status[1]="Name is a required field.";
				return $status;
			}
			$collection_list = Resource_List_Collection::get_all($this->db);
			foreach($collection_list as $cl){
				if($cl->get_name()==$name){
					$status[0]=false;
					$status[1]="Name must be unique.";
					return $status;
				}
			}
			
			$rc = new Resource_List_Collection($this->db);
			$rc->set_name($name);
			$rc->set_display_name($display_name);
			$rc->set_heading($heading);
			$rc->save();
			
			if(is_array($list)){
				foreach($list as $l){
					$resource_list = new Resource_List($this->db, $l);
					if(!is_numeric($resource_list->get_id()))continue;
					
					if(is_numeric($sort[$l]))$s = $sort[$l];
					else $s = 100;
					$resource_list->set_sort_order($s);
					$rc->add_list($resource_list);
				}
			}
			
			$rc->save();
			$status[0]=true;
			$status[1]=$rc;
			return $status;
		}
		
		public function edit_collection($id, $name, $display_name, $heading, $list, $sort){
			if(!isset($name)||strlen($name)==0){
				$status[0]=false;
				$status[1]="Name is a required field.";
				return $status;
			}
			
			$rc = new Resource_List_Collection($this->db, $id);
			$rc->set_name($name);
			$rc->set_display_name($display_name);
			$rc->set_heading($heading);
			$rc->save();
			
			if(is_array($list)){
			
				$curr_lists = $rc->get_lists();
				foreach($curr_lists as $cl){
					if(!in_array($cl->get_id(), $list))$rc->remove_list($cl);
				}
			
				foreach($list as $l){
					$resource_list = new Resource_List($this->db, $l);
					if(!is_numeric($resource_list->get_id()))continue;
					
					if(is_numeric($sort[$l]))$s = $sort[$l];
					else $s = 100;
					$resource_list->set_sort_order($s);
					$rc->add_list($resource_list);
				}
			}
			else{
				$curr_lists = $rc->get_lists();
				foreach($curr_lists as $cl){
					$rc->remove_list($cl);
				}
			}
			
			$rc->save();
			$status[0]=true;
			$status[1]=$rc;
			return $status;
		}
		
		public function create_list($name, $display_name, $heading, $icon, $collections, $collections_sort, $resources, $resources_sort){
			if(!isset($name)||strlen($name)==0){
				$status[0]=false;
				$status[1]="Name is required.";
				return $status;
			}
			$resource_list = Resource_List::get_all($this->db);
			foreach($resource_list as $cl){
				if($cl->get_name()==$name){
					$status[0]=false;
					$status[1]="Name must be unique.";
					return $status;
				}
			}
			
			$rl = new Resource_List($this->db);
			$rl->set_name($name);
			$rl->set_heading($heading);
			$rl->set_display_name($display_name);
			$rl->set_icon_path($icon);
			$rl->save();	
			if(is_array($resources)){
				foreach($resources as $key=>$elem){
					$res = new Resource($this->db, $elem);
					if(isset($resources_sort[$key])&&is_numeric($resources_sort[$key]))$sort = $resources_sort[$key];
					else $sort = 100;
					$res->set_sort_order($sort);
					$rl->add_item($res);
				}
			}
		
			
			if(is_array($collections)){
				foreach($collections as $key=>$elem){
					$temp_list = new Resource_List($this->db, $rl->get_id());
					if(isset($collections_sort[$key])&&is_numeric($collections_sort[$key]))$sort = $collections_sort[$key];
					else $sort = 100;
					$temp_list->set_sort_order($sort);
					$coll = new Resource_List_Collection($this->db, $elem);
					$coll->add_list($temp_list);
					$coll->save();
				}
			}
			$status[0]=true;
			$status[1]=$rl;
			return $status;
		}
		
		public function edit_list($id, $name, $display_name, $heading, $icon, $collections, $collections_sort, $resources, $resources_sort){
			if(!isset($name)||strlen($name)==0){
				$status[0]=false;
				$status[1]="Name is required.";
				return $status;
			}

			$rl = new Resource_List($this->db, $id);
			$rl->set_name($name);
			$rl->set_heading($heading);
			$rl->set_display_name($display_name);
			$rl->set_icon_path($icon);
			$rl->save();	
			
			
						
			if(is_array($resources)){
				$curr_resources = $rl->get_items();
				foreach($curr_resources as $curr){
					if(!in_array($curr->get_id(), $resources))$rl->remove_item($curr);
				}
			
				foreach($resources as $key=>$elem){
					$res = new Resource($this->db, $elem);
					if(isset($resources_sort[$key])&&is_numeric($resources_sort[$key]))$sort = $resources_sort[$key];
					else $sort = 100;
					$res->set_sort_order($sort);
					$rl->add_item($res);
				}
			}
			else{
				$curr_resources = $rl->get_items();
				foreach($curr_resources as $curr){
					$rl->remove_item($curr);
				}
			}
		
			
			if(is_array($collections)){
				$curr_collections = $this->get_resource_list_collection_memberships($rl);
				foreach($curr_collections as $curr){
					if(!in_array($curr['collection']->get_id(), $collections))$curr['collection']->remove_list($rl);
				}
			
				foreach($collections as $key=>$elem){
					$temp_list = new Resource_List($this->db, $rl->get_id());
					if(isset($collections_sort[$key])&&is_numeric($collections_sort[$key]))$sort = $collections_sort[$key];
					else $sort = 100;
					$temp_list->set_sort_order($sort);
					$coll = new Resource_List_Collection($this->db, $elem);
					$coll->add_list($temp_list);
					$coll->save();
				}
			}
			else{
				$curr_collections = $this->get_resource_list_collection_memberships($rl);
				foreach($curr_collections as $curr){
					$curr['collection']->remove_list($rl);
				}
			}
			$status[0]=true;
			$status[1]=$rl;
			return $status;
		}
		
		public function create_resource($name, $url, $author, $organization, $description, $type_id, $tags, $copyright, $time_estimate, $embed_code, $list, $sort){
			if(!isset($name)||!isset($url)||!isset($description)||strlen($name)==0||strlen($url)==0||strlen($description)==0){
				$status[0]=false;
				$status[1]="Name, URL, and Description are required fields.";
				return $status;
			}

			if(!filter_var($url, FILTER_VALIDATE_URL)){
				$status[0] = false;
				$status[1] = "URL is not properly formed.  Must include protocol (http:// or https://) and host name.";
				return $status;
			}
			
			$type = new Resource_Type($this->db, $type_id);
			if($type->get_id()==NULL){
				$status[0] = false;
				$status[1] = "Invalid resource type.";
				return $status;
			}
			
			$resource_list = Resource::get_all($this->db);
			foreach($resource_list as $rl){
				if ($rl->get_url()==$url){
					$status[0] = false;
					$status[1] = "URL must be unique";
					return $status;
				}
			}
			
			$tags = str_replace(" ", "", $tags);
			if(strlen($tags)>0)$tags = explode(",",$tags);
			
			$res = new Resource($this->db);
			$res->set_title($name);
			$res->set_url($url);
			$res->set_organization($organization);
			$res->set_author($author);
			$res->set_description($description);
			$res->set_type($type);
			$res->set_tags($tags);
			$res->set_copyright($copyright);
			if(is_numeric($time_estimate)&&$time_estimate>0)$res->set_time_estimate($time_estimate);
			$res->set_embed_code($embed_code);
			$res->save();
			
			if(is_array($list)){
				foreach($list as $key=>$elem){
					$rl = new Resource_List($this->db, $elem);
					if(!is_numeric($rl->get_id()))continue;
					$trl = new Resource($this->db, $res->get_id());
					if(is_array($sort)&&isset($sort[$key])&&is_numeric($sort[$key]))$trl->set_sort_order($sort[$key]);
					else $trl->set_sort_order(100);
					$rl->add_item($trl);
					$rl->save();
				}
			}
			
			
			$status[0] = true;
			$status[1] = $res;
			
			
			
			return $status;
		}
		
		public function edit_resource($id, $name, $url, $author, $organization, $description, $type_id, $tags, $copyright, $time_estimate, $embed_code, $list, $sort){
			$res = new Resource($this->db, $id);
			if(!is_numeric($res->get_id())){
				$status[0]=false;
				$status[1]="Invalid Resource.";
				return $status;
			}
			
			if(!isset($name)||!isset($url)||!isset($description)||strlen($name)==0||strlen($url)==0||strlen($description)==0){
				$status[0]=false;
				$status[1]="Name, URL, and Description are required fields.";
				return $status;
			}

			if(!filter_var($url, FILTER_VALIDATE_URL)){
				$status[0] = false;
				$status[1] = "URL is not properly formed.  Must include protocol (http:// or https://) and host name.";
				return $status;
			}
			
			$type = new Resource_Type($this->db, $type_id);
			if($type->get_id()==NULL){
				$status[0] = false;
				$status[1] = "Invalid resource type.";
				return $status;
			}
			
			
			$tags = str_replace(" ", "", $tags);
			if(strlen($tags)>0)$tags = explode(",",$tags);
			
			$res->set_title($name);
			$res->set_url($url);
			$res->set_organization($organization);
			$res->set_author($author);
			$res->set_description($description);
			$res->set_type($type);
			$res->set_tags($tags);
			$res->set_copyright($copyright);
			if(is_numeric($time_estimate)&&$time_estimate>0)$res->set_time_estimate($time_estimate);
			else $res->set_time_estimate(null);
			$res->set_embed_code($embed_code);
			$res->save();
			if(is_array($list)){
				foreach($list as $key=>$elem){
					$rl = new Resource_List($this->db, $elem);
					if(!is_numeric($rl->get_id()))continue;
					$trl = new Resource($this->db, $res->get_id());
					if(is_array($sort)&&isset($sort[$key])&&is_numeric($sort[$key]))$trl->set_sort_order($sort[$key]);
					else $trl->set_sort_order(100);
					$rl->add_item($trl);
					$rl->save();
				}
			}
			
			$list_memberships = $this->get_resource_list_memberships($res);
			foreach($list_memberships as $elem){
				$lm = $elem['list'];
				if(!in_array($lm->get_id(), $list)){
					$lm->remove_item($res);
				}
			}
			
			$status[0] = true;
			$status[1] = $res;
			
			
			
			return $status;
		}
		
		public function get_resource_list_memberships($resource){
			$lists = Resource_List::get_all($this->db);
			$members = array();
			foreach($lists as $l){
				$items = $l->get_items();
				foreach($items as $i){
					if($i->get_id()==$resource->get_id()){
						$members[$l->get_id()]['list'] = $l;
						$members[$l->get_id()]['sort'] = $i->get_sort_order();
					}
				}
			}
			return $members;
		}
		
		public function get_resource_list_collection_memberships($resource_list){
			$collections = Resource_List_Collection::get_all($this->db);
			$members = array();
			foreach($collections as $l){
				$items = $l->get_lists();
				foreach($items as $i){
					if($i->get_id()==$resource_list->get_id()){
						$members[$l->get_id()]['collection'] = $l;
						$members[$l->get_id()]['sort'] = $i->get_sort_order();
					}
				}
			}
			return $members;
		}
		
		public function resource_is_in_list($resource_list, $resource_id){
			foreach($resource_list->get_items() as $elem){
				if($elem->get_id()==$resource_id)return true;
			}
			return false;
		}
		
		public function list_in_collection($resource_collection, $list_id){
			foreach($resource_collection->get_lists() as $elem){
				if($elem->get_id()==$list_id)return true;
			}
			return false;
		}
		
		
		public function create_video_resource($video_id, $internal_title, $internal_notes, $title, $description, $transcript, $podcast_url, $chapters){
			if(!isset($internal_title)||strlen($internal_title)==0||!isset($video_id)||strlen($video_id)==0){
				$status[0]=false;
				$status[1]="Internal Title and Video ID are required fields.";
				return $status;
			}
			$video_list = Video_Resource::get_all($this->db);
			foreach($video_list as $cl){
				if($cl->get_internal_title()==$internal_title){
					$status[0]=false;
					$status[1]="Internal title must be unique.";
					return $status;
				}
				if($cl->get_video_id()==$video_id){
					$status[0]=false;
					$status[1]="Video ID must be unique.";
					return $status;
				}
			}
		
			$v = new Video_Resource($this->db);
			$v->set_video_id($video_id);
			$v->set_internal_title($internal_title);
			$v->set_internal_notes($internal_notes);
			$v->set_title($title);
			$v->set_description($description);
			$v->set_transcript($transcript);
			$v->set_podcast_url($podcast_url);
			$v->set_chapters($chapters);
			$v->save();
			$status[0] = true;
			$status[1] = $v;
			return $status;

		}
		
				
		public function edit_video_resource($id, $video_id, $internal_title, $internal_notes, $title, $description, $transcript, $podcast_url, $chapters){
			if(!isset($internal_title)||strlen($internal_title)==0||!isset($video_id)||strlen($video_id)==0){
				$status[0]=false;
				$status[1]="Internal Title and Video ID are required fields.";
				return $status;
			}
		
			$v = new Video_Resource($this->db, $id);
			$v->set_video_id($video_id);
			$v->set_internal_title($internal_title);
			$v->set_internal_notes($internal_notes);
			$v->set_title($title);
			$v->set_description($description);
			$v->set_transcript($transcript);
			$v->set_podcast_url($podcast_url);
			$v->set_chapters($chapters);
			$v->save();
			$status[0] = true;
			$status[1] = $v;
			return $status;

		}
		
		
		
	}

?>