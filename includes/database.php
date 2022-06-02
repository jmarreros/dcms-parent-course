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


    // Get product id from course id
    public function get_id_product_from_course( $id_course ){
      $sql = "SELECT meta_value id_product
              FROM {$this->wpdb->prefix}postmeta
              WHERE post_id = {$id_course} AND meta_key = 'stm_lms_product_id' LIMIT 1";
      return $this->wpdb->get_var($sql);
    }

}
