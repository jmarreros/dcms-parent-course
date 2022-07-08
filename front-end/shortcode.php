<?php

use dcms\parent\helpers\Helper;
use dcms\parent\includes\Database;


// Muestra los módulos del curso actual
// usar: [mostrar_modulos] como parte de una lección

add_action( 'init', 'dcms_add_shortcode_modules' );

add_action('wp_ajax_nopriv_dcms_validate_finish_modules','validate_finish_modules');
add_action('wp_ajax_dcms_validate_finish_modules','validate_finish_modules');

function dcms_add_shortcode_modules(){
	add_shortcode('mostrar_modulos', 'dcms_show_modules');
}

function dcms_show_modules( $atts , $content ){
	wp_enqueue_script('shortcode-script');

	$db = new Database;
	$course_id = $db->get_course_from_lesson(get_the_ID());

	$list_modules = $db->list_courses_and_modules($course_id);

	$str = "<ul class='modules-course'>";
	foreach ($list_modules as $module) {
		$str .= "<li><a data-mod='". $module->module_id ."' href='".get_permalink($module->module_id)."'>";
		$str .= $module->module_title;
		$str .= "</a></li>";
	}
	$str .= "</ul>";

	$str .='<a href="#" class="btn btn-default uncompleted btn-modules-complete" data-course="'. $course_id .'">
				<span>He completado todos los módulos</span>
			</a>';
	$str .= '<div class="uncompleted msg hide"></div>';
	
	return $str;
}

// Validate all finish modules to show the complete button
function validate_finish_modules(){
	$nonce = $_POST['nonce']??'';
	$id_course = $_POST['id_course']??0;
	$id_user = get_current_user_id();
	$str = '';
	$res = ['status' => 1, 
			'message' => 'Completaste todos los módulos, ya puedes completar este curso!'];	

    Helper::validate_nonce($nonce, 'ajax-nonce-parent');
	
	$db = new Database;
	$modules_info = $db->get_percent_module_user_course($id_course, $id_user);

	if ( $modules_info  !== false ) {
		foreach ($modules_info as $module_info) {
			if ( $module_info->progress_percent < 100 ){
				$str .= '- '. $module_info->title . ' (' .  $module_info->progress_percent . '%) <br>';
			}
		}
		
		if ( ! empty($str) ) {
			$res = [
				'status' => 0,
				'message' => 'Existen módulos incompletos: <br> '. $str
			];	
		}
	} else {
		$res = [
				'status' => 0, 
				'message' => 'No existen módulos para este curso'
			];
	}

	wp_send_json($res);
}