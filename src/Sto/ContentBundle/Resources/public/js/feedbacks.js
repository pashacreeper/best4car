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
    switch (action) {
        case ('hide'):
            setFeedbackParameter($(this).data('feedback-id'), $(this).data('field'), $(this).data('value'));
            break;
        case ('edit'):
            if ($(this).data('type')=="company"){
                var path = Routing.generate('content_company_feedbacks_edit', { id: $(this).data('feedback-id') });
            }
            else if ($(this).data('type')=="deal"){
                var path = Routing.generate('content_deal_feedbacks_edit', { id: $(this).data('feedback-id') });
            }
            window.location.replace(path);
            break;
        case ('delete'):
            deleteFeedback($(this).data('feedback-id'));
            break;
        case ('no_complain'):
            setFeedbackParameter($(this).data('feedback-id'), $(this).data('field'), $(this).data('value'));
            $('[data-complain-feedback="'+$(this).data('feedback-id')+'"]').remove();
            break;
    }
    $('#complainFeedbackAction').modal('hide');
    return false;
});

function setFeedbackParameter(id, field, value){
    $.getJSON(Routing.generate('api_feedback_set_parameter'), {'feedback_id': id, 'field':field, 'value':value})
    .done(function (data) {
        console.log(data);
    })
    .fail(function(e) {
        console.log('error', e.message);
    })
}

function deleteFeedback(id){
    $.getJSON(Routing.generate('api_feedback_delete'), {'feedback_id': id})
    .done(function (data) {
        if (data.id){
            $('div[data-feedback-block="'+data.id+'"]').remove();
        }
    })
    .fail(function(e) {
        console.log('error', e.message);
    })
}
