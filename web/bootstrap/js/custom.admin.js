$(document).ready(function(){
    var serviceSelectByType = function() {
        $('.auto_service_select').each(function() {
            var optionsByType = [];
            $(this).find('optgroup').each(function() {
                var type = $(this).attr('label');
                optionsByType[type] = $(this).html();
            });
            var serviceSelect = $(this);
            var typeSelect = $(this).parents('.field-container').find('.auto_specialization_select');
            if(!typeSelect.length) {
                typeSelect = $(this).parents('.sonata-ba-collapsed-fields').find('.auto_specialization_select');
            }
            typeSelect.on('change', function() {
                var html = optionsByType[$(this).val()] ? optionsByType[$(this).val()] : ' ';
                serviceSelect.html(html);
            }).trigger('change');
        });
    };
    serviceSelectByType();
    $(document).on("dialogopen", ".ui-dialog", function(event, ui) {
        serviceSelectByType();
    });

    if($('.admin-service-select').length) {
        var modal = $("<div id='service-choose-modal' class='reveal-modal'></div>");
        modal.append($('<a href="#" class="close-reveal-modal"></a>'));
        modal.append($('<div class="content"><div id="service-tree"></div></div>'));
        $('body').append(modal);

        $('.admin-service-select').hide();
        var serviceLink = $("<a href='#' style='padding: 8px 10px;display: block;'>Выбор услуг</a>");
        serviceLink.on('click', function(e) {
            e.preventDefault();
            $('#service-choose-modal').bind('reveal:open', function() {
                $('#service-tree').jstree({
                    "plugins" : ["json_data", "checkbox", "themes", "ui"] ,
                    "themes" : {
                        "theme" : "classic",
                        "icons" : false,
                    },
                    "json_data" : {
                        "ajax" : {
                            "url" : Routing.generate('api_service_choice'),
                        }
                    },
                }).bind("loaded.jstree", function(e, data) {
                    $('.admin-service-select').find('option:selected').each(function() {
                        $('#service-tree').jstree('check_node', $('li#'+$(this).val()));
                    });
                });
            });
            $('#service-choose-modal').bind('reveal:close', function() {
                $('.admin-service-select option').prop('selected', false);
                $('#service-tree').jstree('get_checked', null, true).each(function() {
                    $('.admin-service-select option[value='+$(this).attr('id')+']').prop('selected', true);
                });
                $('#service-tree').jstree('get_checked', null, false).each(function() {
                    $('.admin-service-select option[value='+$(this).attr('id')+']').prop('selected', true);
                });
            });
            $('#service-choose-modal').reveal();
        });
        $('.admin-service-select').after(serviceLink);
    }
});