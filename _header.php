<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title><?=ecrane($CFG->oPageInfo->html_title)?> - CRM система</title>

    <link rel="stylesheet" type="text/css" href="/tpl/css/font-awesome/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

    <link rel="icon" href="/tpl/img/favicon.png" type="image/x-icon" />

    <link href="/tpl/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/tpl/bootstrap/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/tpl/css/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/tpl/css/jquery.scrollbar.css" />

    <link rel="stylesheet" type="text/css" href="/tpl/css/aside.css?att=<?=date('Ymd');?>" />

	  <script type="text/javascript" src="/tpl/cdn/jquery.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


  <script type="text/javascript" src="/tpl/js/clipboard.min.js"></script>
  <script type="text/javascript" src="/tpl/js/jquery.inputmask.bundle.js"></script>
  <script type="text/javascript" src="/tpl/js/jquery.placeholder.min.js"></script>
  <script type="text/javascript" src="/tpl/js/jqTime.js"></script>
  <script type="text/javascript" src="/tpl/js/jquery.scrollbar.js"></script>
  <script type="text/javascript" src="/tpl/js/push.min.js"></script>


    <script type="text/javascript">
		jQuery(function($){$('.date > strong.jqTime').jqTime('interval', { utc: +4, exp: 'hh:MM:ss' }); });
        jQuery(function(){ jQuery('input[placeholder], textarea[placeholder]').placeholder();});
    </script>

    <link href="/tpl/player/css/jplayer.blue.monday.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/tpl/player/jquery.jplayer.min.js"></script>

     <!-- BOOTSTRAP JS -->
    <script type="text/javascript" src="/tpl/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/tpl/bootstrap/js/bootstrap-select.js"></script>

   	<script type="text/javascript" src="/tpl/js/hy-drawer.js"></script>



	<link rel="stylesheet" href="/tpl/cdn/jquery.fancybox.min.css" />
	<script src="/tpl/cdn/jquery.fancybox.min.js"></script>

<?
	for($i=0; $i<sizeof($this->headerblock); $i++)
	{
		echo $this->headerblock[$i];
	}
?>

</head>
<?
if($CFG->USER->USER_ID != 1)
{

  //echo ' Система временно не доступна';
  //exit;

}



	if($CFG->USER->USER_VIEW_LOADER == 1)
	{
		?>
        	<style type="text/css">
			.head-search,
			.container_new aside,
			.row.tab .col-md-3,
			.filter_hide.block,
			a.mailogo{ display:none !important}
			.container_new .content { width:1080px !important}
			.container_new .content br{ display:none !important}


@media screen and (min-width:100px) and (max-width:768px)
{
.container.header .users { z-index:10000000000000}
.container_new .content { width:100% !important}
.container_new .content br{ display: block !important}
}
			</style>

        <?

	}

?>


	<body>


  <div class="alert" role="alert">
  </div>


<style>
  @media screen {
  .hy-drawer-scrim {
    display: block;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    opacity: 0;
    z-index: 100000;
    pointer-events: none;
    background: rgba(0, 0, 0, 0.8);
    transform: translateX(0);
    -webkit-tap-highlight-color: transparent;
  }

  .hy-drawer-content {
    position: fixed;
    bottom: 0;
    top: 0;
    z-index: 1000000 !important;
    overflow-x: hidden;
    overflow-y: auto;
    contain: strict;
    width: 300px;
    background: #242731;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.25);
    transform: translateX(0);
    -webkit-overflow-scrolling: touch;

  }

  .hy-drawer-content.hy-drawer-left {
    left: -300px;
  }

  .hy-drawer-content.hy-drawer-right {
    right: -300px;
  }

  .hy-drawer-content.hy-drawer-left.hy-drawer-opened {
    left: 0!important;
    transform: translateX(0)!important;
  }

  .hy-drawer-content.hy-drawer-right.hy-drawer-opened {
    right: 0!important;
    transform: translateX(0)!important;
  }
}

@media print {
  .hy-drawer-scrim {
    display: none!important;
  }

  .hy-drawer-content {
    transform: none!important;
  }
}


  </style>



