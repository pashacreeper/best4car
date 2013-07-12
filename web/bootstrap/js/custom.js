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
    var showRegistrationPopup = function(linkElement){
        var $this = $(linkElement),
            $registrationFormPopup = $('#registration-form-popup'),
            $registrationContainer = $('#registration-container');

        $this.parent().trigger('reveal:close');
        $registrationFormPopup.reveal($(this).data());
        $registrationContainer.empty();

        $.get(Routing.generate('fos_user_registration_register'), function(data){
            $registrationContainer.append(data);
        });
    };
    $('#carOwnerRegister').on('click', function(){
        showRegistrationPopup(this);
    });
    $('#carOwnerRegisterFromTour').on('click', function(){
        showRegistrationPopup(this);
    });

    $('#resettingPassword').on('click', function(){
        var $this = $(this),
            $resettingContainer = $('#resetting-container');

        $resettingContainer.empty();

        $.get(Routing.generate('fos_user_resetting_request'), function(data){
            $resettingContainer.append(data);
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
    $('.linkFirstVisitPeople').on('click', function(){
        $('.bxslider1').bxSlider({
            mode: 'fade',
            captions: true
        });
    });
    $('.linkFirstVisitCompany').on('click', function(){
        $('.bxslider2').bxSlider({
            mode: 'fade',
            captions: true
        });
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
    $('#datetimepicker1').datetimepicker({
        pickTime: false
    });

    var appendIcons = function(link){
        var checkboxes = $(link).parent().find('.wrapper').find('input[type="checkbox"]:checked'),
            container = $('#' + $(link).data('container'));

        checkboxes.each(function(index, element){
            var element = $(element),
                labelText = element.next('label').html()
                checkedViewElement = '<li class="tagTicketServices"><span class="sto"></span>' + labelText + '</li>'

            container.append(checkedViewElement);
        });

        $(link).parent().trigger('reveal:close');   
    }

    $('#specializationSave').on('click', function(e){
        e.preventDefault();
        appendIcons(this);
    });
    $('#serviceSave').on('click', function(e){
        e.preventDefault();
        appendIcons(this);
    });

    $('body').on('click', '.inputTime', function(){
        $(this).datetimepicker({
            pickDate: false
        });
    });
};

var feedbackPage = function(){
    $('#datetimepicker1').datetimepicker({
        pickTime: false
    });
    $('#datetimepicker2').datetimepicker({
        pickTime: false
    });
}

var profilePage = function(){
    (function(){
        var tabContainers = $('.tabs'),
            tabLinksContainer = $('.tabs ul.tabNavigation'),
            url = document.URL;

        tabContainers.find('> div').hide();
        if (url.indexOf('profile/') + 1) {
            var hash = false;

            if (url.indexOf('#') + 1) {
                hash = url.substring(url.indexOf('#'));
            }

            if (hash) {
                tabContainers.find('[data-tab-id="'+hash+'"]').show();
                tabLinksContainer.find('a[href="' + hash + '"]').addClass('selected');
            } else {
                tabContainers.find('> div').hide().filter(':first').show();
                tabLinksContainer.find('a:first').addClass('selected');
            }
        }
        tabLinksContainer.find('a').click(function () {
            tabContainers.find('> div').hide(); // прячем все табы
            tabContainers.find('[data-tab-id="'+this.hash+'"]').show(); // показываем содержимое текущего
            $('.tabs ul.tabNavigation a').removeClass('selected'); // у всех убираем класс 'selected'
            $(this).addClass('selected'); // текушей вкладке добавляем класс 'selected'
        });
    })();
}

var initPage = function(){
    mainLayout();
    catalogPage();
    dealsPage();
    registrationPage();
    feedbackPage();
    profilePage();
};

$(document).ready(function(){initPage()});