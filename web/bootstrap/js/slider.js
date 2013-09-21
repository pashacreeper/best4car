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

        if (sliderItemCount == 1) {
            nextLink.remove();
            prevLink.remove();
            sliderWidth = jQuery('.slider').width();
            $('.slide-item').css("margin-left", slideWidth);
        } else if (sliderItemCount == 2 || sliderItemCount == 3) {
            clonedSlides = slideWrap.find('.slide-item').clone();
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
            });
        }
        
        /* Клики по ссылкам старт/пауза */
        playLink.click(function(){
            if(playLink.hasClass('play')){
                playLink.removeClass('play').addClass('pause');
                jQuery('.navy').addClass('disable');
                timer = setInterval(autoplay, 1000);
            } else {
                playLink.removeClass('pause').addClass('play');
                jQuery('.navy').removeClass('disable');
                clearInterval(timer);
            }
        });

    }

    /* иницилизируем функцию слайдера */
    if($('.slider').length) {
        htmSlider(5000);
    }
});