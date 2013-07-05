$(document).ready(function(){
    $('data-x-container-feedbacks').on('click', $('a[data-action="complain"]'), function(){
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
        var filter = $('#data-filter').find(":selected").val(),
            sort = $('#data-sort').val(),
            entType = $('#sortDataType').attr('data-type');

        $.get(Routing.generate('api_sort_filter'), {'sort-tab': sort, 'filter-tab': filter, 'entity-id': getEntityId(), 'entity-type': entType})
        .done(function (data) {
            $('#data-x-container-feedbacks').empty().append(data);
        })
        .fail(function(e){
            console.log(e.message);
        })
        return false;
    });
});