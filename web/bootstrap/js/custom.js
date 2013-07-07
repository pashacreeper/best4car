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
        $('.enterDropdown').toggle();
    }); 
    $('.btnEnter').click(function() {
        $('.enterDropdown').hide();
    });
    // User menu
    $('.userDropdown').hide();
    $('.btnUser').click(function() {
        $('.userDropdown').toggle();
    });
    // Registration popup
    $('#carOwnerRegister').on('click', function(){
        var $this = $(this),
            $registrationFormPopup = $('#registration-form-popup'),
            $registrationContainer = $('#registration-container');

        $this.parent().trigger('reveal:close');
        $registrationFormPopup.reveal($(this).data());
        $registrationContainer.empty();

        $.get(Routing.generate('fos_user_registration_register'), function(data){
            $registrationContainer.append(data);
        });
    });
};

var catalogPage = function(){
    // Отображаем и скрываем меню «В первый раз на сайте»
    if (CookieHandler.get('popup_for_new_closed')) {
        $('.popupFirstVisit').hide();
    };
    $('.popupClose').click(function() {
        $('.popupFirstVisit').hide();
        CookieHandler.set('popup_for_new_closed', true, (3*24*60*60), '/');
    });

    $('#advancedSearch').hide();
    $('#toggleAdvancedSearch').on("click", function(){
        var $this = $(this),
            defaultText = 'Показать расширенный поиск',
            closeText = 'Скрыть расширенный поиск',
            advancedSearch = $('#advancedSearch');

        $this.parent().toggleClass('showRightSearch');
        advancedSearch.toggle();
        if (advancedSearch.is(':hidden')) {
            $this.html(defaultText);
            $('#map').css('right', '0');
        } else {
            $this.html(closeText);
            $('#map').css('right', '340px');
        }
    });

    // Отрабатываем нажатие по «Только с акциями» в меню расширенного поиска
    // start
    jQuery(document).ready(function(){
        jQuery(".checkBox__autograf__wrap").mousedown(function() {
            changeCheck(jQuery(this));
        });
        jQuery(".checkBox__autograf__wrap").each(function() {
            changeCheckStart(jQuery(this));
        });
    });
    var changeCheck = function(el) {
        var el = el,
            input = el.find("input").eq(0);
        if(!input.attr("checked")) {
            el.css("background-position","0 -24px");    
            input.attr("checked", true)
        } else {
            el.css("background-position","0 0");    
            input.attr("checked", false)
        }
        return true;
    }
    var changeCheckStart = function (el) {
        var el = el,
        input = el.find("input").eq(0);
        if(input.attr("checked")) {
            el.css("background-position","0 -24px");    
        }
        return true;
    }
    // end
};

var dealsPage = function(){
    $('.actionItemLink').mouseenter(function() {                                  
        $(this).children('.actionItemBottomWrap').css("top", "90px");
    }); 
    $('.actionItemLink').mouseleave(function() {
        $(this).children('.actionItemBottomWrap').css("top", "120px");
    });
    // Deals menu
    $('.menuLeftBar').on('click', 'li', function(){
        var $this = $(this),
            $menu = $('.menuLeftBar'),
            point = $('<i class="subActive"></i>'),
            dealsType = $this.data('deal-type');

        if (! $this.hasClass('activeBar')) {
            $activeItem = $menu.find('.activeBar');
            $activeItem.find('i').remove();
            $activeItem.removeClass('activeBar');

            $this.addClass('activeBar');
            $this.append(point);
            loadDealsFromMenu($, Routing, dealsType);
        }
    });
};

var registrationPage = function(){
    $('#submitRegisterForm').on('click', function(){
        $('#registerForm').submit();
    });
};

var initPage = function(){
    mainLayout();
    catalogPage();
    dealsPage();
    registrationPage();
};

$(document).ready(function(){initPage()});