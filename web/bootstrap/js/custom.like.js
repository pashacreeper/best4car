(function(){
    $(document).on('click', '[data-like-feedback]', function(){
        var feedbackId = $(this).data('like-feedback');
        var feedbackType = $(this).data('data-like');
        if (!$(this).hasClass('disabledIconPositiv')) {
            $.getJSON(
                Routing.generate('api_add_like'), 
                {'feedback_id': feedbackId, 'feedback_type': feedbackType}
            ).done(function (data) {
                $('[data-like-feedback="'+data.id+'"]').find('span[data-like="count"]').empty().append(data.pluses);
                $('[data-dislike-feedback="'+data.id+'"]').find('span[data-dislike="count"]').empty().append(data.minuses);
                $('[data-dislike-feedback="'+data.id+'"]').addClass('disabledIconNegativ');
            }).fail(function(e) {
                console.log(e.message);
            });
        }
        return false;
    });

    $(document).on('click', '[data-dislike-feedback]', function(){
        var feedbackId = $(this).data('dislike-feedback');
        var feedbackType = $(this).data('data-dislike');
        if (!$(this).hasClass('disabledIconNegativ')) {
            $.getJSON(
                Routing.generate('api_add_dislike'), 
                {'feedback_id': feedbackId, 'feedback_type': feedbackType}
            ).done(function (data) {
                $('[data-like-feedback="'+data.id+'"]').find('span[data-like="count"]').empty().append(data.pluses);
                $('[data-dislike-feedback="'+data.id+'"]').find('span[data-dislike="count"]').empty().append(data.minuses);
                $('[data-like-feedback="'+data.id+'"]').addClass('disabledIconPositiv');
            }).fail(function(e){
                console.log(e.message);
            });
        }
        return false;
    });
})();