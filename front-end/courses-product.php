<?php
// Muestra la lista de cursos en el producto de pago flexible (DCMS_PARENT_ID_PRODUCT_MULTI_PRICES)
// Guarda el curso seleccionado como parte de la orden

use dcms\parent\includes\Database;

// Mostramos el control de selección de cursos en el producto con precios flexibles
add_action('woocommerce_before_add_to_cart_button', 'dcms_build_select_courses');
function dcms_build_select_courses(){
  global $product;
  $id_product = intval(get_option(DCMS_PARENT_ID_PRODUCT_MULTI_PRICES));

  if ( $product->get_id() !== $id_product  ) return;

  $db = new Database();
  $courses = $db->get_courses();
  $options = [ "0"  => 'Seleccionar'];

  foreach ($courses as $course) {
    $options[$course->ID] = $course->post_title;
  }

  woocommerce_form_field('course', array(
    'type'          => 'select',
    'label'         => 'Selecciona un curso',
    'required'      => true,
    'options'       => $options,
    ),'');
}

// Almacenamos el id del curso en los datos del ítem
add_filter( 'woocommerce_add_cart_item_data', 'dcms_extra_data_to_cart_item', 20, 2 );
function dcms_extra_data_to_cart_item( $cart_item_data, $product_id ){
    $course_id = intval($_POST['course']??0);
    if ( ! $course_id ) return $cart_item_data;

    $db = new Database();
    $course = $db->get_course($course_id);
    $course_name = $course->post_title;

    $cart_item_data['dcms-course'] = [
      'course_id' => $course_id,
      'course_name' => $course_name
    ];
    return $cart_item_data;
}

// Mostramos el curso en el ítem del carrito
add_filter( 'woocommerce_get_item_data', 'display_custom_cart_item_data', 10, 2 );
function display_custom_cart_item_data( $cart_data, $cart_item ) {
    if ( isset( $cart_item['dcms-course'] ) ){
        $cart_data[] = [
                'name' => 'Curso',
                'value' => $cart_item['dcms-course']['course_name']
            ];
    }
    return $cart_data;
}

// Para guardar los datos en el ítem de producto y que se muestren en las órdenes del backend
add_action( 'woocommerce_new_order_item', 'dcms_save_new_order_item', 10, 3 );
function dcms_save_new_order_item( $item_id, $item, $order_id ) {
  wc_add_order_item_meta($item_id, 'Curso-id',  $item->legacy_values['dcms-course']['course_id']);
  wc_add_order_item_meta($item_id, 'Curso-nombre',  $item->legacy_values['dcms-course']['course_name']);
};
