<?php

namespace dcms\parent\includes;

use dcms\parent\includes\Database;

class Plugin{

    public function __construct(){
        register_activation_hook( DCMS_PARENT_BASE_NAME, [ $this, 'dcms_activation_plugin'] );
        register_deactivation_hook( DCMS_PARENT_BASE_NAME, [ $this, 'dcms_deactivation_plugin'] );
    }

    // Activate plugin - create options and database table
    public function dcms_activation_plugin(){
    }

    // Deactivate plugin
    public function dcms_deactivation_plugin(){
    }

}
