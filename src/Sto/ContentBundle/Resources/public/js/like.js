$('[data-like]').on('click', function () {
    var feedbackId = $(this).data('feedback');
    $.getJSON(Routing.generate('api_add_like'), {'feedback_id': feedbackId})
    .done(function (data) {
        $('[data-feedback="'+data.id+'"]').parent().find('span[data-like="count"]').empty().append(data.pluses);
    })
    .fail(function (e) {
        console.log(e.message);
    })

    return false;
});
$('[data-dislike]').on('click', function () {
    var feedbackId = $(this).data('feedback');
    $.getJSON(Routing.generate('api_add_dislike'), {'feedback_id': feedbackId})
    .done(function (data) {
        $('[data-feedback="'+data.id+'"]').parent().find('span[data-dislike="count"]').empty().append(data.minuses);
    })
    .fail(function(e){
        console.log(e.message);
    })

    return false;
});
