var datePicker = function(){
    $('.init-ui-datepicker').datepicker({
        showOn: "both",
        buttonImage: "/bootstrap/img/calendar-additional-icon.png",
        buttonImageOnly: true,
        option: $.datepicker.regional[ "ru" ],
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd"
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
});