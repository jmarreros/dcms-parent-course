<?php

namespace dcms\parent\helpers;

// Helper class
class Helper{

  // Validate security nonce
  public static function validate_nonce( $nonce, $nonce_name ){
    if ( ! wp_verify_nonce( $nonce, $nonce_name ) ) {
      $res = [
          'status' => 0,
          'message' => '✋ Error nonce validation!!'
      ];
      echo json_encode($res);
      wp_die();
    }
  }

  // Add apostrophes for array condition in sql query
  public static function add_apostrophes_array($arr){
    if ( empty($arr) ) return "''";

    $result_array = array_map ( function($item) {
      return "'" . $item . "'";
    }, $arr );

    return join(',', $result_array);
  }
}