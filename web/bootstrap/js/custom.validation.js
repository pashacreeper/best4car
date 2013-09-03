var Validation = function(activeTabPane) {
    this.activeTabPane = activeTabPane;
    this.errorFlags = 0;
    this.checkForValidation = function(element, type){
        object = this;
        $element = $(element);
        value = 0;

        if (type == 'collection') {
            value = $element.find('input').size();
        }
        else {
            if ($element.val()) {
                value = $element.val();
            }
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
    $('a[data-toggle="tab"]').on('click', function () {
        var $this = $(this),
            $activeTabPane = $('.tab-pane.active'),
            $requiredInputs = $activeTabPane.find('input[required]'),
            $requiredSelects = $activeTabPane.find('select[required]'),
            validation = new Validation($activeTabPane),
            tabs = $('#stepRegistration'),
            $oldTab = $(tabs.find('.active a').data('content'));

        if (!$this.hasClass('btnPrev')) {
            $requiredSelects.each(function(index, element){
                validation.checkForValidation(element);
            });

            $requiredInputs.each(function(index, element){
               validation.checkForValidation(element);
            });

            if ($('#workTimeAddWrapper:visible').size()) {
                validation.checkForValidation('#workTimeAddWrapper', 'collection');
            }

            if ($('#phoneAddWrapper:visible').size()) {
                validation.checkForValidation('#phoneAddWrapper', 'collection');
            }
        }

        if (validation.errorFlags == 0 || $this.hasClass('btnPrev')) {
            tabs.find('.active').removeClass('active');
            $oldTab.removeClass('active');
            $oldTab.hide();

            if ($this.hasClass('btnNext')) {
                tabs.find('[data-content=' + $this.data('next') + ']').parent().addClass('active');
                $($this.data('next')).addClass('active');
                $($this.data('next')).show();
            }
            else {
                $this.parents('li').addClass('active');
                $($this.data('content')).addClass('active');
                $($this.data('content')).show();
            }

        }

        return false;
    });
});