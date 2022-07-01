<?php

namespace dcms\parent\includes;

// Class to filter courses in client area
// El plugin LMS por defecto ya muestra los cursos de primer nivel por defecto (id_parent = 0)
class Courses{

  public function __construct(){
    add_filter('stm_lms_get_user_courses_filter', [$this, 'exclude_modules_courses']);
  }

  // Exclude modules courses, parent_id <> 0 
  public function exclude_modules_courses($res){

    // TODO, revisión exclusión módulos

    error_log(print_r('Aqui 🚀', true));
    array_splice($res['posts'], 1, 1);
    error_log(print_r($res, true));
        
    return $res;
  }


}