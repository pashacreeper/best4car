var mainLayout = function(){
    // Top menu hover effect
    $('.navTopItem').mouseenter(function() {                                  
        $(this).children().css("opacity", "1");
    });
    $('.navTopItem').mouseleave(function() {
        $(this).children().css("opacity", "0.5");
    });
    // Login form
    $('.enterDropdown').hide();
    $('.enter').click(function() {
        $('.enterDropdown').show();
    }); 
    $('.btnEnter').click(function() {
        $('.enterDropdown').hide();
    });
    // User menu
    $('.userDropdown').hide();
    $('.btnUser').click(function() {
        $('.userDropdown').toggle();
    });
};

var catalogPage = function(){
    $('.popupClose').click(function() {
        $('.popupFirstVisit').hide();
    }); 
}

var initPage = function(){
    mainLayout();
    catalogPage();
};

$(document).ready(function(){initPage()});