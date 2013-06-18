$('a[data-action="complain"]').click(function(){
    var feedbackId = $(this).data('feedback-id');
    $.getJSON(Routing.generate('api_feedback_add_complain'), {'feedback_id': feedbackId})
    .done(function (data) {

        if (data.complain == true){
            var block = ''+
            '<div class="row-fluid">'+
                '<div class="span12">'+
                        '<span class="icon-b4c-attention"></span>'+
                '</div>'+
            '</div>';
            $('div[data-answer-block="'+data.id+'"]').prepend(block);
            $('a[data-action="complain"][data-feedback-id="'+data.id+'"]').remove();
        }
    })
    .fail(function(e) {
        console.log('error', e.message);
    })

    return false;
});


$('a[data-action="admin_complain"]').click(function(){
    var feedback_id = $(this).data('feedback-id');
    $('div#feedback').data('type','feedback');
    $('a[data-modal-action="edit"]').css('visibility', 'visible');
    var feedback_text = $('[data-messages-block-edit="'+feedback_id+'"] > div > p').text();
    $('div#complainFeedbackAction > div.modal-body').text(feedback_text);
    $('a[data-modal-action]').each(function(n, element){
        console.log($(element).data('modal-action'));
        $(element).attr('data-feedback-id', feedback_id);
    });
    $('#complainFeedbackAction').modal('show');
    return false;
});

$('a[data-modal-action]').click(function(){
     var action = $(this).data('modal-action');
    var type = $(this).parents().data('type');
    var data_id ;

    if (type == 'answer') {data_id = 'answer-id'} else {data_id = 'feedback-id'}
        switch (action) {
            case ('hide'):
            setFeedbackParameter(data_id,$(this).data(data_id), $(this).data('field'), $(this).data('value'));
            break;
            case ('edit'):
            if ($(this).data('type')=="company"){
                var path = Routing.generate('content_company_feedbacks_edit', { id: $(this).data('feedback-id') });
            }
            else if ($(this).data('type')=="deal"){
                var path = Routing.generate('content_deal_feedbacks_edit', { id: $(this).data('deal-id'),feedbackId: $(this).data('feedback-id') });
            }
            window.location.replace(path);
            break;
            case ('delete'):
            deleteFeedback(data_id,$(this).data(data_id));
            break;
            case ('no_complain'):
            setFeedbackParameter(data_id,$(this).data(data_id), $(this).data('field'), $(this).data('value'));
            $('[data-complain-feedback="'+$(this).data(data_id)+'"]').remove();
            break;
        }
        $('#complainFeedbackAction').modal('hide');

        return false;
});

function setFeedbackParameter(type,id, field, value){

        $.getJSON(Routing.generate('api_feedback_set_parameter'), {'type':type,'id': id, 'field':field, 'value':value})
        .done(function (data) {
            console.log(data);
        })
        .fail(function(e) {
            console.log('error', e.message);
        })
    }


function deleteFeedback(type,id){
    $.getJSON(Routing.generate('api_feedback_delete'), {'type':type,'id': id})
    .done(function (data) {
        if (data.id){

            if (type == 'feedback-id') { $('div[data-feedback-block="'+data.id+'"]').remove();}
            else { $('blockquote[data-answer-block-edit="'+data.id+'"]').remove();}
        }
    })
    .fail(function(e) {
        console.log('error', e.message);
    })
}
