jQuery(document).ready(function(){
	function htmSlider(){
		/* ������� ��������� ���������� */

		/* ������� �������� */
		var slideWrap = jQuery('.slide-wrap');
		/* ������ �� ���������� ���������� ����� */
		var nextLink = jQuery('.next-slide');
		var prevLink = jQuery('.prev-slide');

		var playLink = jQuery('.auto');
		
		/* ������ ������ � ��������� */
		var slideWidth = jQuery('.slide-item').outerWidth();
		
		/* �������� �������� */
		var newLeftPos = slideWrap.position().left - slideWidth;
		
		/* ���� �� ������ �� ��������� ����� */
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

		/* ���� �� ������ �� ����������� ����� */
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
		
		
		/* ������� �������������� ��������� �������� */
		function autoplay(){
			slideWrap.animate({left: newLeftPos}, 500, function(){
				slideWrap
					.find('.slide-item:first')
					.appendTo(slideWrap)
					.parent()
					.css({'left': 0});
			});
		}
		
		/* ����� �� ������� �����/����� */
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

	/* ������������� ������� �������� */
	if($('.slider').length) {
		htmSlider();
	}
});