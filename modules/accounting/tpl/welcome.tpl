<?
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');
?>



<h2><img alt="" src="/tpl/img/new/icon/6_red.png"> <? echo $CFG->oPageInfo->name; ?></h2>


<div class="white">

<style>
	#file_upload { float:left; margin-left:130px; top:-3px; position:relative;cursor:pointer; }
	#file_upload_2 { width: 20px;height: 22px;font-size: 24px;background: url(/tpl/img/file.png) no-repeat center #D4D4D4;margin-top: 6px !important; padding: 17px !important; display: inline-block; border-radius: 3px; transition: all 0.2s;float: left;margin-right: 5px !important;cursor:pointer; margin-left:130px; top:-3px; position:relative}

	#file_upload { width: 20px;height: 22px;font-size: 24px;background: url(/tpl/img/file.png) no-repeat center #D4D4D4;margin-top: 6px !important; padding: 17px !important; display: inline-block; border-radius: 3px; transition: all 0.2s;float: left;margin-right: 5px !important;cursor:pointer; margin-left:130px; top:-3px; position:relative}


	#accounting h3 { padding:0; margin:0; display:block; text-align: left;  padding-bottom:10px; margin-bottom:20px; border-bottom:1px solid #BD2149 }
	#accounting .col-md-6 { border-right: 1px solid #CCCCCC;margin-top:20px; }
	#accounting .col-md-6:nth-child(2) { border:0}
	#accounting .col-md-6 label{ display:block; width:100%; font-family:'Helvetica_r'; font-weight:100 !important; margin-bottom:20px; font-size:16px}
	#accounting .col-md-6 .selectpicker{padding:7px 10px; font-size:16px; display: inline-block; }
	.bootstrap-select { width:300px !important; margin-right:5px;}
	#accounting .col-md-6 input[type="text"]{ border-radius:3px; border:0; border:solid 1px #ccc; padding:7px 10px; font-size:18px; margin-right:10px; width:300px;}
	#accounting .col-md-6 textarea{ border-radius:3px; border:0; border:solid 1px #ccc; padding:7px 10px; font-size:18px; margin-right:10px; width:300px; display:block; height:80px;}
	#accounting .col-md-6 input[type="submit"]{padding: 9px 30px;font-family:'Helvetica_b'; border:0px;color: #FFF;background-color: #F84241;cursor: pointer; text-transform:uppercase;font-size:14px;margin-left:171px; border-radius:3px; display:block; }
	#accounting h4 { padding:0; margin:0; display:block; font-family:'Helvetica_medium';text-align: right;  margin-top:50px; margin-bottom:20px;}

	#accounting  .row .obj{ display: block; width: 70%; margin: 0 auto; margin-bottom: 20px; margin-top: 20px;}
	#accounting .row .obj img{ display: block; width: 70%; margin: 0 auto;}
	#accounting .row .obj span{ display: block; font-size:18px;    font-family: 'segoeui_sb'; color: #000; text-align: center;}
</style>

	<div class="row" id="accounting">
	     <div class="col-md-12">
	        <h3>Выберите кассу:</h3>
					<?

						// Доступ для пользователя
						$res = getSQLArrayO("SELECT * FROM my_money_accounting_data_access WHERE user_id ='{$CFG->USER->USER_ID}' AND visible = 1 ");

						if( count($res ) > 1)
						{
							echo '<div class="row">';
							foreach($res as $key => $value)
							{
								$name = getSQLRowO(" SELECT name FROM my_money_accounting_data WHERE id = {$value->data_id} ");

								$url = '/accounting/list_view/'.$value->data_id.'/?&monthstart='.date("Y-m-01").'&monthend='.date("Y-m-d");

								echo '<div class="col-md-6">';
									echo '<div class="obj">';
										echo '<a href="'.$url.'">';
										echo '<img src="/tpl/img/kassa.png">';
										echo '<span>'.$name->name.'</span>';
										echo '<span>'.$ACCOUNT->sum($value->data_id).' '.$CFG->USER->USER_CURRENCY.' </span>';
										echo '</a>';
									echo '</div>';
								echo '</div>';
							}
							echo '</div>';

						}
						elseif( count($res ) == 0)
						{
							$CFG->STATUS->ERROR = true;
							$CFG->STATUS->MESSAGE = 'Вам закрыт доступ для просмотра касс!';
							redirect($_SERVER["HTTP_REFERER"]);
						}
						else
						{
								$url = '/accounting/list_view/'.$res[0]->data_id.'/?&monthstart='.date("Y-m-01").'&monthend='.date("Y-m-d");
								redirect($url);
						}
					?>
	     </div>
	</div>

</div>
