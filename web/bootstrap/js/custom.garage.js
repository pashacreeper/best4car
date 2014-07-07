var Garage = {};

Garage = {
    modificationModal: null,
    modificationLink: null,
    carModificationList: null,
    customModificationInput: null,
    init: function () {
        var t = this;

        t.modificationModal = $('#modification-choose-modal');
        t.modificationLink = $('#other-modification');
        t.carModificationList = $('#sto_user_car_modification');
        t.customModificationInput = $('.customModificationInput');

        t.modificationModal.hide();

        t.filterModifications();
        t.events();
    },
    events: function() {
        var t = this;

        // Открывать попап кастомной модификации
        $('body').on('click', '#other-modification' ,function(e){
            e.preventDefault();
            t.modificationModal.reveal({dismissmodalclass: 'closeModal'});
        });

        // Сохраняем значение через ajax и добавляем полученный id на страницу
        t.modificationModal.on('click', '.saveModification', function (e) {
            e.preventDefault();
            var form = t.modificationModal.find('form').first();
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                success: function (data) {
                    if (data.error) {
                        form.remove();
                        t.modificationModal.find('.close-reveal-modal').after(data.html);
                    } else {
                        t.customModificationInput.val(data.id);
                        t.carModificationList.removeAttr('required');
                        t.carModificationList.find('#emptyOption').prop('selected', true);
                        $('#other-modification').html('Изменить модификацию')
                        t.modificationModal.trigger('reveal:close');
                        t.insertCustomModificationData(data);
                    }
                }
            })
        });
    },
    filterModifications: function () {
        var t = this,
            modelBlock = $('#sto_user_car_model'),
            model = modelBlock.val(),
            yearBlock = $('#sto_user_car_year'),
            year = yearBlock.val(),
            $select = $('#sto_user_car_modification');

        if(model && year) {
            $select.empty();
            $.getJSON(Routing.generate('api_auto_catalog_get_modifications_for_model_and_year', {id: model, year: year}))
                .done(function (json) {
                    $select.append('<option value="" id="emptyOption">Выбрать модификацию</option>');
                    $.each(json, function (index, mod) {
                        var option = $('<option value="' + mod.id + '">' + mod.name + '</option>');
                        if (t.modificationLink) {
                            if(t.modificationLink.data('selected') == mod.id) {
                                option.attr('selected', true);
                            }
                        }
                        $select.append(option);
                    });
                    if ($('#other-modification').length == 0) {
                        var custom = $('<br><a href="#" id="other-modification">Ввести другую модификацию</a>');

                        $select.after(custom);
                        t.modificationLink = $('#other-modification');
                    }
                    slbxUpdate($, $select);
                })
                .fail(function (jqxhr, textStatus, error) {
                    console.log("Request Failed: " + textStatus + ', ' + error);
                });
        }

        modelBlock.on('change', t.filterModifications);
        modelBlock.on('change', function() {
            slbxUpdate($, this);
        });
        yearBlock.on('change', t.filterModifications);
    },
    insertCustomModificationData: function(data) {
        var t = this,
            dataContainer = $('.customModificationInformation');

        if (dataContainer.length > 0) {
            dataContainer.remove();
        }

        if (data.modification) {
            $('#other-modification').after(data.modification)
        }
    }
};

$(document).ready(function(){
    Garage.init();
});
