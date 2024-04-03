<?


if($CFG->_GET_PARAMS[1] == post)
{
    $sql = getSQLArrayO("SELECT DISTINCT name,status,mobile FROM my_tmp_whatsapp_rss WHERE whatsapp_id='{$_POST[id]}' ORDER BY id ASC");

    foreach($sql as $res)
    {
    	if( $res->status == 1)
        {
        	echo '<img src="/tpl/img/ok_on.png" style="width:16px; vertical-align:middle"><a href="/person/'.$res->mobile.'" target="_blank"> '.$res->mobile.' - '.$res->name.'</a><br>';
        }
    	if( $res->status == 0)
        {
        	echo '<img src="/tpl/img/ok_off.png" style="width:16px; vertical-align:middle"><a href="/person/'.$res->mobile.'" target="_blank"> '.$res->mobile.' - '.$res->name.'</a><br>';
        }

    }

exit;
}
elseif($CFG->_GET_PARAMS[1] == 'delete')
{
    $CFG->DB->query("DELETE FROM {$CFG->DB_Prefix}tmp_whatsapp_rss WHERE whatsapp_id='{$_POST[id]}' ");
    if($CFG->DB->query("DELETE FROM {$CFG->DB_Prefix}tmp_whatsapp WHERE id='{$_POST[id]}' "))
    echo 1;
    else 0;
exit;
}


?>

<?	$CFG->oPageInfo->html_title = 'Список Whatsapp рассылок'; ?>

<h2>Список Whatsapp рассылок</h2>

