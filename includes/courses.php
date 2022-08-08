<?php
namespace dcms\parent\includes;

use dcms\parent\includes\Database;

// Class to filter courses in client area
// El plugin LMS por defecto ya muestra sólo los cursos de primer nivel por defecto (id_parent = 0)
class Courses{

  public function __construct(){
    add_filter('stm_lms_get_user_courses_filter', [$this, 'exclude_modules_courses']);
  }

  // Exclude modules courses, parent_id <> 0 
  // In addition exists an override: wp-content/themes/masterstudy-child/stm-lms-templates/stm-lms-lesson.php
  public function exclude_modules_courses($res){
    $db = new Database;
    $i = 0;
    while( count($res['posts']) > $i ) {
        $id_course = $res['posts'][$i]['id'];
        $id_parent = $db->get_parent_course($id_course);

        // if is a module and user has the complete course, we have to remove
        if ( intval($id_parent) !== 0 &&  $this->user_has_course($id_parent) ){
            array_splice($res['posts'], $i, 1);
        } else {
            $i++;
        }
    }
    return $res;
  }

  // Validate if a user has a course o only has a individual module
  private function user_has_course($id_course){
    $db = new Database;
    $id_user = get_current_user_id();

    $count = $db->user_has_course($id_user, $id_course);
    
    return intval($count) > 0;
  }
}