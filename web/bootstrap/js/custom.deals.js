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
            },
            error: function(e) {
                console.log(e.message);
            }
        });
        return false;
    });
};
