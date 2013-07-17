var companiesList = function($, Routing){
    $(document).ready(function(){

        var mapHeight = $('#map').height(),
            footerHeight = $('section.footer').height();
        $('#companiesListContainer').height(mapHeight - footerHeight);

        var doAjax = function() {
            var aURL = null;
            var aData = null;
            if ($('#advancedSearch').is(':visible')){
                aURL = Routing.generate('api_auto_get_companies_with_filter', { "responce-type" : "html"});
                $('[data-serarch-form="responce-type"]').val("html");
                aData = $('#advanced-search-form').serialize();
                $('[data-serarch-form="responce-type"]').val("json");
            } else {
                aURL = Routing.generate('api_auto_get_companies_with_filter', { "responce-type" : "html"});
            }
            $.ajax({
                type: 'GET',
                url: aURL,
                data: aData,
                success: function(html) {
                    var root = $('#companiesListContainer');
                    root.empty();
                    root.append(html);
                    root.jScrollPane({
                        autoReinitialise: true,
                    });
                },
                error: function(jqxhr, textStatus, errorThrown) {
                    console.log(textStatus + ": " + errorThrown);
                }
            });
        };

        // Показ компаний списком
        $("#companiesList").hide();
        $('#toggleCompaniesList').on("click", function(){
            var $this = $(this),
                defaultText = 'Показать списком',
                closeText = 'Скрыть',
                companiesList = $('#companiesList');

            $this.parent().toggleClass('showLeftColumn');
            companiesList.toggle();
            if (companiesList.is(':hidden')) {
                $this.html(defaultText);
                $('#map').css('left', '0');
                $.removeData($('#companiesListContainer'), 'jsp');
            } else {
                $this.html(closeText);
                $('#map').css('left', '380px');
                doAjax();
            }
        });
    });
};