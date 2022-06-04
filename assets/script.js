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

  // Auxilitar functions
  MergeCommonRows($('#table-parents'));

  function MergeCommonRows(table) {
      let firstColumnBrakes = [];
      // iterate through the columns instead of passing each column as function parameter:
      // Except last 2 columns
      for(let i=1; i<=table.find('th').length-1; i++){
          let previous = null, cellToExtend = null, rowspan = 1;
          table.find("td:nth-child(" + i + ")").each(function(index, e){
              let jthis = $(this), content = jthis.text();
              // check if current row "break" exist in the array. If not, then extend rowspan:
              if (previous == content && content !== "" && $.inArray(index, firstColumnBrakes) === -1) {
                  // hide the row instead of remove(), so the DOM index won't "move" inside loop.
                  jthis.addClass('hidden');
                  cellToExtend.attr("rowspan", (rowspan = rowspan+1));
              }else{
                  // store row breaks only for the first column:
                  if(i === 1) firstColumnBrakes.push(index);
                  rowspan = 1;
                  previous = content;
                  cellToExtend = jthis;
              }
          });
      }
      // now remove hidden td's (or leave them hidden if you wish):
      $('td.hidden').remove();
  }


})( jQuery );


