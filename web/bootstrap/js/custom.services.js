jQuery(document).ready(function() {
    var changeServiceLabel = "Изменить услуги";
    var collectionWrapper = $('#specializationsAddWrapper');
    var $addTagLink = $('<span class="addImg"><span class="linkContact">Добавить</span><i class="icon-plus-sign"></i></span>');
    var $newLink = $('<div class="contactDate"></div>').append($addTagLink);

    collectionWrapper.append($newLink);
    collectionWrapper.data('index', collectionWrapper.find('.contactDate').length - 1);

    collectionWrapper.on('click', '.deleteElement', function() {
        $(this).parents('.contactDate').remove();
    });
    collectionWrapper.on('click', '.addImg', function() {
        $deleteLink = $('<i class="icon-remove-circle deleteElement"></i>');

        var newForm = addTagForm(collectionWrapper, $newLink, true, $deleteLink);
        var index = collectionWrapper.data('index')-1;
        var serviceSelect = $("<select class='selected-auto-services' data-index='"+index+"' name='services["+index+"][]' style='display:none' multiple='multiple'></select>");

        collectionWrapper.find('.companySpecialization').each(function(index, element){
            if ($(element).find(':selected').val() != '') {
                var selectedId = parseInt($(element).find(':selected').val());
                newForm.find('option[value=' + selectedId + ']').remove();
            }
        });

        newForm.append(serviceSelect);

        var serviceLink = $("<a href='#' data-index='"+index+"' class='edit-services btn-select-services addPhotoBtn'>Выбор услуг</a>");
        newForm.find('.deleteElement').before(serviceLink);
        editServiceLink(serviceLink, index);
        return false;
    });

    $('select[data-index]').each(function(index, element){
        if ($(element).find(':selected').length) {
            $(element).next('a').html(changeServiceLabel);
        }
    });

    var editServiceLink = function(link, index) {
        var select = $('select[data-index='+index+']');
        select.parent().find('.companySpecialization').on('change', function(e) {
            if (e.originalEvent !== undefined) {
                $(select).html('');
                $(select).next('a').html("Выбор услуг");
            }
        });
        link.on('click', function(e) {
            e.preventDefault();
            if (select.parent().find('.companySpecialization').val()) {
                var $serviceModal = $('#service-choose-modal');

                $serviceModal.bind('reveal:open', function() {
                    $('#service-tree').jstree({
                        "plugins" : ["json_data", "checkbox", "themes", "ui"] ,
                        "checkbox": {
                            "two_state": true
                        },
                        "themes" : {
                            "theme" : "classic",
                            "icons" : false
                        },
                        "json_data" : {
                            "ajax" : {
                                "url" : Routing.generate('api_service_choice', {'specializationId': select.parent().find('.companySpecialization').val() })
                            }
                        }
                    }).bind("loaded.jstree", function(e, data) {
                        select.find('option').each(function(e) {
                            $('#service-tree').jstree('check_node', $('li#'+$(this).val()));
                        });
                        $('#service-tree').prepend('<span class="service-popup-type-title">Услуги для типа «' + selectedType + '»');
                    });
                    var selectedType = link.parent().first('select').find(':selected').html();
                });
                $serviceModal.off('click', '.serviceClose');
                $serviceModal.on('click', '.serviceClose',function(e){
                    e.preventDefault();

                    $serviceModal.trigger('reveal:close');
                });
                $serviceModal.off('click', '.serviceSave');
                $serviceModal.on('click', '.serviceSave' ,function(e){
                    e.preventDefault();

                    select.html('');
                    var hasSelected = false;
                    $('#service-tree').jstree('get_checked', null, true).each(function() {
                        var option = $('<option value='+$(this).attr('id')+'>'+$(this).attr('id')+'</option>').prop('selected', true);
                        select.append(option);
                        hasSelected = true;
                    });

                    if (hasSelected) {
                        link.html(changeServiceLabel);
                    }
                    $serviceModal.trigger('reveal:close');
                });
            } else {
                $('#service-tree').html("<h3>Для выбора услуг необходимо выбрать специализацию!</h3>");
            }

            $('#service-choose-modal').reveal();
        });
    };

    $('.edit-services').each(function() {
        var serviceId = $(this).data('index');
        editServiceLink($(this), serviceId)
    })
});