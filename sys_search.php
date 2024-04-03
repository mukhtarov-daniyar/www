<?
	$NEWS = new News();

	$search_word = rawurldecode($CFG->_GET_PARAMS[0]);


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
		else
		{
			redirect('/person/?search='.preg_replace('/[+]/', '%2B', $search_word));
			//redirect('/person/?search='.preg_replace('/[\s\+]/', '%2B', $search_word));
		}
	}
	exit;


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



?>
<style>
.big_title {font-family: 'segoeui_sb'; font-size:16px; text-transform:uppercase; display:block; margin:10px 0;}
.respons_news { padding-left:30px; display:block; width:100%; text-align:center; padding-bottom:8px;}
.respons_news a{font-family: 'segoeui'; font-size:14px; display:block; text-align:left}
.respons_news span {color: #CA1B56;     font-family: 'segoeui_b'; text-decoration:underline;font-size: 13px;}
.respons_news .load_search {    background: #F8403E; border-radius:5px; color:#fff; cursor:pointer;  padding: 7px 15px !important; font-family: 'segoeui'; display: inline-block; text-align:center; margin-top:10px;margin: 0 auto;}
table.users td, table.users th {padding: 5px 3px;}
</style>

<div class="content">
	<h2>Результаты поиска: <? echo $search_word;?></h2>
	<div class="white" style="padding-left:30px;">


	<div class="big_title">Клиентские записи</div>
    <div class="respons_news" style="padding-left:0;">
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
        $search_word = trim($search_word);
		$search_where .= "AND (name_company LIKE '%{$search_word}%'

										OR history LIKE '%{$search_word}%'
										OR contact LIKE '%{$search_word}%'
										OR insta LIKE '%{$search_word}%'
										OR info LIKE '%{$search_word}%')";

        if (utf8_strlen($search_word) > 2)
        {
            $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news WHERE visible='1' {$search_where} AND page_id = 868 order by cdate DESC limit 10");
            for ($i=0; $i<sizeof($sql); $i++)
            {
                $pid[] .= $sql[$i]->id;
            }

            $com = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE text LIKE '%{$search_word}%' AND visible='1' AND parent_id = 868  order by cdate DESC limit 10");
            for ($i=0; $i<sizeof($com); $i++)
            {
                $pid[] .= $com   [$i]->page_id;
            }

            $res = array_unique($pid);
        }

        for ($i=0; $i<sizeof($res); $i++)
        {
            $data = SelectDataRowOArray("news", $res[$i]);
            if($data->id == !'' && $data->page_id == 868)
            {
                ?>
                <tr>
                    <td><a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><span>*<?=$data->id;?></span></a></td>
                    <td><a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><?=$data->name_company;?></a></td>
                    <td><a href="/profile/view/<?=$data->manager_id?>"><? $o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}city WHERE id='{$data->city_id}'");		echo $o->name;?></td>
                    <td><? echo dateSQL2TEXT($data->cdate, "DD.MM.YY").' '.dateSQL2TEXT($data->cdate, "hh:mm").'';?></td>
                    <td><? echo dateSQL2TEXT($data->edate, "DD.MM.YY").' '.dateSQL2TEXT($data->edate, "hh:mm").'';?></td>
                    <td><a href="/profile/view/<?=$data->manager_id?>"><? $o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$data->manager_id}'");		echo $o->name;?></a></td>
                    <td><?=SelectDataCount('comments', 'page_id', $data->id);?></td>
                </tr>

                 <?
            }
        }
        ?>
		</table>
    	<div class="rest_868"></div>
    	<? if (count($res) > 9 ) {?><div class="load_search" data-id="868" data-direction="<?=$search_word;?>">Загрузить еще</div><? }?>

    </div>





	<div class="big_title">Физ. лица</div>
    <div class="respons_news">
		<?
        $search_word = trim($search_word);

        if (utf8_strlen($search_word) > 2)
        {
			$s_w = "AND (name LIKE '%{$search_word}%' OR mobile LIKE '%{$search_word}%' OR email LIKE '%{$search_word}%' OR whatsapp LIKE '%{$search_word}%' OR name_other LIKE '%{$search_word}%' OR info LIKE '%{$search_word}%' )";
            $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}face WHERE visible='1' {$s_w} AND page_id = 1012 order by id limit 10");
            for ($i=0; $i<sizeof($sql); $i++)
            {
                $pis[] .= $sql[$i]->id;
            }

            $res = array_unique($pis);
        }

        for ($i=0; $i<sizeof($res); $i++)
        {
			$data = getSQLRowO("SELECT page_id,id,name FROM {$CFG->DB_Prefix}face WHERE id='{$res[$i]}'  AND visible='1'");
            if($data->id == !'' && $data->page_id == 1012)
            {
                ?> <a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><?=$data->name;?></a> <?
            }
        }
        ?>
    	<div class="res_1012"></div> <div class="rest_1012"></div>
    	<? if (count($res) > 9 ) {?><div class="load_search" data-id="1012" data-direction="<?=$search_word;?>">Загрузить еще</div><? }?>

    </div>




	<div class="big_title">Сделки</div>
    <div class="respons_news">
		<?
        $search_word = trim($search_word);

        if (utf8_strlen($search_word) > 2)
        {
            $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news WHERE visible='1' {$search_where} AND page_id = 1000 AND manager_id {$final} order by id limit 10");
            for ($i=0; $i<sizeof($sql); $i++)
            {
                $pis[] .= $sql[$i]->id;
            }

            $com = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE text LIKE '%{$search_word}%' AND visible='1' AND parent_id = 1000 AND user_id {$final} order by id limit 10");
            for ($i=0; $i<sizeof($com); $i++)
            {
                $pis[] .= $com   [$i]->page_id;
            }

            $res = array_unique($pis);

        }

        for ($i=0; $i<sizeof($res); $i++)
        {
            $data = SelectDataRowOArray("news", $res[$i]);
            if($data->id == !'' && $data->page_id == 1000)
            {
                ?> <a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><span>*<?=$data->id;?></span> <?=$data->name_company;?></a> <?
            }
        }
        ?>
    	<div class="res_1000"></div> <div class="rest_1000"></div>
    	<? if (count($res) > 9 ) {?><div class="load_search" data-id="1000" data-direction="<?=$search_word;?>">Загрузить еще</div><? }?>

    </div>






	<div class="big_title">Служебные записи</div>
    <div class="respons_news">
		<?
        $search_word = trim($search_word);

        if (utf8_strlen($search_word) > 2)
        {
            $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news WHERE visible='1' {$search_where} AND page_id = 976 AND manager_id {$final} order by id limit 10");
            for ($i=0; $i<sizeof($sql); $i++)
            {
                $pis[] .= $sql[$i]->id;
            }

            $com = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE text LIKE '%{$search_word}%' AND visible='1' AND parent_id = 976 AND user_id {$final} order by id limit 10");
            for ($i=0; $i<sizeof($com); $i++)
            {
                $pis[] .= $com   [$i]->page_id;
            }

            $res = array_unique($pis);

        }

        for ($i=0; $i<sizeof($res); $i++)
        {
            $data = SelectDataRowOArray("news", $res[$i]);
            if($data->id == !'' && $data->page_id == 976)
            {
                ?> <a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><span>*<?=$data->id;?></span> <?=$data->name_company;?></a> <?
            }
        }
        ?>
    	<div class="res_976"></div> <div class="rest_976"></div>
    	<? if (count($res) > 9 ) {?><div class="load_search" data-id="976" data-direction="<?=$search_word;?>">Загрузить еще</div><? }?>

    </div>



	<div class="big_title">Профили пользователей</div>
    <div class="respons_news">
		<?
        $search_word = trim($search_word);

        if (utf8_strlen($search_word) > 2)
        {
            $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}users WHERE visible='1'  AND (name LIKE '%{$search_word}%' OR email LIKE '%{$search_word}%' OR mobile LIKE '%{$search_word}%' OR info LIKE '%{$search_word}%') ");
            for ($i=0; $i<sizeof($sql); $i++)
            {
                $pip = $sql[$i]; ?> <a href="/profile/view/<?=$pip->id;?>"><?=$pip->name;?></a> <?
            }
        }

        ?>

	</div>
</div>
