$(document).ready(function(){
    var countFeedbacks = function(){
        var feedbacksCount = $('#data-x-container-feedbacks').find('.reviewContentItem').length;
        $('#showedFeedbacks').html(feedbacksCount);
    }

    var feedbackRating = function(){
        $('.vote-wrap').each(function(index, element){
            $this = $(element); 
            ratingStars = $this.data('rating');
            if ('rating' in $this.find('input')) {
                $this.find('input').rating().rating('select', ratingStars).rating('disable');
            }
        });
    }

    $('data-x-container-feedbacks').on('click', 'a[data-action="complain"]', function(){
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
        });
    });

    $('.data-select').on('change', function() {
        var filter = $($('#data-filter').find(':selected')[1]).val(),
            sort = $('#data-sort').val(),
            entType = $('#sortDataType').attr('data-type');

        if (filter === undefined) {
            filter = 'all';
        }

        $.get(
            Routing.generate('api_sort_filter'), 
            {'sort-tab': sort, 'filter-tab': filter, 'entity-id': getEntityId(), 'entity-type': entType}
        ).done(function (data) {
            $('#data-x-container-feedbacks').empty().append(data);
            countFeedbacks();
            feedbackRating();
        }).fail(function(e){
            console.log(e.message);
        });

        return false;
    });

    $('a[data-feedback]').on('click', function(){
        var feed_id = $(this).data('feedback');
        $('div[data-answerform='+feed_id+']').toggle();
        $(this).css('display', 'none');
        return false;
    });

    $('a[data-abort-answer]').click(function(){
        var feed_id = $(this).data('abort-answer');
        $('div[data-answerform='+feed_id+']').toggle();
        $('a[data-feedback='+feed_id+']').css('display', 'block');
        return false;
    });

    $('input[data-submit-form]').click(function(){
        var feed_id = $(this).data('submit-form');
        var answer = $('form[name="form'+feed_id+'"] textarea[name=answer]').val();
        $.getJSON(Routing.generate('api_add_answer'),{'feedback_id': feed_id, 'answer':answer})
        .done(function(data){
            location.reload();
        })
        .fail(function(e){
            console.log(e.message);
        })

        return false;
    });

    $('a[data-action="complain"]').on('click', function(e){
        e.preventDefault();
        var $this = $(this),
            feedbackId = $(this).data('feedback-id');

        $.getJSON(
            Routing.generate('api_feedback_add_complain'), 
            {'feedback_id': feedbackId}
        ).done(function (data) {
            if (data.complain == true){
                $this.after($('<i class="complainAlert"></i>'));
                $this.before($('<span class="primaryLink complain">Пожаловаться</span>'));
                $this.remove();
            }
        }).fail(function(e) {
            console.log('error', e.message);
        });
    });

    countFeedbacks();
    feedbackRating();
});