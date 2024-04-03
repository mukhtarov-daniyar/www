<?



	$o = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE status IN(5, 4) ");


    for($z=0;$z<sizeof($o);$z++)
    {
        ?>
            <a href=""></a>
            Выдайте кешбек клиенту <a href="<?=getFullXCodeByPageIdUrl($o[$z]->page_id).$o[$z]->page_id?>"><?=$o[$z]->mobile;?></a>. Сумма к получению <?=$o[$z]->price;?> <?=$CFG->USER->USER_CURRENCY;?> - <a href="#" class="cashback_list_view_go"><strong>ВЫДАТЬ</strong></a><br>

            	<script type="text/javascript">

       $('.cashback_list_view_go').on('click', function(e)
		{
			$(".modal-body").html('');

            $("#myModalBox").modal('show');

            $(".modal-body").append('<p>Комментарий</p>');
            $(".modal-body").append('<p><textarea class="form-control text" rows="2" style="margin-bottom:15px;"></textarea></p>');
            $(".modal-body").append('<p><button type="submit" data-id="1" class="btn btn-primary submit">Выдать</button></p>');

			$(document).on('click','.btn.submit',function(e)
            {
                var data_id = $(this).attr('data-id');
                var textarea = $('.form-control.text').val();

                $.ajax
                ({
                    url: "/static/cashback_go_three/",
                    type: "POST",
                    data: {"id": <?=$o[$z]->id;?>, "text": textarea, "mobile": '<?=$o[$z]->mobile?>', "price": '<?=$o[$z]->price;?>', "type": 1},
                    cache: true,
                        beforeSend: function()
                        {
                            $(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
                        },
                        success: function(response)
                        {
							$(".modal-body").html('<h4 class="modal-title"><center>КЕШБЕК ВЫДАН!</center></h4></div>');

							setTimeout(function()
							{
								window.location.reload();
							}, 1000);





                        }
                });
            });
         });
    </script>

        <?
    }





?>
