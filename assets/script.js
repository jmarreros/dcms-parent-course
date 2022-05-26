(function( $ ) {
	'use strict';

  // Save cron syn advanced configuration
  $('#id-product-section .button').click(function(e){
    e.preventDefault();

    const id_product = $('#id-product').val();

    $.ajax({
        url : dcms_parent.ajaxurl,
        type: 'post',
        dataType: 'json',
        data: {
            action : 'dcms_save_id_product',
            nonce : dcms_parent.parent,
            id_product,
        },
        beforeSend: function(){
            $('#id-product-section .button').prop('disabled', true);
            $('#id-product-section .msg-btn').text('Enviando...');
            $('#id-product-section .dcms-spin').removeClass('hide');
        }
    })
    .done( function(res) {
        console.log(res);
        $('#id-product-section .msg-btn').text(res.message);
    })
    .always(function(){
        $('#id-product-section .button').prop('disabled', false);
        $('#id-product-section .dcms-spin').addClass('hide');
    });
  });

})( jQuery );


