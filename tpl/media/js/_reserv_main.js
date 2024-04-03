function slider(obj, el, step, speed, options) {
	if (!options) options = {};
	if (typeof(options.append) == 'undefined') options.append = false;
	if (typeof(options.width) == 'undefined') options.width = false;
	if (typeof(options.line) == 'undefined') options.line = '.line';
	
	if (!speed) speed = 2;
	if (options.width) {
		width = 0;
		$(obj + ' div ' + options.line + ' ' + el).each(function(){
			width += $(this).outerWidth(true); 
		});
		$(obj + ' div ' + options.line).css('width', width);
	}
	$(obj + ' div ' + options.line).html($(obj + ' div ' + options.line).html().replace(/\>[\s\r\n\t]+\</ig, '><'));
	if (options.append) $(obj + ' div ' + options.line).append('<div>&nbsp;</div>');
	var $el = $(obj + ' ' + options.line + ' ' + el + ':first-child');
	
	$(obj + ' .left')[0].onclick = function(){
		var $p = $el.prev(el) ? $el.prev(el) : $el;
		for (var i = 1; i < step; i ++)
			if ($p.prev(el)) $p = $p.prev(el);
			
		if ($p.length > 0) {
			$(obj + ' div:first').stop().animate({scrollLeft: $p[0].offsetLeft - $(obj + ' ' + options.line).css('paddingLeft').replace('px', '') - $p.css('marginLeft').replace('px', '')}, ($(obj + ' div:first')[0].scrollLeft - $p[0].offsetLeft) / speed, 'swing', function(){options.scrollLeft = $(this).scrollLeft();});
			$el = $p;
		}
	};
	$(obj + ' .right')[0].onclick = function(){
		if ($(obj + ' div:first').scrollLeft() >= $(obj + ' div:first')[0].scrollWidth - $(obj + ' div:first').width()) return;
		var $n = $el.next(el) ? $el.next(el) : $el;
		for (var i = 1; i < step; i ++)
			if ($n.next(el)) $n = $n.next(el);
		if ($n.length > 0) {
			$(obj + ' div:first').stop().animate({scrollLeft: $n[0].offsetLeft - $(obj + ' ' + options.line).css('paddingLeft').replace('px', '') - $n.css('marginLeft').replace('px', '')}, ($n[0].offsetLeft - $(obj + ' div:first')[0].scrollLeft) / speed, 'swing', function(){options.scrollLeft = $(this).scrollLeft();});
			$el = $n;
		}
	};
}
function showDropDown(a, b, c) {
	var $obj = $(a);
	var $menu = $(b);
	var $input = $(c);
	$menu.css({opacity: 0, display: 'block', width: $obj.outerWidth(), left: $obj.position().left, top: $obj.position().top});
	$menu.stop().animate({opacity: 1}, 300);
	$menu.mouseout(function(){
		$menu.to = setTimeout(function(){
			$menu.stop().animate({opacity: 0}, 300, null, function(){$menu.css('display', 'none')});
		}, 50);
	});
	$menu.mouseover(function(){
		clearTimeout($menu.to);
	});
	$menu.children('ul').children('a').each(function(){
		if ($(this).attr('data-value') == $input.val()) $(this).addClass('selected');
		else $(this).removeClass('selected');
		this.onclick = function(){
			$input.val($(this).attr('data-value'));
			$obj.val($(this).children('li').text());
			$menu.mouseout();
			return false;
		}
	});
}