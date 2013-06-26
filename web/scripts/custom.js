var catalogPage = function(){
    $('.navTopItem').mouseenter(function() {                                  
        $(this).children().css("opacity", "1");
    });
    $('.navTopItem').mouseleave(function() {
        $(this).children().css("opacity", "0.5");
    });
};

var initPage = function(){
    catalogPage();
};

$(document).ready(function(){initPage()});