<style>
.extremum-slide {display:none;}
form.response { display:block; margin-bottom:20px;}
form.response button[type="submit"] {background: #F84241; color:#FFF; border:0; border-radius:3px !important; padding:5px 10px; margin-left:10px;}
table.price {  width:100%; margin:0 auto; border-collapse: collapse; font-size:12px; text-align:center; margin-bottom:20px;  font-family: 'segoeui'; }
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top; font-size:12px; color:#000;  }
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:13px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align:center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb';}
.ran_text,.ran_hello  { display:block; margin-bottom:5px; border-bottom:1px solid #CCC}
.extremum-click { display:block; margin-bottom:10px; font-family: 'segoeui_b'; cursor:pointer}
#res_data {background:# FFF; width:40%; height: auto; display:none;}
</style>


<div id="res_data">

</div>

<div class="white">

  <form method="GET" enctype="multipart/form-data" class="response" action="/whatsapp_new/static/">
      <select name="id" class="selectpicker show-tick" id="type">
      <option value="0">Все</option>
     <?
      $type_company = getSQLArrayO("SELECT id,name FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE visible='1' order by id ASC ");

      for($i=0;$i<sizeof($type_company);$i++)
      {
          ($_GET["id"] == $type_company[$i]->id) ? $sel = " selected" : $sel = ""; ?>
          <option value="<?=$type_company[$i]->id?>"<?=$sel?>><?=$type_company[$i]->name;?></option>
    	<? } ?>
      </select>
      <button type="submit">Выполнить</button>

  </form>

    <table class="price">
        <tr>
          <th>Автор, отправитель</th>
          <th style="width:200px;">Текст</th>
          <th>Картинка</th>
          <th>Дата созд.</th>
          <th>Критерии отправки</th>
          <th>Статус</th>
        </tr>
       <?

       $nums = 10;

       if(isset($_GET["id"]) && $_GET["id"] > 0)
       {
          $nr_user = ' AND namber = '.$_GET[id];
       }

       $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp WHERE visible='1' {$nr_user} order by id DESC limit {$nums}");

        for ($i=0; $i<sizeof($sql); $i++)
        {
          include('tr_item.php');
        }
       ?>

    </table>

    <style>
    .more { padding-top:20px; background:#fff; margin-bottom:50px; display: block; width: 100%; text-align: center;}
    .more .load_activs {padding:7px 20px; background: #F84241; display: inline-block; color: #fff; cursor: pointer;}
    </style>

    <? $cound_all = getSQLRowO("SELECT COUNT(id) as id FROM my_tmp_whatsapp WHERE visible = 1 "); ?>
    <div class="more">
      <div class="load_activs">Загрузить еще</div>
      <div class="static"> Всего <span><?=$cound_all->id;?></span> &nbsp; &nbsp; &nbsp; Загружено  <span class="rebut"><?=$nums;?></span></div>
    </div>

</div>





<script>


/* Загрузить еще. */
$(document).ready(function()
{
  var num = <?=$nums;?>;
  $('.load_activs').on('click', function(e)
  {
    <? if( isset($_GET['id']) && $_GET['id'] > 0 ) {$get_id = ', "id":'.$_GET['id'];} else {$get_id = '';}?>
    $.ajax
    ({
      url: "/whatsapp_new/load_activ/",
      type: "POST",
      data: {"num": num<?=$get_id;?>},
      cache: true,
      beforeSend: function()
      {
        $('#myModalBox').modal({backdrop: 'static', keyboard: false});

        $(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
      },
      success: function(response)
      {
        $(document).ready(function(){
          $("#myModalBox").modal('hide');
        });

        $( response ).appendTo(' table.price tbody' );

        //$('table.price tbody').append(response);

        num = num  + <?=$nums;?>;

        $('.static > .rebut').html(num);

        //$("html, body").stop().animate({scrollTop: $('.SPDOWN').offset().top - 0 + 'px'}, 500);

      }

    });
  });


  var limit = 20;
  $(document).on('click','.wp_id',function(e)
  {
  	var id = $(this).attr('data-id');
  	$.ajax
  	({
  		url: "/whatsapp_new/static_ajax/",
  		type: "POST",
  		data: {"id": id, "limit": limit},
  		cache: true,
  		success: function(response)
  		{
  			$("#res_data").html('<button type="button" data-fancybox-close="" class="fancybox-button fancybox-close-small" title="Close" autocomplete="off"><svg xmlns="http://www.w3.org/2000/svg" version="1" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"></path></svg></button>' + response);

        $('#res_data').off('click').on('click', '.more_stat', function(e)
        {
          limit = limit  + 20;

          $.post( "/whatsapp_new/static_ajax/", { id: id, limit: limit } ).done(function( data )
          {
            $("#res_data").html('<button type="button" data-fancybox-close="" class="fancybox-button fancybox-close-small" title="Close" autocomplete="off"><svg xmlns="http://www.w3.org/2000/svg" version="1" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"></path></svg></button>' + data);
          })

            e.preventDefault();
        });

  		}
  	});
  });


  function GetStat()
  {

  }

});
/* Загрузить еще. End*/







$(document).ready(function()
{
	var inProgress = false,
		startFrom = 12;

	var container = $(".project-list");

  $(window).scroll(function()
	{
        if( $(window).scrollTop() + $(window).height() >= $(document).height() && !inProgress )
		{
			inProgress = true;

			$.ajax({
				url: "",
				type: "POST",
				data: {"count": startFrom},
				cache: true,
				success: function(response)
				{
					inProgress = false;

					if( typeof response !== 'undefined' || response !== '' )
					{
						$(container).append(response);

						startFrom = startFrom + 12;
					}
				}
			});
        }
    });

});



$(document).on('click','.delete_wp',function(e)
{
	var id = $(this).attr('data-id');

	$.ajax
	({
		url: "/whatsapp_new/static/delete/",
		type: "POST",
		data: {"id": id},
		cache: true,
		beforeSend: function()
		{
			$('#myModalBox').modal({backdrop: 'static', keyboard: false});

			$(".modal-body").html('<h4 class="modal-title"><center>Удаляем...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
		},
		success: function(response)
		{
			if(response == 1)
			{
				$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Обновляем страницу...</center></h4></div>');
				setTimeout(function() {window.location.reload();}, 1000);
			}
			if(response == 0)
			{
				$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка, попробуйте еще раз :(</h4></div>');
			}

		}

	});

	//
});
$(document).ready(function() {
	$('.block').on('click', '.extremum-click', function()
	{
		//$(this).toggleClass('red').siblings('.extremum-slide').slideToggle(0);
		//$(this).html('Отмена');


		if(  $('.extremum-click').hasClass('red') )
		{
		 	$('.extremum-click').removeClass('red');
			$(this).toggleClass('red').siblings('.extremum-slide').slideToggle(0);
			$(this).html('Показать текст рассылки');
		}
		else
		{
			$('.extremum-click').addClass('red');
			$(this).toggleClass('red').siblings('.extremum-slide').slideToggle(0);
			$(this).html('Скрыть текст рассылки');
		}

	});
});

</script>
