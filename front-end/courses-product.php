<?php
// Muestra la lista de cursos en el producto de pago flexible (DCMS_PARENT_ID_PRODUCT_MULTI_PRICES)
// Guarda el curso seleccionado como parte de la orden

use dcms\parent\helpers\Helper;
use dcms\parent\includes\Database;

// Mostramos el control de selección de cursos en el producto con precios flexibles
add_action( 'woocommerce_before_add_to_cart_button', 'dcms_build_select_courses' );
function dcms_build_select_courses() {
	global $product;
	$id_product = intval( get_option( DCMS_PARENT_ID_PRODUCT_MULTI_PRICES ) );

	if ( ! is_singular('product') OR $product->get_id() !== $id_product ) {
		return;
	}

	$db      = new Database();
    $only_own_courses = intval($_GET['own']??0);

    $options = [ "0" => 'Seleccionar' ];
    $user_id = get_current_user_id();

    if ( $only_own_courses && $user_id ) {
        $courses_user = $db->get_recent_courses_user( $user_id );
        foreach ( $courses_user as $course ) {
            $options[ $course->course_id ] = $course->post_title;
        }
    } else { // list only new courses
        $courses = $db->get_aviable_courses();
        foreach ( $courses as $course ) {
            $options[ $course->ID ] = $course->post_title;
        }
    }


	woocommerce_form_field( 'course', array(
		'type'     => 'select',
		'label'    => 'Selecciona un curso',
		'required' => true,
		'options'  => $options,
	), '' );
}

// Almacenamos el id del curso en los datos del ítem
add_filter( 'woocommerce_add_cart_item_data', 'dcms_extra_data_to_cart_item', 20, 2 );
function dcms_extra_data_to_cart_item( $cart_item_data, $product_id ) {
	$course_id = intval( $_POST['course'] ?? 0 );
	if ( ! $course_id ) {
		return $cart_item_data;
	}

	$db          = new Database();
	$course      = $db->get_course( $course_id );
	$course_name = $course->post_title;

	$cart_item_data['dcms-course'] = [
		'course_id'   => $course_id,
		'course_name' => $course_name
	];

	return $cart_item_data;
}

// Mostramos el curso en el ítem del carrito
add_filter( 'woocommerce_get_item_data', 'display_custom_cart_item_data', 10, 2 );
function display_custom_cart_item_data( $cart_data, $cart_item ) {
	if ( isset( $cart_item['dcms-course'] ) ) {
		$cart_data[] = [
			'name'  => 'Curso',
			'value' => $cart_item['dcms-course']['course_name']
		];
	}

	return $cart_data;
}

// Para guardar los datos en el ítem de producto y que se muestren en las órdenes del backend
add_action( 'woocommerce_new_order_item', 'dcms_save_new_order_item', 10, 3 );
function dcms_save_new_order_item( $item_id, $item, $order_id ) {

	$course_id   = $item->legacy_values['dcms-course']['course_id'] ?? 0;
	$course_name = $item->legacy_values['dcms-course']['course_name'] ?? '';

	if ( ! $course_id ) {
		return;
	}

	wc_add_order_item_meta( $item_id, 'curso_id', $course_id );
	wc_add_order_item_meta( $item_id, 'curso_nombre', $course_name );

	// Add metadata price and currency course
	$db = new Database;

	$default_currency = Helper::get_default_currency();
	$id_product       = $db->get_id_product_from_course( $course_id );

	$price = 0;
	if ( $id_product ) {
		$product = wc_get_product( $id_product );
		if ( $product ) {
			$price = $product->get_price();
		}
	}

	wc_add_order_item_meta( $item_id, 'curso_precio', $price );
	wc_add_order_item_meta( $item_id, 'curso_moneda', $default_currency );
}
