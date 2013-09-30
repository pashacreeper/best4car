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
        console.log('dddd');
        serviceSelectByType();
    });
});