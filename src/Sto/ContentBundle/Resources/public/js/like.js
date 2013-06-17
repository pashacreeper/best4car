$('a[data-like-feedback]').live('click', function () {
    var feedbackId = $(this).data('like-feedback');
    var feedbackType = $(this).data('data-like');
    $.getJSON(Routing.generate('api_add_like'), {'feedback_id': feedbackId, 'feedback_type': feedbackType})
    .done(function (data) {
        $('[data-like-feedback="'+data.id+'"]').parent().find('span[data-like="count"]').empty().append(data.pluses);
        $('[data-dislike-feedback="'+data.id+'"]').parent().find('span[data-dislike="count"]').empty().append(data.minuses);
    })
    .fail(function(e) {
        console.log(e.message);
    })

    return false;
});

$('a[data-dislike-feedback]').live('click', function () {
    var feedbackId = $(this).data('dislike-feedback');
    var feedbackType = $(this).data('data-dislike');
    $.getJSON(Routing.generate('api_add_dislike'), {'feedback_id': feedbackId, 'feedback_type': feedbackType})
    .done(function (data) {
        $('[data-like-feedback="'+data.id+'"]').parent().find('span[data-like="count"]').empty().append(data.pluses);
        $('[data-dislike-feedback="'+data.id+'"]').parent().find('span[data-dislike="count"]').empty().append(data.minuses);
    })
    .fail(function(e){
        console.log(e.message);
    })

    return false;
});

$('[data-tabs]').live('click', function() {
    var tabs = $(this).data('tabs');
    $(this).parent().parent().children().removeClass('active');
    $(this).parent().addClass('active');
    var filter = $('li.active > [data-tabs^="filter-"]').attr('data-tabs');
    var sort =$('li.active > [data-tabs^="sort-"]').attr('data-tabs');
    var entType = $('[data-type]').data('type');
    $.get(Routing.generate('api_sort_filter'), {'sort-tab': sort, 'filter-tab': filter, 'entity-id': getEntityId(), 'entity-type': entType})
    .done(function (data) {
        $('[data-x-container="feedbacks"]').empty().append(data);
    })
    .fail(function(e){
        console.log(e.message);
    })
    return false;
});
