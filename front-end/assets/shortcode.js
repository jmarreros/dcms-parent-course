(function( $ ) {
	'use strict';
    $('.stm_lms_complete_lesson').hide();

    $('.btn-modules-complete').click(function(e){
        e.preventDefault();
        
        $.ajax({
            url : dcms_parent.ajaxurl,
            type: 'post',
            dataType: 'json',
            data: {
                action : 'dcms_validate_finish_modules',
                nonce : dcms_parent.parent,
                id_course: $('.btn-modules-complete').data('course')
            },
            beforeSend: function(){
                $('.uncompleted.msg').removeClass('hide').text('Verificando...');
                $('.btn-modules-complete').addClass('disabled-link');
            }
        })
        .done( function(res) {
            $('.uncompleted.msg').html(res.message);
            
            if ( res.status == 1 ){
                $('.stm_lms_complete_lesson').show();
            }
        })
        .always(function(){
            $('.btn-modules-complete').removeClass('disabled-link');
        });
    });

})( jQuery );