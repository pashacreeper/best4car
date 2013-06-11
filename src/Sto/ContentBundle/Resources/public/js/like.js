$('[data-like]').live('click', function () {
    var feedbackId = $(this).data('feedback');
    $.getJSON(Routing.generate('api_add_like'), {'feedback_id': feedbackId})
    .done(function (data) {
        $('[data-feedback="'+data.id+'"]').parent().find('span[data-like="count"]').empty().append(data.pluses);
        $('[data-feedback="'+data.id+'"]').parent().find('span[data-dislike="count"]').empty().append(data.minuses);
    })
    .fail(function(e) {
        console.log(e.message);
    })

    return false;
});

$('[data-dislike]').live('click', function () {
    var feedbackId = $(this).data('feedback');
    $.getJSON(Routing.generate('api_add_dislike'), {'feedback_id': feedbackId})
    .done(function (data) {
        $('[data-feedback="'+data.id+'"]').parent().find('span[data-like="count"]').empty().append(data.pluses);
        $('[data-feedback="'+data.id+'"]').parent().find('span[data-dislike="count"]').empty().append(data.minuses);
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
    $.get(Routing.generate('api_sort_filter'), {'sort-tab': sort, 'filter-tab': filter, 'company-id': getCompanyId()})
    .done(function (data) {
        $('[data-x-container="feedbacks"]').empty().append(data);
    })
    .fail(function(e){
        console.log(e.message);
    })
    return false;
});
