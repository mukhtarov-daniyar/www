<? if($CFG->USER->USER_EXPENSES_LIL == 0)
{
	redirect("/");
}
$CFG->oPageInfo->html_title = 'Расходы';
?>

<h2><img alt="" src="/tpl/img/new/icon/7_red.png"> Расходная часть</h2>
<br>
<div class="row tabs">
	<div class="obj">
		<h1><a href="/salary/?month=<?=date("m", strtotime("-1 month"))*1;?>&year=<?=date('Y')?>">Финасововый анализ</a></h1>
	</div>

  <? if($CFG->USER->USER_EXPENSES_LIL == 1) {?>
  <div class="obj">
      <h1  class="active"><a href="/speedometer/lil/020440010420">Прибыльность Сделок</a></h1>
  </div>
  <? } ?>

	<div class="obj">
		<h1><a href="/bar_statistics/?cdate=<?=date('Y-m')?>">Molotok.kz Алматы</a></h1>
	</div>

	<div class="obj">
		<h1><a href="/bar_statistics/aluminum_rim/?year=<?=date('Y')?>&month=<?=date('m')?>">Алматы РиМ</a></h1>
	</div>

	<div class="obj">
		<h1><a href="/bar_statistics/pavlodar_asem/?year=<?=date('Y')?>&month=<?=date('m')?>">Павлодар Асем</a></h1>
	</div>

</div>

<div class="white">

	<nav class="navbar navbar-default">
	    <ul class="nav navbar-nav">
	      <li<? if($CFG->_GET_PARAMS[1] == '020440010420') { echo ' class="active" '; } ?>><a href="/speedometer/lil/020440010420">ЛИЛ</a></li>
	      <li<? if($CFG->_GET_PARAMS[1] == '140540003426') { echo ' class="active" '; } ?>><a href="/speedometer/lil/140540003426">ЛИД</a></li>
	      <li<? if($CFG->_GET_PARAMS[1] == '150340007552') { echo ' class="active" '; } ?>><a href="/speedometer/lil/150340007552">Оливин</a></li>
	    </ul>
	</nav>

<style>
.nav-stacked, .row.open_deal_windows {font-family: 'segoeui_sb';}
.row.open_deal_windows { padding-left: 15px;}
table.price {  width:100%; margin:0 auto; border-collapse: collapse; font-size:12px; text-align:center; margin-bottom:20px; margin-top:10px;  font-family: 'segoeui'; }
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top; font-size:12px; color:#000}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:13px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align:center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb'; cursor: pointer;}
table.price .sel.asc { background: #F78F8F !important;}
table.price .sel { background: #F47373 !important;}
</style>


	    <div class="row">

	        <div class="col-md-12">

	            <!-- tabs left -->
	            <div class="tabbable">
	                <ul class="nav nav-pills nav-stacked col-md-3">
										<?
										$cnt = 0;
										foreach($l as $res)
										{
											if($cnt == 0) {$act = ' class="active" ';} else {$act = '';}
											if($res->Сделка !='') {$res->Сделка = $res->Сделка;} else {$res->Сделка = 'Не прявязан к сделке';}

											$namber = explode("*", $res->Сделка);
											$sql = getSQLRowO("SELECT name_company FROM {$CFG->DB_Prefix}news WHERE id='{$namber[1]}'");

											?>
											<li<?=$act;?>><a href="#data_<?=$res->id;?>" data-id="<?=$res->id;?>" data-toggle="tab"><?=$sql->name_company;?> *<?=$namber[1];?></a></li>
											<?
											$cnt ++;
										}
										?>
	                </ul>

	                <div class="tab-content data_ajax col-md-9">
	                </div>
	            </div>
	            <!-- /tabs -->
	        </div>
	    </div>
	    <!-- /row -->

</div>


<script>


$(document).ready(function ()
{
	var navbox = $('.nav-pills');

	navbox.on('click', 'a', function (e)
	{
			var $this = $(this);
			e.preventDefault();
			window.location.hash = $this.attr('href');
			$this.tab('show');
	});

	function refreshHash()
	{
		navbox.find('a[href="'+window.location.hash+'"]').tab('show');
		var curStr = window.location.hash.replace(/[#data_]/g, "");
		refAjaxHash(curStr);
	}

	$(window).bind('hashchange', refreshHash);
	refreshHash();

	function refAjaxHash(id)
	{
		$('.data_ajax').html('');
		$.ajax
		({
			url: "/speedometer/lil_ajax_data/",
			type: "POST",
			data: {"id": id},
			cache: true,
			beforeSend: function()
			{
			$('.data_ajax').html('<center style="margin-top:50px;font-family: segoeui_sb;">Пожалуйста подождите, идет загрузка данных...</center><center><img src="/tpl/img/loading.gif"></center>');
			},
			success: function(response)
			{
					$('.data_ajax').html(response);
			}
		});
	}
});


$(function(){ $('[data-toggle="tooltip"]').tooltip(); });

(function($){
	$.fn.tsort=function(){
		var
			v=function(e,i){return $(e).children('td').eq(i).text()},
			c=function(i){return function(a,b)
				{
				var k = v(a,i).replace(/[ ]/g, ""), m = v(b,i).replace(/[ ]/g, "");
				return $.isNumeric(k)&&$.isNumeric(m)?k-m:k.localeCompare(m)}
			};
		this.each(function(){
			var
				th=$(this).children('thead').first().find('tr > th'),
				tb=$(this).children('tbody').first();

			th.click(function(){
				var r=tb.children('tr').toArray().sort(c($(this).index()));

				th.removeClass('sel'),$(this).addClass('sel').toggleClass('asc');
				if($(this).hasClass('asc'))r=r.reverse();
				for(var i=0;i<r.length;i++)tb.append(r[i])
			})
		})
	}
})(jQuery);

</script>
