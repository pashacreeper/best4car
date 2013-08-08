var Validation = function(activeTabPane) {
    this.activeTabPane = activeTabPane;
    this.errorFlags = 0;
    this.checkForValidation = function(element){
        object = this;
        $element = $(element);
        value = 0;
        if ($element.val()) {
            value = $element.val();
        }
        if (value < 1) {
            object.errorFlags = object.errorFlags + 1;
            $element.parents('.contentLabel').addClass('error');
            object.activeTabPane.find('.alertSelect').show();

            $element.on('change', function(){
                $(this).parents().removeClass('error');
                object.errorFlags = object.errorFlags - 1;
                if (object.errorFlags == 0) {
                    object.activeTabPane.find('.alertSelect').hide();
                }
            });
        }
    }
};

$(document).ready(function(){
    $('a[data-toggle="tab"]').on('click', function (e) {
        e.preventDefault();
        var $this = $(this),
            next = $this.data('next'),
            $activeTabPane = $('.tab-pane.active'),
            $requiredInputs = $activeTabPane.find('input[required]'),
            $requiredSelects = $activeTabPane.find('select[required]'),
            validation = new Validation($activeTabPane),
            tabs = $('#stepRegistration');

        $requiredSelects.each(function(index, element){
            validation.checkForValidation(element);
        });

        $requiredInputs.each(function(index, element){
            validation.checkForValidation(element);
        });

        if (validation.errorFlags == 0) {
            $activeTabPane.removeClass('active');
            $(next).addClass('active');
            tabs.find('.active').removeClass('active');
            tabs.find('[data-content=' + next + ']').parent().addClass('active');
        }
    });
});