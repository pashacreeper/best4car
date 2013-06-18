$('a[data-action="complain-answer"]').click(function(){
    var answerId = $(this).data('answer-id');
    $.getJSON(Routing.generate('api_feedback_add_complain'), {'data':'answer','answer_id': answerId})
    .done(function (data) {
        if (data.complain == true){
            var block = ''+
            '<div class="row-fluid">'+
            '<div class="span12">'+
            '<span class="icon-b4c-attention"></span>'+
            '</div>'+
            '</div>';
            $('blockquote[data-answer-block-edit="'+data.id+'"]').prepend(block);
            $('a[data-action="complain-answer"][data-answer-id="'+data.answer_id+'"]').remove();
        }
    })
    .fail(function(e) {
        console.log('error', e.message);
    })

    return false;
});


$('a[data-action="admin_complain_answer"]').click(function(){
    var answer_id = $(this).data('answer-id');
    $('div#feedback').data('type','answer');
     $('a[data-modal-action="edit"]').css('visibility', 'hidden');
    var answer_text = $('[data-answer-block-edit="'+answer_id+'"] > div > p').text();
    $('div#complainFeedbackAction > div.modal-body').text(answer_text);
    $('a[data-modal-action]').each(function(n, element){
        console.log($(element).data('modal-action'));
        $(element).attr('data-answer-id', answer_id);
    });
    $('#complainFeedbackAction').modal('show');
    return false;
});


