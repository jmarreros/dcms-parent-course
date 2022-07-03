<?php

use dcms\parent\helpers\Helper;
use dcms\parent\includes\Database;


// Muestra los módulos del curso actual
// usar: [mostrar_modulos] como parte de una lección

add_action( 'init', 'dcms_add_shortcode_modules' );

function dcms_add_shortcode_modules(){
	add_shortcode('mostrar_modulos', 'dcms_show_modules');
}

function dcms_show_modules( $atts , $content ){
	$db = new Database;
	$course_id = $db->get_course_from_lesson(get_the_ID());

	$list_modules = $db->list_courses_and_modules($course_id);

	$str = "<ul class='modules-course'>";
	foreach ($list_modules as $module) {
		$str .= "<li><a href='".get_permalink($module->module_id)."'>";
		$str .= $module->module_title;
		$str .= "</a></li>";
	}
	$str .= "</ul>";
	
	return $str;
}
