<h2><img alt="" src="/tpl/img/new/icon/7_red.png"> <?=$CFG->oPageInfo->html_title;?></h2>
<div class="white">
<?


    $search =  rawurldecode($CFG->_GET_PARAMS[0]);

	if($CFG->_GET_PARAMS[0] == 'list_view')
	{
		include("./modules/cashback/tpl/body.tpl");
	}
	else if($search != "")
	{
		$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$search}%' AND visible='1' AND status = 1 ");

		for($z=0;$z<sizeof($sql);$z++)
		{
			$sumplus[] = $sql[$z]->price;
		}

		$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$search}%' AND visible='1' AND status = 6 ");

		for($y=0;$y<sizeof($sql);$y++)
		{
			$summinus[] = $sql[$y]->price;
		}

		$all = array_sum($sumplus) - array_sum($summinus);



		$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$search}%' AND visible='1' order by cdate DESC");

		for($z=0;$z<sizeof($sql);$z++)
		{
			$sumplusArray[] = $sql[$z];
		}



?>

     <div class="col-md-12 textS">
        <div class="obj">
        	<br clear="all">
            <p class="gray" style=" font-size:16px;font-family: 'segoeui_sb';"><strong>Доступный кешбек клиента по номеру <span style="color:#F00"><?=$search;?></span>  составляет: <span style="color:#F00"><? echo $all;?> </span> <?=$CFG->USER->USER_CURRENCY;?></strong></a></p>
        </div>

        <div class="obj">
        	<br clear="all">
            <p class="gray" style=" font-size:16px;font-family: 'segoeui_sb';">Операции: <? if($all == 0) { echo '<span style=" color:#4177A6; text-transform:uppercase; font-family: segoeui_sb; font-size:18px;">Баланс с клиентом нулевой!</span>';} ?></p>
            <?
				for($z=0;$z<sizeof($sumplusArray);$z++)
				{
					if($sumplusArray[$z]->status == 1)
					{
						?> <p class="gray" style=" color:#090; font-size:16px;font-family: 'segoeui_sb';"> <span>+</span> <?=dateSQL2TEXT($sumplusArray[$z]->cdate, "DD.MM.YYYY, hh:mm");?> зачислен кэшбэк в размере <?=$sumplusArray[$z]->price;?> <?=$CFG->USER->USER_CURRENCY;?></p> <?
					}
					if($sumplusArray[$z]->status == 0 || $sumplusArray[$z]->status == 2)
					{
						?> <p class="gray" style=" color:#F00; font-size:16px;font-family: 'segoeui_sb';"> <span>-</span> <?=dateSQL2TEXT($sumplusArray[$z]->cdate, "DD.MM.YYYY, hh:mm");?> требует одобрения кэшбэк в размере <?=$sumplusArray[$z]->price;?> <?=$CFG->USER->USER_CURRENCY;?> <span style=" color:#F00; font-size:14px;font-family: 'segoeui';">(не одобрен)</span></p> <?
					}

					if($sumplusArray[$z]->status == 3)
					{
						?> <p class="gray" style=" color:#F00; font-size:16px;font-family: 'segoeui_sb';"> <span>-</span> <?=dateSQL2TEXT($sumplusArray[$z]->cdate, "DD.MM.YYYY, hh:mm");?> выдача кэшбэка в размере <?=$sumplusArray[$z]->price;?> <?=$CFG->USER->USER_CURRENCY;?> не одобренна!</p> <?
					}

					if($sumplusArray[$z]->status == 4 || $sumplusArray[$z]->status == 5)
					{
						?> <p class="gray" style=" color:#F00; font-size:16px;font-family: 'segoeui_sb';"> <span>-</span> <?=dateSQL2TEXT($sumplusArray[$z]->cdate, "DD.MM.YYYY, hh:mm");?> ожидает выдачи кэшбэка в размере <?=$sumplusArray[$z]->price;?> <?=$CFG->USER->USER_CURRENCY;?></p> <?
					}

					if($sumplusArray[$z]->status == 4 || $sumplusArray[$z]->status == 6)
					{
						?> <p class="gray" style=" color:#F00; font-size:16px;font-family: 'segoeui_sb';"> <span>-</span> <?=dateSQL2TEXT($sumplusArray[$z]->cdate, "DD.MM.YYYY, hh:mm");?> выдан кэшбэк в размере <?=$sumplusArray[$z]->price;?> <?=$CFG->USER->USER_CURRENCY;?></p> <?
					}

				}
			?>
        </div>
    </div>

<br clear="all">
<style type="text/css">
	.cashback_from {font-family: 'segoeui_sb'; padding: 7px 15px;background: #F8403E;border-radius: 5px;color: #FFF;cursor: pointer;font-size: 14px;margin: 0 auto; text-transform: uppercase;border: 0;margin-top: 18px; display: inline-block; margin-left:15px;}
</style>

<div class="cashback_from">Выдать кешбек</div>


<br clear="all">


<?	$sql = array_reverse($sql);	?>

</div>




<script type="text/javascript">
	$('.cashback_from').on('click', function(e)
	{
		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});

		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Введите сумму кешбека не более <?=$all;?> <?=$CFG->USER->USER_CURRENCY;?></h4></div>');
		$(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control name" value="<?=$all;?>"  placeholder="Сумма" required autofocus="autofocus"></p>');
		$(".modal-body").append('<p>Комментарий</p>');
		$(".modal-body").append('<p><textarea class="form-control text" rows="2" style="margin-bottom:15px;"></textarea></p>');
		$(".modal-body").append('<button type="submit" class="btn btn-primary" >Выдать</button>');
		$('.selectpicker').selectpicker();

		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{
			var price = $('input.form-control.name').val();
			var text = $('textarea.form-control.text').val();

			if(price > 0 )
			{
				$.ajax
				({
					url: "/static/cashback_from/",
					type: "POST",
					data: {"page_id": <?=$sql[0]->page_id;?>, "price": price, "mobile":  '<?=$sql[0]->mobile;?>', "text": text},
					cache: true,
					beforeSend: function()
					{
						$("#myModalBox").modal('show');
						$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
					},
					success: function(response)
					{
						$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title"><? if($CFG->USER->USER_ID == 153) {?> Кешбек выдан<? } else {?> Кешбек отправлен на модерацию. <? }?></h4></div>');
						$('.content').load(location.href + '/json');

						//setTimeout(function() {window.location.reload();}, 0,1);
					}

				});

			}
			else
			{
				alert("Укажите сумму");
			}
		});

		e.preventDefault();

	});

</script>

	<? }
	else {
	?>

     <div class="col-md-12 textS">
        <div class="obj">
        	<br clear="all">
            <p class="gray" style=" font-size:16px;font-family: 'segoeui_sb';"><strong><span style="color:#F00">Проверьте добавлен ли контакт в карточке, поле Телефон должно быть заполненно!</span></strong></a></p>
        </div>
    </div>

<br clear="all">
<style type="text/css">
	.cashback_from {font-family: 'segoeui_sb'; padding: 7px 15px;background: #F8403E;border-radius: 5px;color: #FFF;cursor: pointer;font-size: 14px;margin: 0 auto; text-transform: uppercase;border: 0;margin-top: 18px; display: inline-block; margin-left:15px;}
</style>

<br clear="all">


<?	$sql = array_reverse($sql);	?>

</div>

<?
}
?>
