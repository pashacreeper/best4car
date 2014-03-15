var loadDeals = function($, Routing){
    $('#dealsContainer').on('click', '#showMoreDeals' ,function(e){
        e.preventDefault();

        var $this = $(this),
            page = parseInt($this.attr('rel')) + 1,
            dealType = $this.data('deal-type'),
            url = Routing.generate('deals_show');

        $this.parent().remove();
        $('[data-page]showMoreDeals').attr('rel', page + 1);
        $.ajax({
            type: 'POST',
            url: url + "?page=" + page + "&deal_type=" + dealType,
            success: function(html) {
                $('#dealsContainer').append(html);
                $('#dealsContainer .actionItemLink').each(function() {
                    var height = 140 - $(this).find('h4').height();
                    $(this).children('.actionItemBottomWrap').css("top", height+"px");
                });
            },
            error: function(e) {
                console.log(e.message);
            }
        });
        return false;
    });
};

var loadDealsFromMenu = function($, Routing, dealsType){
    $.ajax({
        type: 'POST',
        url: Routing.generate('deals_show'),
        data: {deal_type: dealsType, search: $('#inputSearchHidden').val()},
        success: function(res){
            $('#dealsContainer').empty();
            $('#dealsContainer').append(res);
        },
        error: function(e){
            console.log(e.message);
        }
    });  
};
