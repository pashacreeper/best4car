jQuery(document).ready(function(){
	function htmSlider(){
		/* Зададим следующие переменные */

		/* обертка слайдера */
		var slideWrap = jQuery('.slide-wrap');
		/* ссылки на предудыщий иследующий слайд */
		var nextLink = jQuery('.next-slide');
		var prevLink = jQuery('.prev-slide');

		var playLink = jQuery('.auto');
		
		/* ширина слайда с отступами */
		var slideWidth = jQuery('.slide-item').outerWidth();
		
		/* смещение слайдера */
		var newLeftPos = slideWrap.position().left - slideWidth;
		
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
		htmSlider();
	}
});