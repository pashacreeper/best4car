$(document).ready(function(){
    $('a[data-action="complain"]').click(function(e){
        e.preventDefault();
        var $this = $(this),
            feedbackId = $(this).data('feedback-id');

        $.getJSON(Routing.generate('api_feedback_add_complain'), {'feedback_id': feedbackId})
        .done(function (data) {
            if (data.complain == true){
                $this.after($('<i class="complainAlert"></i>'));
                $this.befor($('<span class="primaryLink complain">Пожаловаться</span>'));
                $this.remove();
            }
        })
        .fail(function(e) {
            console.log('error', e.message);
        })
    });
});