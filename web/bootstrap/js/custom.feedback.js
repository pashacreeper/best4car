$(document).ready(function(){
    $('.reviewAdditionalInfoButton').click(function() {
        var content_block = $(this).parents('.reviewCont').find('.reviewAdditionalInfo');
        if (content_block.is(':visible')) {
            content_block.hide();
            $(this).text('Показать дополнительную информацию');
        }
        else {
            content_block.show();
            $(this).text('Скрыть дополнительную информацию');
        }
        
        return false;
    });
    
    var countFeedbacks = function(){
        var feedbacksCount = $('#data-x-container-feedbacks').find('.reviewContentItem').length;
        $('#showedFeedbacks').html(feedbacksCount);
    }

    var feedbackRating = function(){
        $('.vote-wrap').each(function(index, element){
            $this = $(element); 
            ratingStars = $this.data('rating');
            if ('rating' in $this.find('input')) {
                $this.find('input').rating().rating('select', ratingStars - 1).rating('disable');
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
        var filter = $('select[data-filter-type]').val(),
            sort = $('select[data-sort-type]').val(),
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

    $('[data-admin-complain-menu="true"]').on('click', function(e){
        e.stopPropagation();

        var $this = $(this),
            popup = $('.popUpPrimary[data-feedback-id="'+$this.data('feedback-id')+'"]');

        popup.show();

        $(document).on('click', function(){
            if (popup.is(':visible')) {
                popup.hide();
            }
        });
    });

    var setFeedbackParameter = function(type, id, field, value){
        console.log('type: ' + type);
        console.log('id: ' + id);
        console.log('field: ' + field);
        console.log('value: ' + value);
        $.getJSON(
            Routing.generate('api_feedback_set_parameter'), 
            {'type':type, 'id': id, 'field':field, 'value':value}
        ).done(function (data) {
            console.log(data);
        }).fail(function(e) {
            console.log('error', e.message);
        });
    };

    var stateFeedback = function(type, id){
        $.getJSON(Routing.generate('api_feedback_state'), {'type':type, 'id': id})
        .done(function (data) {
            if (!data.hidden) {
                $('.badge.badge-warning[data-feedback-id="'+id+'"]').remove();
            } else {
                if (!$('.badge.badge-warning[data-feedback-id="'+id+'"]')) {
                    $('i[data-feedback-id="'+id+'"]').after('<span class="badge badge-warning" data-feedback-id="'+id+'">скрыт</span>');
                }
            }

            if (!data.complain) {
                $('i[data-feedback-id="'+id+'"]').remove();
            }
        }).fail(function(e) {
            console.log('error', e.message);
        });
    };

    var deleteFeedback = function(type, id){
        $.getJSON(
            Routing.generate('api_feedback_delete'), {'type':type, 'id': id}
        ).done(function (data){
            if (data.id){
                if (type == 'feedback-id') {
                    $('div[data-feedback-block="'+data.id+'"]').remove();
                }
            }
        }).fail(function(e) {
            console.log('error', e.message);
        })
    }

    $('[data-modal-action]').on('click', function(){
        var $this = $(this);
        var action = $this.data('modal-action');
        var type = $this.parents().data('type');
        var feedbackId = $this.data('feedback-id');
        var after = true;

        switch (action) {
            case ('hide'):
                setFeedbackParameter(
                    'feedback-id', feedbackId, $this.data('field'), $(this).data('value')
                );
                break;
            case ('edit'):
                if ($this.data('type')=="company"){
                    var path = Routing.generate('content_company_feedbacks_edit', { id: feedbackId });
                }
                else if ($this.data('type')=="deal"){
                    var path = Routing.generate('content_deal_feedbacks_edit', { id: $this.data('deal-id'), feedbackId: feedbackId });
                }
                window.location.replace(path);
                break;
            case ('delete'):
                deleteFeedback('feedback-id', feedbackId);
                after = false;
                break;
            case ('no_complain'):
                setFeedbackParameter('feedback-id', feedbackId, $this.data('field'), $this.data('value'));
                break;
        }
        if (after) {
            stateFeedback('feedback-id', feedbackId);
        }

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