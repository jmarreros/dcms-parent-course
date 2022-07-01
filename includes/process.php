<?php

namespace dcms\parent\includes;

use dcms\parent\includes\Database;

// TODO, no hay un hook para eliminación manual de estudiante curso, 
// hay que hacer una opción de regularización para los módulos que no queden huérfanos

// Sólo se muestran los cursos con id_parent = 0, esto ya esta por defecto del propio plugin LMS

class Process{

    public function __construct(){
        add_action('add_user_course', [$this, 'add_user_modules_course'], 10, 2);
        add_action('stm_lms_woocommerce_order_cancelled', [$this, 'remove_user_modules_course_woo'], 10, 2 );
    }

    // Add student to modules
    public function add_user_modules_course($user_id, $course_id){
        $db = new Database;
        $module_ids = $db->get_modules_by_course($course_id);

        foreach ($module_ids as $module_id) {
            $count = $db->user_has_module($user_id, $module_id);
            
            if ( intval($count) === 0 ){
                $db->add_user_course($user_id, $module_id);
                $this->_add_remove_count_student($module_id);
            }
        }
    }

    // Remove students in modules
    public function remove_user_modules_course_woo($course, $user_id){
        if ( ! isset($course['item_id']) ) return false;

        $db = new Database;
        $course_id = intval($course['item_id']);
        $module_ids = $db->get_modules_by_course($course_id);

        if ( empty($module_ids) ) return false;

        $db->remove_modules_user($user_id, $module_ids);

        foreach ($module_ids as $module_id) {
            $this->_add_remove_count_student($module_id, false);
        }
    }

    // To increment/decrement count student in modules
    private function _add_remove_count_student($course_id, $add = true) {
        $current_students = get_post_meta($course_id, 'current_students', true);
        if (empty($current_students)) $current_students = 0;

        $current_students = $add ? $current_students + 1 : $current_students - 1;
        update_post_meta($course_id, 'current_students', $current_students);
    }

}


