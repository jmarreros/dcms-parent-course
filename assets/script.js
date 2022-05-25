(function( $ ) {
	'use strict';

  // Save cron syn advanced configuration
  $('#product-id .button').click(function(e){
    e.preventDefault();

    let id_product = $('#id').val();

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
            $('#product-id .button').prop('disabled', true);
            $('#product-id .msg-btn').text('Enviando...');
            $('#product-id .dcms-spin').removeClass('hide');
        }
    })
    .done( function(res) {
        console.log(res);
        $('#product-id .msg-btn').text(res.message);
    })
    .always(function(){
        $('#product-id .button').prop('disabled', false);
        $('#product-id .dcms-spin').addClass('hide');
    });
  });

})( jQuery );


