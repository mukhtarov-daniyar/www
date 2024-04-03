<h2><img alt="" src="/tpl/img/new/icon/4_red.png">Структура компании<div style="float:right"><?  if($CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 912 ||$CFG->USER->USER_ID == 133 || $CFG->USER->USER_ACCOUNTING_CHIEF == $CFG->USER->USER_ID || $CFG->USER->USER_BOSS == 1) { ?>
	<a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/add/" class="mailogo" style="padding:0 !important; margin-top:15px; margin-right:10px; line-height:30px; font-family: 'segoeui_sb'; ">Добавить менеджера</a>
<? } ?></div></h2>

<style type="text/css">
.structure .row, .structure .roditel, .structure .row .user { display:block; clear:both; padding:0; margin:0}

.structure .user { margin-top:10px !important; margin-bottom:10px !important; margin-left:50px !important;}
.structure .user .col-md-3 { width:70px; padding:0; margin:0}
.structure .user .col-md-3 img{ width:100%; border-radius:50%}
.structure .user .col-md-9 { width: auto; position:relative; top:10px;}
.structure .user .col-md-9 h3{ padding:0; margin:0; display: inline-block; font-size:20px; margin-bottom:3px;}
.structure .user .col-md-9 a{ color:#333; text-decoration:none; font-size:14px; text-transform:uppercase; font-family: 'segoeui_sb';}
.structure .user .col-md-9 span{ color: #999; text-decoration:none; font-size:14px; text-transform:uppercase; font-family: 'segoeui_sb'; display:block}

.structure .user .col-md-9 .opt_power, .structure .user .col-md-9  .opt_off { color:#03C; text-transform:none; text-decoration:underline; font-size:12px; display:inline-block; margin-left:5px; vertical-align: super}

.structure .roditel { padding-left:100px !important; display:block}
.structure .roditel_1 { padding-left:80px !important; display:block}
.structure .roditel_2 { padding-left:80px !important; display:block}

@media screen and (min-width:100px) and (max-width:768px)
{
	.structure .user { margin-top:10px !important; margin-bottom:10px !important; margin-left:10px !important;}
	.structure .user .col-md-3 { width:40px; padding:0; margin:0; float:left}
	.structure .user .col-md-3 img{ width:100%; border-radius:50%}

	.structure .user .col-md-9 { width: auto; position:relative; top:10px; float:left}
	.structure .user .col-md-9 h3{ padding:0; margin:0; display:block; font-size:14px; margin-bottom:3px;}
	.structure .user .col-md-9 a{ color:#333; text-decoration:none; font-size:12px; text-transform:uppercase; font-family: 'segoeui_sb';}
	.structure .user .col-md-9 span{ color: #999; text-decoration:none; font-size:12px; font-family: 'segoeui_sb';}

	.structure .roditel { padding-left:10px !important; display:block}
	.structure .roditel_1 { padding-left:20px !important; display:block}
	.structure .roditel_2 { padding-left:20px !important; display:block}
}
</style>











<div class="white structure">

<?	$res = getSQLRowO("SELECT id, name, avatar, position FROM {$CFG->DB_Prefix}users WHERE user_id='{$CFG->USER->USER_DIRECTOR_ID}' and boss= 1 "); ?>

	<div class="row">
    	<div class="user">
            <div class="col-md-3">
                <a href="/profile/view/<? echo $res->id;?>"><img src="<? echo $res->avatar;?>" title="<? echo $res->name;?>" alt="<? echo $res->name;?>" /></a>
            </div>
            <div class="col-md-9">
                <a href="/profile/view/<? echo $res->id;?>"><h3><? echo $res->name;?></h3></a>
                <span><? echo $res->position;?></span>
            </div>
        </div>

        	<div class="roditel">
			<?
            $response  = getSQLArrayO("SELECT id, name, avatar, position FROM {$CFG->DB_Prefix}users WHERE curator='{$res->id}'  and visible = 1 ORDER by name");
             for ($i=0; $i<sizeof($response); $i++)
             {
                $o = $response[$i];
                ?>

                <div class="row">
                    <div class="user">
                        <div class="col-md-3">
                            <a href="/profile/view/<? echo $o->id;?>"><img src="<? echo $o->avatar;?>" title="<? echo $o->name;?>" alt="<? echo $o->name;?>" /></a
                        ></div>
                        <div class="col-md-9">
                            <a href="/profile/view/<? echo $o->id;?>"><h3><? echo $o->name;?></h3></a>
                             <?  if($CFG->USER->USER_ID == 912 || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ACCOUNTING_CHIEF == $CFG->USER->USER_ID || $CFG->USER->USER_BOSS == 1) { ?>
                             <a class="opt_power" data-id="<? echo $o->id;?>" href="#">Отключить</a>
                             <a class="opt_off" data-id="<? echo $o->id;?>" href="#">Уволить</a>
                             <? } ?>
                            <span><? echo $o->position;?></span>
                        </div>
                    </div>
                        <div class="roditel_1">
                        <?
                        $sql  = getSQLArrayO("SELECT id, name, avatar, position FROM {$CFG->DB_Prefix}users WHERE curator='{$o->id}'  and visible = 1 ORDER by name");
                         for ($y=0; $y<sizeof($sql); $y++)
                         {
                            $z = $sql[$y];
                            ?>

                            <div class="row">
                                <div class="user">
                                    <div class="col-md-3">
                                        <a href="/profile/view/<? echo $z->id;?>"><img src="<? echo $z->avatar;?>" title="<? echo $z->name;?>" alt="<? echo $z->name;?>" /></a>
                                    </div>
                                    <div class="col-md-9">
                                        <a href="/profile/view/<? echo $z->id;?>"><h3><? echo $z->name;?></h3></a>
										  <?  if($CFG->USER->USER_ID == 912 ||  $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ACCOUNTING_CHIEF == $CFG->USER->USER_ID || $CFG->USER->USER_BOSS == 1) { ?>
                                         <a class="opt_power" data-id="<? echo $z->id;?>" href="#">Отключить</a>
                                         <a class="opt_off" data-id="<? echo $z->id;?>" href="#">Уволить</a>
                                         <? } ?>
                                       <span><? echo $z->position;?></span>
                                    </div>
                                </div>
                                     <div class="roditel_2">
                                    <?
                                    $res  = getSQLArrayO("SELECT id, name, avatar, position FROM {$CFG->DB_Prefix}users WHERE curator='{$z->id}'  and visible = 1 ORDER by name");
                                     for ($t=0; $t<sizeof($res); $t++)
                                     {
                                        $r = $res[$t];
                                        ?>

                                        <div class="row">
                                            <div class="user">
                                                <div class="col-md-3">
                                                    <a href="/profile/view/<? echo $r->id;?>"><img src="<? echo $r->avatar;?>" title="<? echo $r->name;?>" alt="<? echo $r->name;?>" /></a>
                                                </div>
                                                <div class="col-md-9">
                                                    <a href="/profile/view/<? echo $r->id;?>"><h3><? echo $r->name;?></h3></a>
													 <?  if($CFG->USER->USER_ID == 912 ||  $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ACCOUNTING_CHIEF == $CFG->USER->USER_ID || $CFG->USER->USER_BOSS == 1) { ?>
                                                     <a class="opt_power" data-id="<? echo $r->id;?>" href="#">Отключить</a>
                                                     <a class="opt_off" data-id="<? echo $r->id;?>" href="#">Уволить</a>
                                                     <? } ?>
                                                    <span><? echo $r->position;?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?
                                     }
                                    ?>
                                    </div>



                            </div>
                            <?
                         }
                        ?>
                        </div>

                </div>
                <?

             }
            ?>
            </div>


    </div>

<br clear="all">
<br clear="all">
<br clear="all">
</div>



<script type="text/javascript">

$('a.opt_power').on('click', function(e)
{
	var id = $(this).attr('data-id');

	$('#myModalBox').modal({backdrop: 'static', keyboard: false});

	$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
	$(".modal-body").append('<h4 class="modal-title"><center>Вы действительно хотите отключить сотрудника от системы?</center></h4>');

	$(".modal-body").append('<center><button style="margin-top:10px;    padding-left: 15px !important; padding-right: 15px !important;" type="submit" class="btn btn-primary submit">Да</button> &nbsp; &nbsp; <button style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Нет</button></center>');

	$(".modal-body").append('<br><p style="font-size:14px;">Если вы нажмете на кнопку <strong>«ДА»</strong>, то данный сотрудник больше не сможет войти в систему до того момента пока Вы ему вновь не предоставить доступ. При этом все его записи заметки и прочая информация, сохраняется.</p>');

	$('.btn.btn-primary.submit').on('click', function(e)
	{
		$.ajax
		({
			url: "/static/user_op/",
			type: "POST",
			data: {"user_id": id},
			cache: true,
			beforeSend: function()
			{
				$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(response)
			{
				setTimeout(function() {window.location.reload();}, 0,5);
			}
		});
	});
	e.preventDefault();
});


$('a.opt_off').on('click', function(e)
{
	var id = $(this).attr('data-id');

	$('#myModalBox').modal({backdrop: 'static', keyboard: false});

	$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
	$(".modal-body").append('<h4 class="modal-title"><center>Вы действительно хотите уволить сотрудника из компании?</center></h4>');

	$(".modal-body").append('<center><button style="margin-top:10px;    padding-left: 15px !important; padding-right: 15px !important;" type="submit" class="btn btn-primary submit">Да</button> &nbsp; &nbsp; <button style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Нет</button></center>');

	$(".modal-body").append('<br><p style="font-size:14px;">Если вы нажмете на кнопку <strong>«ДА»</strong>, то данный сотрудник больше не сможет войти в систему никогда и все его записи переместятся на директора компании.</p>');

	$('.btn.btn-primary.submit').on('click', function(e)
	{
		$.ajax
		({
			url: "/static/user_dismissed/",
			type: "POST",
			data: {"user_id": id},
			cache: true,
			beforeSend: function()
			{
				$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(response)
			{
				setTimeout(function() {window.location.reload();}, 0,5);
			}
		});
	});
	e.preventDefault();
});

</script>
