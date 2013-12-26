jQuery(document).ready(function(){
    function htmSlider(autoplayInterval){
        var slideWrap = jQuery('.slide-wrap');
        var nextLink = jQuery('.next-slide');
        var prevLink = jQuery('.prev-slide');
        var playLink = jQuery('.auto');
        var slideWidth = jQuery('.slide-item').outerWidth();
        var newLeftPos = slideWrap.position().left - slideWidth
        var timer = null;
        var sliderItemCount = jQuery('.slide-item').length;

        function changeCountPosition(){
            var index = slideWrap
                .find('.slide-item:nth-child(2)')
                .data('slide-index');

            $('#sliderDisplayPosition').find('.active').removeClass('active');
            $('#sliderDisplayPosition').find('li[data-count-index='+index+']')
                .addClass('active');
        }

        if (sliderItemCount > 1) {
            var sliderCounter = "<ul class='sliderDisplayPosition' id='sliderDisplayPosition'>";
            for (var i = 0; i < sliderItemCount; i++) {
                sliderCounter = sliderCounter + "<li data-count-index='"+(i+1)+"'>"+(i+1)+"</li>";
            };
            sliderCounter = sliderCounter + "</ul>"
            $('.slider').append($(sliderCounter));
            $('#sliderDisplayPosition').find('li:nth-child(2)').addClass('active');

            $('.slide-item').each(function(index, element){
                $(element).data('slide-index', index+1);
            });
        }

        if (sliderItemCount == 1) {
            nextLink.remove();
            prevLink.remove();
            sliderWidth = jQuery('.slider').width();
            $('.slide-item').css("margin-left", slideWidth);
        } else if (sliderItemCount == 2 || sliderItemCount == 3) {
            clonedSlides = slideWrap.find('.slide-item').clone(true);
            clonedSlides.addClass('clone');
            slideWrap.append(clonedSlides);
        }


        if (autoplayInterval && jQuery('.slide-item').length > 1) {
            timer = setInterval(autoplay, autoplayInterval);
        }
        
        /* Клик по ссылке на следующий слайд */
        nextLink.click(function(){
            if( nextLink.attr('name') == 'next' ) {
                nextLink.removeAttr('name');
                slideWrap.animate({left: newLeftPos}, 500, function(){
                    slideWrap
                        .find('.slide-item:first')
                        .appendTo(slideWrap)
                        .parent()
                        .css({'left': 0});
                        
                    changeCountPosition();
                });

                
                setTimeout(function(){ nextLink.attr('name','next') }, 600);
                clearInterval(timer);
                timer = setInterval(autoplay, autoplayInterval);
            }
        });

        /* Клик по ссылке на предыдующий слайд */
        prevLink.click(function(){
            if( prevLink.attr('name') == 'prev' ) {
                prevLink.removeAttr('name');
                slideWrap
                    .css({'left': newLeftPos})
                    .find('.slide-item:last')
                    .prependTo(slideWrap)
                    .parent()
                    .animate({left: 0}, 500);

                changeCountPosition();

                setTimeout(function(){ prevLink.attr('name','prev') }, 600);
                clearInterval(timer);
                timer = setInterval(autoplay, autoplayInterval);
            }
        });


        
        /* Функция автоматической прокрутки слайдера */
        function autoplay(){
            slideWrap.animate({left: newLeftPos}, 500, function(){
                slideWrap
                    .find('.slide-item:first')
                    .appendTo(slideWrap)
                    .parent()
                    .css({'left': 0});

                changeCountPosition();
            });
        }
        
        /* Клики по ссылкам старт/пауза */
        playLink.click(function(){
            return true;
        });

    }

    /* иницилизируем функцию слайдера */
    if($('.slider').length) {
        htmSlider(5000);
    }
});