var datePicker = function(){
    $('.init-ui-datepicker').each(function(i, element) {
        $(element).attr('type', 'text');
    });

    $('.init-ui-datepicker').datepicker({
        showOn: "both",
        buttonImage: "/bootstrap/img/calendar-additional-icon.png",
        buttonImageOnly: true,
        option: $.datepicker.regional[ "ru" ],
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        shortYearCutoff: "+40",
        yearRange: "1970:" + (new Date().getFullYear() + 1)
    });
};

var timePicker = function(){
    $('.init-ui-time').timepicker({
        showOn: 'both',
        showPeriodLabels: false,
        hourText: 'Часы',
        minuteText: 'Минуты'
    });
}

$(document).ready(function(){
    datePicker();

    $('#timepicker1').click(function() {
        $(this).parents('.input-append').find('.init-ui-time').focus();
    });

    $('#timepicker2').click(function() {
        $(this).parents('.input-append').find('.init-ui-time').focus();
    });
});