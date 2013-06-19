(function ($) {
    $(".select2").select2();

    $( "#search-slider" ).slider({
        range: "max",
        min: 1,
        max: 10,
        value: 5,
        slide: function( event, ui ) {
            $( "input.rating" ).val( ui.value );
        },
        change: function( event, ui ) {
            $("input[data-serarch-form='rating']").val( ui.value).change();
        }
    });
    $("input.rating").val($( "#search-slider").slider("value"));

    doAjax = function() {
        var aURL = null;
        var aData = null;
        if (!$('#home_search').hasClass('hide')){
            aURL = Routing.generate('api_auto_get_companies_with_filter');
            $('[data-serarch-form="responce-type"]').val("html");
            aData = $('#advanced-search-form').serialize();
            $('[data-serarch-form="responce-type"]').val("json");
        } else {
            // aURL = Routing.generate('company_ajax_get_all');
            aURL = Routing.generate('api_auto_get_companies_with_filter', { "responce-type" : "html"});
        }
        $.ajax({
            type: 'GET',
            url: aURL,
            data: aData,
            success: function(html) {
                var root = $('#home_compalies');
                root.empty();
                root.append(html);
            },
            error: function(jqxhr, textStatus, errorThrown) {
                console.log(textStatus + ": " + errorThrown);
            }
        });
    }

    // Показ компаний списком
    $('.show-list').on("click", function(){
        doAjax();
        $('#home_compalies').toggleClass("hide");
    });

    $('.show-search').on("click", function () {
        if ($('select.company-type-search > option').length < 2) {
            $.getJSON(Routing.generate('api_dictionary_company_types'))
            .done(function (json) {
                $.each(json, function (index, type) {
                    $('select.company-type-search').append('<option value="'+type.id+'">'+type.name+'</option>');
                });
            })
            .fail(function( jqxhr, textStatus, error ) {
                console.log( "Request Failed: " + textStatus + ', ' + error);
            });
        };
        if ($('select.company-mark-search > option').length < 2) {
            $.getJSON(Routing.generate('api_auto_catalog_get_marks'))
            .done(function (json) {
                $.each(json, function (index, mark) {
                    $('select.company-mark-search').append('<option value="'+mark.id+'">'+mark.name+'</option>');
                });
            })
            .fail(function( jqxhr, textStatus, error ) {
                console.log( "Request Failed: " + textStatus + ', ' + error);
            });
        }
        $('#home_search').toggleClass("hide");
    });

    $('select.company-type-search').change(function () {
        $.getJSON(Routing.generate('api_dictionary_sub_company_types', { id: $('select.company-type-search').val() }))
        .done(function (json) {
            $('select.company-subtype-search').empty();
            $('select.company-subtype-search').append('<option value="0">Все</option>');
            $.each(json, function (index, subtype) {
                $('select.company-subtype-search').append('<option value="'+subtype.id+'">'+subtype.name+'</option>');
            });
        })
        .fail(function( jqxhr, textStatus, error ) {
            console.log( "Request Failed: " + textStatus + ', ' + error);
        });
    });

    $('[data-toggle] > .btn').live('click',function(){
        var self = $(this);
        var setValue = (self.hasClass('active')) ? 0 : 1;
        self.toggleClass(
            self.parent().attr("toggle-mode")
        );
        $('[data-toggle] > .btn').each(function(){
            var vv = ($(this).hasClass('active'))? 1: 0;
            $('[name="'+$(this).attr('toggle-item')+'"]').val(vv);
        });
        $('[name="'+self.attr('toggle-item')+'"]').val( setValue ).change();
    });

})(jQuery)
