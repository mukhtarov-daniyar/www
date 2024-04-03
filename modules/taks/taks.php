<?

	// Продляем срок жизни задачи
	if($CFG->_GET_PARAMS[0] =='updatestaks')
	{
		if($CFG->DB->query("UPDATE my_comments SET offtask = '{$_POST[input]}'  WHERE id='{$_POST[id]}' "))
		{
			echo 1;
		}
		exit;
	}




	if(is_numeric($CFG->_GET_PARAMS[0]) > 0)
	{

?>

<style>
.nav-tabs { font-family:'segoeui';  }
</style>

<script>
	$(function(){
		$('[data-toggle="tooltip"]').tooltip();
	});
	</script>
    <br clear="all" id="view-notes">


    <div class="row tab">
        <div class="col-md-6">
            <h1><a href="/profile/view/<?=$CFG->USER->USER_ID;?>/">Профайл: <? $resp = SelectDataRowOArray('users', $CFG->_GET_PARAMS[0], 0);?> <strong><?=$resp->name;?></strong></a></h1>
        </div>
        <div class="col-md-6">
            <h1 class="active"><a href="/taks/<?=$CFG->USER->USER_ID;?>/">Задачи: <? $resp = SelectDataRowOArray('users', $CFG->_GET_PARAMS[0], 0);?> <strong><?=$resp->name;?></strong></a></h1>
        </div>
    </div>

    <div class="white">


    <br clear="all">


        <ul class="nav nav-tabs" id="myTab">
          <li data-toggle="tooltip" data-placement="top" title="Поставленные мне задачи" class="active"><a href="#my-task" data-toggle="tab">Мои задачи <span class="badge"><? $response = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}comments WHERE visible=1 AND doer = {$CFG->_GET_PARAMS[0]} AND status_taks = 1 ORDER BY cdate DESC"); echo count($response);?></span></a></li>
					<li data-toggle="tooltip" data-placement="top" title="Выполненые задачи которые поставили мне"><a href="#archive" data-toggle="tab">Выполненые задачи</a></li>

					<li><a>|</a></li>

					<li data-toggle="tooltip" data-placement="top" title="Поставленные мною задачи"><a href="#my-user-taks" data-toggle="tab" ><span>Поставленные задачи</span></a></li>
          <li data-toggle="tooltip" data-placement="top" title="Выполненые мои поставленные задачи"><a href="#my-user-taks-ok" data-toggle="tab"><span>Выполненые поставленные задачи</span></a></li>
        </ul>

        <div class="tab-content">

	        <div class="tab-pane active" id="my-task">
                <article class="vacancies_body row">
                        <?

                           $response = getSQLArrayO("SELECT *  FROM {$CFG->DB_Prefix}comments WHERE visible=1 AND doer = {$CFG->_GET_PARAMS[0]} AND status_taks = 1 ORDER BY offtask DESC");
                            if(count($response) > 0)
                            {
                                ?>
                                 <table class="users tasks">
                                  <tbody>
                                    <tr>
                                        <th><strong>Дедлайн</strong></th>
                                        <th><strong>Задача</strong></th>
                                        <th><strong>Запись</strong></th>
                                        <th><strong>Автор</strong></th>
                                        <th><strong>Статус</strong></th>
                                    </tr>
                                           <?
                                            for ($i=0; $i<sizeof($response); $i++)
                                                {
                                                    $o = $response[$i];

													$current = sqlDateNow();

													if($current > $o->offtask)
													{
														$offtask = ' class="offtask"';
														$status = '<span class="offtask">Просрочено</span>';
													}
													else
													{
														$offtask = '';
														$status = '<span class="starttask">В работе</span>';
													}
                                            ?>
                                    <tr<?=$offtask?>>
                                        <td class="offtask"><?=dateSQL2TEXT($o->offtask, "DD.MM.YY")?></td>
                                        <td class="text"><a target="_blank" href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>"><?=utf8_substr(strip_tags($o->text), 0, 100);?></a></td>
                                        <td class="offtask"><a target="_blank" href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>"><? $res = SelectDataRowOArray('news', $o->page_id);?> <?=$res->name_company;?></a></td>
                                        <td class="autor"><? $resp = SelectDataRowOArray('users', $o->user_id, 0);?> <a href="/profile/view/<?=$resp->id;?>/#view-notes"><?=$resp->name;?></a></td>
                                       <td>

                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                   <?=$status;?>
                                                   <? if($o->doer == $CFG->USER->USER_ID) {?><span class="caret"></span><? }?>
                                                </button>


                                                <? if($o->doer == $CFG->USER->USER_ID) {?>
                                                <ul class="dropdown-menu">
                                                  <li><a href="#" data-id="<?=$o->id;?>" class="upstatustaks">Выполнено</a></li>
                                                </ul>
                                                <? }?>
                                            </div>
                                       </td>
                                    </tr>

                                   <? } ?>



                                    </tbody>
                                </table>
                                <?
                            }
                        ?>
                </article>
            </div>


            <div class="tab-pane" id="archive">
                        <article class="vacancies_body row">
                                <?
                                   $response = getSQLArrayO("SELECT *  FROM {$CFG->DB_Prefix}comments WHERE visible=1 AND doer = {$CFG->_GET_PARAMS[0]} AND status_taks = 3 ORDER BY offtask DESC");
                                    if(count($response) > 0)
                                    {
                                        ?>
                                         <table class="users tasks">
                                          <tbody>
                                            <tr>
                                                <th><strong>Дедлайн</strong></th>
                                                <th><strong>Задача</strong></th>
                                                <th><strong>Запись</strong></th>
                                                <th><strong>Автор</strong></th>
                                            </tr>
                                                   <?
                                                    for ($i=0; $i<sizeof($response); $i++)
                                                        {
                                                            $o = $response[$i];
                                                    ?>
                                            <tr>
                                                <td class="offtask"><?=dateSQL2TEXT($o->offtask, "DD.MM.YY")?></td>
                                                <td class="text"><a  target="_blank" href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>"><?=utf8_substr(strip_tags($o->text), 0, 100);?></a></td>
                                                <td class="offtask"><a  target="_blank" href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>"><? $res = SelectDataRowOArray('news', $o->page_id);?> <?=$res->name_company;?></a></td>
                                                <td class="autor"><? $resp = SelectDataRowOArray('users', $o->user_id, 0);?> <a href="/profile/view/<?=$resp->id;?>/#view-notes"><?=$resp->name;?></a></td>
                                            </tr>

                                           <? } ?>

                                            </tbody>
                                        </table>
                                        <?
                                    }
                                ?>
                        </article>
                    </div>

            <div class="tab-pane" id="my-user-taks">
							<article class="vacancies_body row">
								<table class="users tasks">
									<tbody>
										<tr>
											<th><strong>Дедлайн</strong></th>
											<th><strong>Задача</strong></th>
											<th><strong>Запись</strong></th>
											<th><strong>Исполнитель</strong></th>
											<th><strong>Статус</strong></th>
										</tr>
								<?
									$corrent = sqlDateNow();
									$response = getSQLArrayO("SELECT *  FROM {$CFG->DB_Prefix}comments WHERE visible=1 AND user_id = {$CFG->_GET_PARAMS[0]}  AND status_taks = 1 AND task = 1 AND offtask > '{$corrent}' ORDER BY offtask  ASC");
                  if(count($response) > 0)
									{  for ($i=0; $i<sizeof($response); $i++)
												   { $o = $response[$i];
														 if(sqlDateNow() > $o->offtask) { $offtask = ' class="offtask"'; } else {$offtask = '';   } ?>
												<tr<?=$offtask?>>
													<td class="offtask"><?=dateSQL2TEXT($o->offtask, "DD.MM.YY")?></td>
													<td class="text"><a  target="_blank" href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>"><?=utf8_substr(strip_tags($o->text), 0, 100);?></a></td>
													<td class="offtask"><a  target="_blank" href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>"><? $res = SelectDataRowOArray('news', $o->page_id);?> <?=$res->name_company;?></a></td>
													<td class="autor"><? $resp = SelectDataRowOArray('users', $o->doer, 0);?> <a href="/profile/view/<?=$resp->id;?>/#view-notes"><?=$resp->name;?></a></td>
													<td><?
																if($o->status_taks == 1){ echo '<span class="starttask">В работе</span>';}
																if($o->status_taks == 2){  ?>  <div class="btn-group">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                                           <span class="moder">На модерации</span>
                                                                           <? if($o->user_id == $CFG->USER->USER_ID) {?><span class="caret"></span><? }?>
                                                                        </button>

                                                                        <? if($o->user_id == $CFG->USER->USER_ID) {?>
                                                                        <ul class="dropdown-menu">
                                                                          <li><a href="#" data-id="<?=$o->id;?>" class="upmodernstatustaks">Выполнено</a></li>
                                                                        </ul>
                                                                        <? }?>
                                                                    </div>

                              											<?  }
																	if($o->status_taks == 3){echo '<span class="offtask">Выполнено</span>';  } ?></td>
                        </tr>
											<? } //for
                  }//if count  ?>


									<?

									 $corrent = sqlDateNow();
									 $response = getSQLArrayO("SELECT *  FROM {$CFG->DB_Prefix}comments WHERE visible=1 AND user_id = {$CFG->_GET_PARAMS[0]}  AND status_taks = 1 AND task = 1 AND offtask < '{$corrent}' ORDER BY offtask  ASC");
                  if(count($response) > 0)
									{ for ($i=0; $i<sizeof($response); $i++)
										{ $o = $response[$i];
											if(sqlDateNow() > $o->offtask) { $offtask = ' class="offtask"'; } else {$offtask = '';   }  ?>
                      <tr<?=$offtask?>>
											 <td class="offtask"><?=dateSQL2TEXT($o->offtask, "DD.MM.YY")?></td>
											 <td class="text"><a  target="_blank" href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>"><?=utf8_substr(strip_tags($o->text), 0, 100);?></a></td>
											 <td class="offtask"><a  target="_blank" href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>"><? $res = SelectDataRowOArray('news', $o->page_id);?> <?=$res->name_company;?></a></td>
											 <td class="autor"><? $resp = SelectDataRowOArray('users', $o->doer, 0);?> <a href="/profile/view/<?=$resp->id;?>/#view-notes"><?=$resp->name;?></a></td>
											 <td><? if($o->status_taks == 1){?>
                                                            <div class="btn-group">
                                                              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                                 <span class="moder">Просрочено</span>
                                                                 <? if($o->user_id == $CFG->USER->USER_ID) {?><span class="caret"></span><? }?>
                                                              </button>

                                                              <? if($o->user_id == $CFG->USER->USER_ID) {?>
                                                              <ul class="dropdown-menu">
                                                                <li><a href="#" data-id="<?=$o->id;?>" class="updatestaks">Продлить</a></li>
                                                              </ul>
                                                              <? }?>
                                                           </div>  <?  }

                              if($o->status_taks == 3){  echo '<span class="offtask">Выполнено</span>';} ?></td>
                    </tr>

                <? } ?>
              <?}  ?>
						  </tbody>
					   </table>
					  </article>
					</div>





             <div class="tab-pane" id="my-user-taks-ok">
                        <article class="vacancies_body row">
                                <?
                                   $response = getSQLArrayO("SELECT *  FROM my_comments WHERE visible=1 AND user_id = {$CFG->_GET_PARAMS[0]}  AND status_taks = 3  AND task = 1 ORDER BY offtask  DESC");
                                    if(count($response) > 0)
                                    {
                                        ?>
                                         <table class="users tasks">
                                          <tbody>
                                            <tr>
                                                <th><strong>Дедлайн</strong></th>
                                                <th><strong>Задача</strong></th>
                                                <th><strong>Запись</strong></th>
                                                <th><strong>Исполнитель</strong></th>
                                            </tr>
                                                   <?
                                                    for ($i=0; $i<sizeof($response); $i++)
                                                        {
                                                            $o = $response[$i];?>
                                            <tr>
                                                <td class="offtask"><?=dateSQL2TEXT($o->offtask, "DD.MM.YY")?></td>
                                                <td class="text"><a  target="_blank" href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>"><?=utf8_substr(strip_tags($o->text), 0, 100);?></a></td>
                                                <td class="offtask"><a  target="_blank" href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>"><? $res = SelectDataRowOArray('news', $o->page_id);?> <?=$res->name_company;?></a></td>
                                                <td class="autor"><? $resp = SelectDataRowOArray('users', $o->doer, 0);?> <a href="/profile/view/<?=$resp->id;?>/#view-notes"><?=$resp->name;?></a></td>
                                            </tr>
                                           <? } ?>

                                            </tbody>
                                        </table>
                                        <?
                                    }
                                ?>
                        </article>
                    </div>



        </div>

	</div>
<? }
	else
	{
		redirect("/");
	}


 ?>
