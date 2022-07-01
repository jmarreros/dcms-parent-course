<?php

namespace dcms\parent\includes;

class Database{
    private $wpdb;

    public function __construct(){
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    // Get parent courses, exclude modules courses
    public function get_courses(){
      $sql = "SELECT ID, post_title
              FROM {$this->wpdb->posts}
              WHERE post_type = 'stm-courses'
                AND post_parent = 0
                AND post_status = 'publish'
              ORDER BY post_title";

      return $this->wpdb->get_results($sql);
    }

    // Get specific course by id
    public function get_course($course_id){
      $sql = "SELECT ID, post_title
              FROM {$this->wpdb->posts}
              WHERE post_type = 'stm-courses'
                AND post_parent = 0
                AND post_status = 'publish'
                AND ID = {$course_id}";

      return $this->wpdb->get_row($sql);
    }

    // Get product id from course id
    public function get_id_product_from_course( $id_course ){
      $sql = "SELECT meta_value id_product
              FROM {$this->wpdb->prefix}postmeta
              WHERE post_id = {$id_course}
              AND meta_key = 'stm_lms_product_id' LIMIT 1";

      return $this->wpdb->get_var($sql);
    }

    // Save parent course for a module course
    public function save_module_parent_course($id_module, $id_parent){
      $sql = "UPDATE {$this->wpdb->posts}
              SET post_parent = {$id_parent}
              WHERE ID = {$id_module}";

      return $this->wpdb->query($sql);
    }

    // Get parent for a specific module ID
    public function get_parent_course($id_module){
      $sql = "SELECT post_parent
              FROM {$this->wpdb->posts}
              WHERE ID = {$id_module}";

      return $this->wpdb->get_var($sql);
    }

    // List courses with modules
    public function list_courses_and_modules(){
      $sql = "SELECT pp.ID course_id, pp.post_title course_title,
                    pm.ID module_id, pm.post_title module_title
              FROM {$this->wpdb->posts} pp
              INNER JOIN {$this->wpdb->posts} pm ON pp.ID = pm.post_parent
              WHERE pp.post_type = 'stm-courses'
                    AND pm.post_type = 'stm-courses'
              ORDER BY course_title, module_title";

      return $this->wpdb->get_results($sql);
    }


    // Add user to course
    public function add_user_course($id_user, $id_course){
      $sql = "INSERT INTO {$this->wpdb->prefix}stm_lms_user_courses
            (`user_id`, `course_id`, `status`, `progress_percent`, `start_time`, `current_lesson_id`)
            VALUES ({$id_user}, {$id_course}, 'enrolled', 0, UNIX_TIMESTAMP(), 0)";

      return $this->wpdb->query($sql);
    }

    // Search ids modules by course
    public function get_modules_by_course($id_course){
      $sql = "SELECT pm.ID module_id
              FROM {$this->wpdb->posts} pp
              INNER JOIN {$this->wpdb->posts} pm ON pp.ID = pm.post_parent
              WHERE pp.ID = {$id_course} 
              AND pp.post_type = 'stm-courses'
              AND pm.post_type = 'stm-courses'";

      return $this->wpdb->get_col($sql);
    }

    // Validate if a usar has a specific module assigned
    public function user_has_module($id_user, $id_module){
      $sql = "SELECT count(user_course_id) 
              FROM {$this->wpdb->prefix}stm_lms_user_courses 
              WHERE user_id = {$id_user} AND course_id = {$id_module}";

      return $this->wpdb->get_var($sql);
    }

    // Remove course modules for a user
    public function remove_modules_user($id_user, $arr_modules){
      $module_ids = implode(',', $arr_modules);

      $sql = "DELETE FROM {$this->wpdb->prefix}stm_lms_user_courses 
              WHERE user_id = {$id_user} 
              AND course_id IN ( {$module_ids} )";

      return $this->wpdb->query($sql);
    }

}
