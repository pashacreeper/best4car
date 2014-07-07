/*
** (c) Dimox | http://dimox.name/styling-select-boxes-using-jquery-css/
*/
var slbxUpdate = function($, select) {
	if($(select).prev('.selectbox').length) {
		$(select).prev('.selectbox').remove();
	}
	var option = $(select).find('option');
	var optionSelected = $(select).find('option:selected');
	var dropdown = '';
	var selectText = $(select).find('option:first').text();
	if (optionSelected.length) selectText = optionSelected.text();

	for (i = 0; i < option.length; i++) {
		var selected = '';
		var disabled = ' class="disabled"';
		if ( option.eq(i).is(':selected') ) selected = ' class="selected sel"';
		if ( option.eq(i).is(':disabled') ) selected = disabled;
		dropdown += '<li' + selected + '>'+ option.eq(i).text() +'</li>';
	}

	$(select).before(
		'<span class="selectbox" style="display: inline-block; position: relative">'+
			'<span class="select" style="float: left; position: relative; z-index: 10000"><span class="text">' + selectText + '</span>'+
				'<b class="trigger"><i class="arrow"></i></b>'+
			'</span>'+
			'<ul class="dropdown" style="position: absolute; z-index: 9999; overflow: auto; overflow-x: hidden; list-style: none">' + dropdown + '</ul>'+
		'</span>'
	).css({position: 'absolute', left: -9999});

	var ul = $(select).prev().find('ul');
	var selectHeight = $(select).prev().outerHeight();
	if ( ul.css('left') == 'auto' ) ul.css({left: 0});
	if ( ul.css('top') == 'auto' ) ul.css({top: selectHeight});
	var liHeight = ul.find('li').outerHeight();
	var position = ul.css('top');
	ul.hide();

	/* при клике на псевдоселекте */
	$(select).prev().find('span.select').click(function() {
	
		/* умное позиционирование */
		

		$('span.selectbox').css({zIndex: 1}).removeClass('focused');
		if ( $(this).next('ul').is(':hidden') ) {
			$('ul.dropdown:visible').hide();
			$('.trigger').addClass('actSel1');
			$(this).next('ul').show();
		} else {
			$(this).next('ul').hide();
			$('.trigger').removeClass('actSel1');
		}
		$(this).parent().css({zIndex: 3});
		return false;
	});

	/* при наведении курсора на пункт списка */
	$(select).prev().find('li:not(.disabled)').hover(function() {
		$(this).siblings().removeClass('selected');
	})
	/* при клике на пункт списка */
	.click(function() {
		$(this).siblings().removeClass('selected sel').end()
			.addClass('selected sel').parent().hide()
			.prev('span.select').find('span.text').text($(this).text())
		;
		option.prop('selected', false).eq($(this).index()).prop("selected", true);
		$(this).parents('span.selectbox').next().change();
		$('.trigger').removeClass('actSel1');
	});

	/* фокус на селекте при нажатии на Tab */
	$(select).focus(function() {
		$('span.selectbox').removeClass('focused');
		$(this).prev().addClass('focused');
	})
	/* меняем селект с клавиатуры */
	.keyup(function() {
		$(this).prev().find('span.text').text($(this).find('option:selected').text()).end()
			.find('li').removeClass('selected sel').eq($(this).find('option:selected').index()).addClass('selected sel')
		;
	});

};
var slbx = function($) {
$(function() {

  $(document).bind('click', function(e) {
		var clicked = $(e.target);
		if (!clicked.parents().hasClass('dropdown')) {
			$('span.selectbox ul.dropdown').hide().find('li.sel').addClass('selected');
			$('span.selectbox').removeClass('focused');
			$('.trigger').removeClass('actSel1');
		}
	});

	$('select.styled').each(function() {
		slbxUpdate($, $(this));
	});

})};
slbx(jQuery);