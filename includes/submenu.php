<?php

namespace dcms\parent\includes;

/**
 * Class for creating a dashboard submenu
 */
class Submenu{
    // Constructor
    public function __construct(){
        add_action('admin_menu', [$this, 'register_submenu']);
    }

    // Register submenu
    public function register_submenu(){
        add_submenu_page(
            DCMS_PARENT_SUBMENU,
            __('Curso Padre LMS','dcms-parent-course'),
            __('Curso Padre LMS','dcms-parent-course'),
            'manage_options',
            'parent-course',
            [$this, 'submenu_page_callback']
        );
    }

    // Callback, show view
    public function submenu_page_callback(){
        include_once (DCMS_PARENT_PATH. '/views/main-screen.php');
    }
}