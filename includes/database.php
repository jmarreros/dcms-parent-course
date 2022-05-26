<?php

namespace dcms\parent\includes;

class Database{
    private $wpdb;

    public function __construct(){
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function get_courses(){
      $sql = "SELECT ID, post_title
              FROM {$this->wpdb->posts}
              WHERE post_type = 'stm-courses' AND post_parent = 0 AND post_status = 'publish'";
      return $this->wpdb->get_results($sql);
    }

    public function get_course($course_id){
      $sql = "SELECT ID, post_title
              FROM {$this->wpdb->posts}
              WHERE post_type = 'stm-courses' AND post_parent = 0 AND post_status = 'publish' AND ID = {$course_id}";

      return $this->wpdb->get_row($sql);
    }
}
