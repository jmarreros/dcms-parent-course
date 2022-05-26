<?php

namespace dcms\parent\includes;

use dcms\parent\helpers\Helper;

class Configuration{

  public function __construct(){
      add_action('wp_ajax_dcms_save_id_product',[ $this, 'save_id_product' ]);
  }

  // Save api source
  public function save_id_product(){
    $nonce = $_POST['nonce']??'';
    Helper::validate_nonce($nonce, 'ajax-nonce-parent');

    $id_product = intval($_POST['id_product']??0);

    update_option(DCMS_PARENT_ID_PRODUCT_MULTI_PRICES, $id_product);

    $res = [
      'status' => 1,
      'message' => 'Se guardaron correctamente los datos '
      ];

    echo json_encode($res);
    wp_die();
  }

}