<? if($CFG->USER->USER_ID > 0) { ?>


<div class="container header">
	<div class="row">

        <div class="btn-open">
            <img src="/tpl/menu/list-icon.png" alt="" width="35" height="35" id="menuEl" href="#drawerEl" />
        </div>


		 <div class="col-md-3">
         	<a href="<?=$CFG->USER->USER_URL;?>"><img alt="" class="logo" src="/tpl/img/logo-info.png"></a>
         </div>
		 <div class="col-md-5">
            <div class="head-search">
                <form enctype="multipart/form-data"  action="javascript:void(null);" class="response data_form_search_input"  method="get">
                    <input type="text" name="q" value="<?=$_GET['search'];?>"
                    placeholder="Поиск по Лицам, Номер, Подсказка" id="q" >
                    <button type="submit" class="data_form_search"><span class="glyphicon glyphicon-search"></span></button>
                     <!--label style=" display:block; right:20%; cursor:pointer; position:relative; text-align: right; font-weight:100; color:#fff; font-size:10px; font-family: 'segoeui'; margin:0 !important"><input type="checkbox" class="option" name="option" value="0" style="width:10px; position:relative; top:4px; left:5px;"> Расширенный поиск</label!-->
                     <div class="rec_buyerS"></div>
                </form>

            </div>
         </div>

		 <div class="col-md-1">
         </div>

		 <div class="col-md-3">
         	<div class="users">
            	<div class="data">
            		<img alt="" src="<? echo $CFG->USER->USER_AVATAR;?>" class="avatar">
                	<span class="name"><? echo $CFG->USER->USER_NAME; ?><img alt="" src="/tpl/img/new/down.png"></span>
                </div>

                <div class="old">


                	<a href="/office/<?=$CFG->USER->USER_TAKS_ID;?>">Запись *<?=$CFG->USER->USER_TAKS_ID;?><span>Моя запись премирование</span></a>
                	<a href="/profile/view/<?=$CFG->USER->USER_ID;?>">Мой профиль<span>Вся информация о сотруднике</span></a>
                	<a href="/record/?search=&company=<?=$CFG->USER->USER_DIRECTOR_ID;?>&users=<?=$CFG->USER->USER_ID;?>">Мои записи<span>Все клиентские записи</span></a>
                  <? if($CFG->USER->USER_BOSS == 1){?><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания<span>Настройки</span></a><? }?>
                  <? if($CFG->USER->USER_ID == 536 || $CFG->USER->USER_ID == 1){?><a href="/profile/company/85/manager/">Самоначисление<span>Таблица начислений</span></a><? }?>


                	<a href="/auth/logout" class="exit">Выход</a>
                </div>
            </div>
         </div>

	</div>
</div>



<div class="container_new">
    <aside <? if($_COOKIE["show"] > 0) echo ' class="show" '; ?> id="drawerEl" data-prevent-default="true" data-mouse-events="true">

        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="offtask">+ <span class="name">Создать</span></span>
            </button>
            <ul class="dropdown-menu">
             	<li><a href="/profile/add_vacancy/">Создать компанию, ЮЛ</a></li>
                <li><a href="/profile/add_office/">Служебную запись</a></li>
                <li><a href="/profile/add_person/">Создать лицо</a></li>
                <li><a href="/receipt/">Товарный чек</a></li>
                <? $respon = getSQLRowO(" SELECT name FROM my_money_accounting_data WHERE user_id = '{$CFG->USER->USER_ID}' "); if(count($respon) > 0) {?> <li><a href="/accounting/add/">Приход / Расход</a></li> <? } ?>
                <li><a href="/edrive/add/">Добавить ЭлектроАВТО</a></li>
            </ul>
        </div>

        <ul class="menu">
        	<li <? if($CFG->oPageInfo->xcode == '') {echo 'class="act"';}?>><a href="/"><img alt="" src="/tpl/img/new/icon/1.png"> <span class="name">Главная</span></a></li>

          <li <? if($CFG->oPageInfo->xcode == 'help') {echo 'class="act"';}?>><a href="/help/"><img alt="" src="/tpl/img/new/icon/constitution.png"> <span class="name">Меморандум</span></a></li>

        	<li <? if($CFG->oPageInfo->xcode == 'person') {echo 'class="act"';}?>><a href="/person/?search=&company=<?=$CFG->USER->USER_DIRECTOR_ID;?>"><img alt="" src="/tpl/img/new/icon/2.png"> <span class="name">Лица</span></a></li>

        	<li <? if($CFG->oPageInfo->xcode == 'edrive') {echo 'class="act"';}?>><a href="/edrive/?car=0"><img alt="" src="/tpl/img/new/icon/auto_w.png"> <span class="name">ЭлектроАВТО</span></a></li>

         	<li <? if($CFG->oPageInfo->xcode == 'record') {echo 'class="act"';}?>><a href="/record/?company=<?=$CFG->USER->USER_DIRECTOR_ID;?>"><img alt="" src="/tpl/img/new/icon/2.png"> <span class="name">Компании</span></a> </li>

        	<li <? if($CFG->oPageInfo->xcode == 'deal') {echo 'class="act"';}?>><a href="/deal/"><img alt="" src="/tpl/img/new/icon/2.png"> <span class="name">Сделки</span></a></li>

          <li <? if($CFG->oPageInfo->xcode == 'office') {echo 'class="act"';}?>><a href="/office/?company=<?=$CFG->USER->USER_DIRECTOR_ID;?>"><img alt="" src="/tpl/img/new/icon/2.png"> <span class="name">Служебные</span></a></li>

          <!--li <? if($CFG->oPageInfo->xcode == 'taks') {echo 'class="act"';}?>><a href="/taks/<?=$CFG->USER->USER_ID;?>"><img alt="" src="/tpl/img/new/icon/2.png"> <span class="name">Мои задачи </span></a></li!-->

			    <li <? if($CFG->oPageInfo->xcode == 'fileboxs') {echo 'class="act"';}?>><a href="/fileboxs/"><img alt="" src="/tpl/img/new/icon/7.png"> <span class="name">FILE BOX</span></a></li>

        	<li <? if($CFG->oPageInfo->xcode == 'manager') {echo 'class="act"';}?>><a href="/manager/"><img alt="" src="/tpl/img/new/icon/4.png"> <span class="name">Активность всех</span></a></li>

          <li <? if($CFG->oPageInfo->xcode == 'structure') {echo 'class="act"';}?>><a href="/structure/"><img alt="" src="/tpl/img/new/icon/4.png"> <span class="name">Структура компании</span></a></li>

          <?
            $accounting = getSQLRowO("SELECT id FROM my_money_accounting_data_access WHERE user_id ='{$CFG->USER->USER_ID}'"); if($accounting->id > 0) {?><li <? if($CFG->oPageInfo->xcode == 'accounting') {echo 'class="act"';}?>><a href="/accounting/"><img alt="" src="/tpl/img/new/icon/6.png"> <span class="name">Бухгалтерия</span> <span class="badge"></span></a> </li> <? } ?>

          <? if($CFG->USER->USER_ID == $CFG->USER->USER_ACCOUNTING || $CFG->USER->USER_ID == 1) {?><li <? if($CFG->oPageInfo->xcode == 'cashback') {echo 'class="act"';}?>><a href="/cashback/list_view/"><img alt="" src="/tpl/img/new/icon/6.png"> <span class="name">Выдача кешбека</span></a> </li> <? } ?>

        	<li <? if($CFG->oPageInfo->xcode == 'whatsapp_new') {echo 'class="act"';}?>><a href="/whatsapp_new/static/"><img alt="" src="/tpl/img/new/icon/5.png"> <span class="name">WhatsApp рассылки</span></a></li>

          <? if($CFG->USER->USER_WAREHOUSE == 1) {?> <li <? if($CFG->oPageInfo->xcode == 'speedometer') {echo 'class="act"';}?>><a href="<?=sklad_act();?>&tabs=1&counts=2"><img alt="" src="/tpl/img/new/icon/10.png"> <span class="name">Склад и Реализация</span></a></li><? } ?>

           <? if($CFG->USER->USER_VIEW_SALARY == 1) {?> <li <? if($CFG->oPageInfo->xcode == 'salary') {echo 'class="act"';}?>><a href="/salary/?month=<?=date("m", strtotime("-1 month"))*1;?>&year=<?=date('Y')?>"><img alt="" src="/tpl/img/new/icon/grafic.png"> <span class="name">Финасововый анализ</span></a></li><? } ?>


          <li><a href="/static/notebook/"><img alt="" src="/tpl/img/new/icon/9.png"> <span class="name">Мой блокнот</span></a></li>

          <li class="turn"><a href="#" class="turn"> <span class="name">Свернуть</span> <span class="icon">«</span> <span class="expand">»</span></a> </li>


        </ul>

        <style>
		.reminderhtml {display:block;padding-top:20px; font-family: 'segoeui'; color: #3F414E;  }
		.reminderhtml a{ color: #3F414E; text-align:justify; display:block; font-size: 13px; padding-left:5px; padding-right:5px; padding-top:0px; padding-bottom:3px;}
		.reminderhtml .closes { float:right; width:12px; height:12px; background: no-repeat center url('/tpl/img/respons/5.png'); background-size:100%; cursor:pointer; margin-right:5px; margin-top:5px; position:relative; left:3px; top:3px;}
		.reminderhtml .obj { margin-top:20px; display:block; width:98%;  background:#fff bottom right no-repeat url("/tpl/img/stiks.png");background-size: 18%;}

		</style>

        <div class="reminderhtml">
        <?
			 $day_3 = 259200;
			 $day_10 = 864000;
			 $time = time();

			 $res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}reminderhtml WHERE user_id='{$CFG->USER->USER_ID}' AND status = 0 ORDER BY cdate DESC limit 50");
			 for ($i=0; $i<sizeof($res); $i++)
             {
				         $o = getSQLRowO("SELECT page_id FROM {$CFG->DB_Prefix}news WHERE id='{$res[$i]->page_id}'");
				         $z = getSQLRowO("SELECT text FROM {$CFG->DB_Prefix}comments WHERE id='{$res[$i]->coment_id}'");

				if($res[$i]->data == 3)
				{
					if($time > $res[$i]->time_start+$day_3)
					{
						echo '<div class="obj"><div class="closes" data-id="'.$res[$i]->id.'"></div><a href="'.getFullXCodeByPageId($o->page_id).$res[$i]->page_id.'#'.$res[$i]->coment_id.'">'. utf8_substr(strip_tags($z->text), 0, 85) .'...</a></div>';
					}
				}

				if($res[$i]->data == 10)
				{
					if($time > $res[$i]->time_start+$day_10)
					{
						echo '<div class="obj"><div class="closes" data-id="'.$res[$i]->id.'"></div><a href="'.getFullXCodeByPageId($o->page_id).$res[$i]->page_id.'#'.$res[$i]->coment_id.'">'. utf8_substr(strip_tags($z->text), 0, 85) .'...</a></div>';
					}
				}
			 }
		?>
        </div>

    </aside>


<script type="text/javascript">
	$(window).bind('resize', function(e)
	{
		var window_widht = $(window).width();

		if(window_widht < 768)
		{
			var ua = navigator.userAgent.toLowerCase();
			var isSafari = ua.indexOf('safari') > 0 && ua.indexOf('chrome') < 0;
			var isMobileSafari = isSafari && ua.indexOf('mobile') > 0;

			$('#drawerEl').drawer({ range: isMobileSafari ? [35, 150] : [0, 150], 	threshold: isSafari ? 0 : 10,	});

			$('#menuEl').click(function (e)
      {
			     e.preventDefault();
			     $('#drawerEl').drawer('toggle');
			});
		}
	});

	$(document).ready(function(e)
	{
		$(window).trigger('resize');
	});
</script>

	<div class="content<? if($_COOKIE["show"] > 0) echo ' show '; ?>">
   <? } ?>
