<?
	$NEWS = new News();

print_r(1); exit;

	$search_word = rawurldecode($CFG->_GET_PARAMS[0]);
	if (substr($search_word, 0, 5) == "?q=")
	$search_word = preg_replace("+", " ", substr($search_word, 5));


	$oPageInfo->name = $CFG->Locale["search"];

	if(!$CFG->_GET_PARAMS[0] == '')
	{
		$namber = explode("*", $search_word);


		if(is_numeric($namber[1]) > 0 )
		{
			$sql = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id={$namber[1]}");

			$z = $NEWS->getObject($sql->page_id, $namber[1]);

			if(count($z) > 0 )
			{
				redirect(getFullXCodeByPageId($z->page_id).$z->id);
			}
		}
	}



			$sql = "SELECT user_id FROM {$CFG->DB_Prefix}users WHERE id={$CFG->USER->USER_ID}";
			$res = getSQLRowO($sql);

			$big_user = AndDataArray('users', 'user_id', $res->user_id, 0);

			if(count($big_user) > 0)
			{
				for($y=0;$y<sizeof($big_user);$y++)
				{

					$in .= $big_user[$y]->id.",";
				}

				$andid = trim($in, ",");
				$final = " IN ({$andid})";
			}

 $search_word = trim($search_word);



			if($CFG->_GET_PARAMS[0] == 'ajax')
			{
				$res = big_search_ajax($_POST["user_id"], $_POST["search"], $_POST["num"]);

				print_r($res);



				exit;
			}





