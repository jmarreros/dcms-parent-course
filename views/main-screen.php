<?php

// Validations
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! current_user_can( 'manage_options' ) ) return; // only administrator

// Tabs definitions
$plugin_tabs = Array();
$plugin_tabs['config'] = __('Configuration', 'dcms-parent-course');
$plugin_tabs['advanced'] = __('Advanced', 'dcms-parent-course');

$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'config';

// Interfaz header
echo "<div class='wrap'>"; //start wrap
echo "<h1>" . __('Parent Course Functionality', 'dcms-parent-course') . "</h1>";

// Intefaz tabs
echo '<h2 class="nav-tab-wrapper">';
foreach ( $plugin_tabs as $tab_key => $tab_caption ) {
    $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
    echo "<a data-tab='".$current_tab."' class='nav-tab " . $active . "' href='".admin_url( DCMS_PARENT_SUBMENU . "?page=parent-course&tab=" . $tab_key )."'>" . $tab_caption . '</a>';
}
echo '</h2>';


// Partials
switch ($current_tab){
    case 'config':
        $id_product = get_option(DCMS_PARENT_ID_PRODUCT_MULTI_PRICES);
        include_once('partials/config.php');
        break;
    case 'advanced':
        include_once('partials/advanced.php');
        break;
}


echo "</div>"; //end wrap