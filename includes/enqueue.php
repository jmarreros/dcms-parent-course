<?php

namespace dcms\parent\includes;

// Enqueu Cass
class Enqueue{

    public function __construct(){
        add_action('admin_enqueue_scripts', [$this, 'register_scripts_backend']);
    }

    // Backend scripts
    public function register_scripts_backend(){

        // Javascript
        wp_register_script('parent-script',
                            DCMS_PARENT_URL.'assets/script.js',
                            ['jquery'],
                            DCMS_PARENT_VERSION,
                            true);

        wp_localize_script('parent-script',
                            'dcms_parent',
                                [ 'ajaxurl'=>admin_url('admin-ajax.php'),
                                  'parent' => wp_create_nonce('ajax-nonce-parent')
                                ]);

        wp_enqueue_script('parent-script');

        // CSS
        wp_register_style('parent-style',
                            DCMS_PARENT_URL.'assets/style.css',
                            [],
                            DCMS_PARENT_VERSION );

        wp_enqueue_style('parent-style');
    }

}