?>
<style>
.big_title {font-family: 'segoeui_sb'; font-size:16px; text-transform:uppercase; display:block; margin:10px 0;}
.respons_news { padding-left:30px; display:block; width:100%; text-align:center; padding-bottom:8px;}
.respons_news a{font-family: 'segoeui'; font-size:14px; display:block; text-align:left}
.respons_news span {color: #CA1B56;     font-family: 'segoeui_b'; text-decoration:underline;font-size: 13px;}
.respons_news .load_search_big {    background: #F8403E; border-radius:5px; color:#fff; cursor:pointer;  padding: 7px 15px !important; font-family: 'segoeui'; display: inline-block; text-align:center; margin-top:10px;margin: 0 auto;}
table.users td, table.users th {padding: 5px 3px;}


.tabbable #myTab { text-align:center;font-family:'segoeui_sb'; font-size:14px; text-transform:uppercase; padding-left:10px;}

</style>

<div class="content">
	<h2>Расширенный поиск: <? echo $search_word;?></h2>
	<div class="white" style="padding-left:30px;">

    <div class="tabbable">
        <ul class="nav nav-tabs" id="myTab">
          <li class="active"><a href="#record" data-toggle="tab">Клиентские (<? $client =  big_search_count(868, $search_word); echo $client;?>)</a></li>
          <li><a href="#deal" data-toggle="tab">Сделки (<? $deals = big_search_count(1000, $search_word); echo $deals;?>)</a></li>
          <li><a href="#office" data-toggle="tab">Служебные (<? $offices = big_search_count(976, $search_word); echo $offices;?>)</a></li>
          <li><a href="#users" data-toggle="tab">Профили (<? echo count(getSQLArrayO("SELECT id, name FROM my_users WHERE MATCH (name,email,mobile,info) AGAINST ('{$search_word}' IN BOOLEAN MODE) ")); ?>)</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="record">
                <div class="respons_news" style="padding-left:0;">
                	<br clear="all">
                    <? 	$res =  big_search(868, $search_word);	if(count($res) != 0) {?>
                    <table class="users res_868">
                    <tr>
                        <th><strong>№ записи</strong></th>
                        <th><strong>Клиент</strong></th>
                        <th><strong>Город</strong></th>
                        <th><strong>Дата созд.</strong></th>
                        <th><strong>Дата ред.</strong></th>
                        <th><strong>Менеджер</strong></th>
                        <th><strong>Заметка</strong></th>
                    </tr>
                    <?
                    if (utf8_strlen($search_word) > 2)
                    {
                        $res =  big_search(868, $search_word);
                        for ($i=0; $i<sizeof($res); $i++)
                        {
                            $data = SelectDataRowOArray("news", $res[$i]);
                            if($data->id == !'' && $data->page_id == 868)
                            {
                                ?>
                                <tr>
                                    <td><a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><span>*<?=$data->id;?></span></a></td>
                                    <td><a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><?=$data->name_company;?></a></td>
                                    <td><a href="/profile/view/<?=$data->manager_id?>"><? $o = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}city WHERE id='{$data->city_id}'");		echo $o->name;?></td>
                                    <td><? echo dateSQL2TEXT($data->cdate, "DD.MM.YY").' '.dateSQL2TEXT($data->cdate, "hh:mm").'';?></td>
                                    <td><? echo dateSQL2TEXT($data->edate, "DD.MM.YY").' '.dateSQL2TEXT($data->edate, "hh:mm").'';?></td>
                                    <td><a href="/profile/view/<?=$data->manager_id?>"><? $o = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}users WHERE id='{$data->manager_id}'");		echo $o->name;?></a></td>
                                    <td><?=SelectDataCount('comments', 'page_id', $data->id);?></td>
                                </tr>
                                 <?
                            }
                        }
                    }
                    ?>
                    </table>
                    <? } else {echo '<strong>Ничего не найдено в клиенских записях! :(</strong>';}?>
                    <div class="rest_868"></div>
                    <? if ($client > 9 ) {?><div class="load_search_big" data-id="868" data-direction="<?=$search_word;?>">Загрузить еще</div><? }?>
                </div>
            </div>

            <div class="tab-pane" id="deal">
                <div class="respons_news" style="padding-left:0;">
                	<br clear="all">
                    <? 	$res =  big_search(1000, $search_word);	if(count($res) != 0) {?>
                    <table class="users res_1000">
                    <tr>
                        <th><strong>№ записи</strong></th>
                        <th><strong>Клиент</strong></th>
                        <th><strong>Город</strong></th>
                        <th><strong>Дата созд.</strong></th>
                        <th><strong>Дата ред.</strong></th>
                        <th><strong>Менеджер</strong></th>
                        <th><strong>Заметка</strong></th>
                    </tr>
                    <?
                    if (utf8_strlen($search_word) > 2)
                    {
                        for ($i=0; $i<sizeof($res); $i++)
                        {
                            $data = SelectDataRowOArray("news", $res[$i]);
                            if($data->id == !'' && $data->page_id == 1000)
                            {
                                ?>
                                <tr>
                                    <td><a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><span>*<?=$data->id;?></span></a></td>
                                    <td><a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><?=$data->name_company;?></a></td>
                                    <td><a href="/profile/view/<?=$data->manager_id?>"><? $o = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}city WHERE id='{$data->city_id}'");		echo $o->name;?></td>
                                    <td><? echo dateSQL2TEXT($data->cdate, "DD.MM.YY").' '.dateSQL2TEXT($data->cdate, "hh:mm").'';?></td>
                                    <td><? echo dateSQL2TEXT($data->edate, "DD.MM.YY").' '.dateSQL2TEXT($data->edate, "hh:mm").'';?></td>
                                    <td><a href="/profile/view/<?=$data->manager_id?>"><? $o = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}users WHERE id='{$data->manager_id}'");		echo $o->name;?></a></td>
                                    <td><?=SelectDataCount('comments', 'page_id', $data->id);?></td>
                                </tr>
                                 <?
                            }
                        }
                    }
                    ?>
                    </table>
                    <? } else {echo '<strong>Ничего не найдено в сделках! :(</strong>';}?>
                    <div class="rest_1000"></div>
                    <? if ($deals > 9 ) {?><div class="load_search_big" data-id="1000" data-direction="<?=$search_word;?>">Загрузить еще</div><? }?>
                </div>
            </div>

            <div class="tab-pane" id="office">
                <div class="respons_news" style="padding-left:0;">
                	<br clear="all">
                    <? 	$res =  big_search(976, $search_word);	if(count($res) != 0) {?>
                    <table class="users res_976">
                    <tr>
                        <th><strong>№ записи</strong></th>
                        <th><strong>Клиент</strong></th>
                        <th><strong>Город</strong></th>
                        <th><strong>Дата созд.</strong></th>
                        <th><strong>Дата ред.</strong></th>
                        <th><strong>Менеджер</strong></th>
                        <th><strong>Заметка</strong></th>
                    </tr>
                    <?
                    if (utf8_strlen($search_word) > 2)
                    {
                        $res =  big_search(976, $search_word);
                        for ($i=0; $i<sizeof($res); $i++)
                        {
                            $data = SelectDataRowOArray("news", $res[$i]);
                            if($data->id == !'' && $data->page_id == 976)
                            {
                                ?>
                                <tr>
                                    <td><a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><span>*<?=$data->id;?></span></a></td>
                                    <td><a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><?=$data->name_company;?></a></td>
                                    <td><a href="/profile/view/<?=$data->manager_id?>"><? $o = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}city WHERE id='{$data->city_id}'");		echo $o->name;?></td>
                                    <td><? echo dateSQL2TEXT($data->cdate, "DD.MM.YY").' '.dateSQL2TEXT($data->cdate, "hh:mm").'';?></td>
                                    <td><? echo dateSQL2TEXT($data->edate, "DD.MM.YY").' '.dateSQL2TEXT($data->edate, "hh:mm").'';?></td>
                                    <td><a href="/profile/view/<?=$data->manager_id?>"><? $o = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}users WHERE id='{$data->manager_id}'");		echo $o->name;?></a></td>
                                    <td><?=SelectDataCount('comments', 'page_id', $data->id);?></td>
                                </tr>
                                 <?
                            }
                        }
                    }
                    ?>
                    </table>
                    <? } else {echo '<strong>Ничего не найдено в служебных записях! :(</strong>';}?>
                    <div class="rest_976"></div>
                    <? if ($offices > 9 ) {?><div class="load_search_big" data-id="976" data-direction="<?=$search_word;?>">Загрузить еще</div><? }?>
                </div>
            </div>



            <div class="tab-pane" id="users">
            	<br clear="all">
                <div class="respons_news">
                    <?
                    $search_word = trim($search_word);

                    if (utf8_strlen($search_word) > 2)
                    {

						$sql = getSQLArrayO("SELECT id, name FROM my_users WHERE MATCH (name,email,mobile,info) AGAINST ('{$search_word}' IN BOOLEAN MODE)");

                        for ($i=0; $i<sizeof($sql); $i++)
                        {
                            $pip = $sql[$i]; ?> <a href="/profile/view/<?=$pip->id;?>"><?=$pip->name;?></a> <?
                        }
                    }

                    ?>
                </div>
            </div>

        </div>

    </div>

 </div>


<script>
/* Загрузить список поиска */
$(document).ready(function()
{
	var num = 5;

	$('.load_search_big').live('click', function(e)
	{
		var id = $(this).attr('data-id');
		var serch = $(this).attr('data-direction');

		$.ajax
		({
			url: "/search_big/ajax/",
			type: "POST",
			data: {"user_id": id, "search": serch, "num": num },
			cache: true,
			beforeSend: function()
			{
				$(document).ready(function(){
					$("#myModalBox").modal('show');
				});

				$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(response)
			{

				if(response == 0)
				{
					$(document).ready(function(){
						$("#myModalBox").modal('hide');
					});

					$('.alert').html("Ничего не найдено");
					$('.alert').animate({'opacity':'show'}, 1000);
					$('.alert').animate({'opacity':'hide'}, 4000);
				}
				else
				{
					$(document).ready(function(){
						$("#myModalBox").modal('hide');
					});

					num = num  + 5;

					$('.res_' + id).append(response);

					$("html, body").stop().animate({scrollTop: $('.rest_' + id).offset().top - 0 + 'px'}, 500);
				}
			}
		});
	});
});
/*  Загрузить список поиска. End */
</script>
