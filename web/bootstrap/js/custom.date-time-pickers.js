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

$(document).ready(function(){
    datePicker();
});