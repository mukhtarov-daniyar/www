function slider(obj, el, step, speed, options, after, before) {
	if (!options) options = {};
	if (typeof(options.append) == 'undefined') options.append = false;
	if (typeof(options.width) == 'undefined') options.width = false;
	if (typeof(options.line) == 'undefined') options.line = '.line';
	if (typeof(options.offset) == 'undefined') options.offset = 0;
	if (!after) after = function(){};
	if (!before) before = function(){};
	
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
			before();
			$(obj + ' div:first').stop().animate({
					scrollLeft: $p[0].offsetLeft - 
						$(obj + ' ' + options.line).css('paddingLeft').replace('px', '') - 
						$p.css('marginLeft').replace('px', '') + 
						($p.prev(el).length == 0 ? options.offset : 0)
				}, $(obj + ' > div').outerWidth() / speed, 'swing', function(){options.scrollLeft = $(this).scrollLeft();after();});
			$el = $p;
		}
	};
	$(obj + ' .right')[0].onclick = function(){
		if ($(obj + ' div:first').scrollLeft() >= $(obj + ' div:first')[0].scrollWidth - $(obj + ' div:first').width()) return;
		var $n = $el.next(el) ? $el.next(el) : $el;
		for (var i = 1; i < step; i ++)
			if ($n.next(el)) $n = $n.next(el);
		if ($n.length > 0) {
			before();
			$(obj + ' div:first').stop().animate({
				scrollLeft: $n[0].offsetLeft - 
					$(obj + ' ' + options.line).css('paddingLeft').replace('px', '') - 
					$n.css('marginLeft').replace('px', '') + 
					($n.next(el).length == 0 ? options.offset * (-1) : 0)
			}, $(obj + ' > div').outerWidth() / speed, 'swing', function(){options.scrollLeft = $(this).scrollLeft();after();});
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
			$input.change()
			return false;
		}
	});
}
function setResponse(id, obj) {
	obj = $(obj).parents('.comment');
	var username = $(obj).prev().children('a').children('h3').text();
	obj = obj.clone(true);
	obj.find('.controls').remove();
	obj.find('span:first').remove();
	$('#response').val(id);
	$('#response_label').html('<div>Ответ на комментарий: <div><h3>' + username + '</h3>' + obj.html() + '</div><a href="#" onclick="$(\'#response\').val(0);$(this).parent().remove();return false;">Отменить ответ</a></div>');
	$('html,body').animate({scrollTop: $('.comment_form').position().top - 60}, 400);
}

function loadSearchContent(url, q) {
	
	function ajax_pagination($obj){
		$obj.find('.pagination a').each(function(){
			this.onclick=function(){$obj.load(this.href, function(){ajax_pagination($obj);$('html,body').animate({scrollTop: $obj.position().top - 50}, 300);});return false;}
		});
	}

	var $news = $('#search_news');
	var $articles = $('#search_articles');
	var $blogs = $('#search_blogs');
	
	$blogs.html('<div class="loading"></div>');
	$articles.html('<div class="loading"></div>');
	$news.html('<div class="loading"></div>');
	
	$blogs.load(url + 'blog?q=' + encodeURIComponent(q), function(){ajax_pagination($blogs);});
	$articles.load(url + 'article?q=' + encodeURIComponent(q), function(){ajax_pagination($articles);});
	$news.load(url + 'news?q=' + encodeURIComponent(q), function(){ajax_pagination($news);});
}

$(function(){
	$('.comment_form form').each(function(){
		var self = this;
		this.onsubmit = function() {
			if ($(self.text).val().replace(/[^A-Za-zА-Яа-я0-9]+/ig, '') == '') {
				alert('Нельзя добавлять пустые и несодержательные комментарии');
				return false;
			}
			self.text.disabled = true;
			$.post(self.action, {text: $(self.text).val(), pid: $('#response').val()}, function(data){
				if (data && data != 'error') {
					$('.list.comments').html(data);
					$(self.text).val('');
					$('#response').val(0);
					$('#response_label').html('');
				} else if (data == 'error') {
					alert('Переданы неверные параметры, либо Вы не авторизованы');
				}
				self.text.disabled = false;
			}).error(function(){self.text.disabled = false;});
			return false;
		}
	});
	
});

$(function(){
	document.onkeypress = function(e){
		if (!e) e = window.event; 
		if((e.ctrlKey) && ((e.keyCode==10)||(e.keyCode==13))) {
			var mis = '';
			var scrollTop = 0;
			if(navigator.appName == 'Microsoft Internet Explorer'){
				if (document.selection.createRange()){
					var range = document.selection.createRange();
					mis = range.text;
				}
			} else {
				if (window.getSelection()) {
					mis = window.getSelection();
				} else if(document.getSelection()) {
					mis = document.getSelection();
				}
			}
			mis = mis.toString();
			var sTop = $('body').scrollTop() > 0 ? $('body').scrollTop() : $('html').scrollTop();
			var href = location.href.toString();
			$.post('/ajax/mistake', {s: sTop, t: mis, l: href}, function(){alert('Сообщение об ошибке принято. Спасибо за информацию!')});
			return false;
		}
		return true;
	}
});
