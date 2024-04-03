<?

    $o = $CFG->FORM->getFullForm();
    $e = $CFG->FORM->getFailInputs();

	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');

	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/js/elfinder.min.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/js/extras/editors.default.min.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/js/i18n/elfinder.ru.js"></script>');


  $CFG->SITE->addToHeaderHTML('<script src="/tpl/js/record/recorder.js?att='.time().'"></script>');
  $CFG->SITE->addToHeaderHTML('<script src="/tpl/js/record/Fr.voice.js?att='.time().'"></script>');
  $CFG->SITE->addToHeaderHTML('<script src="/tpl/js/record/record.js?att='.time().'"></script>');


	$CFG->SITE->addToHeaderHTML('<link rel="stylesheet" type="text/css" href="/tpl/cdn/jquery-ui.css">');
	$CFG->SITE->addToHeaderHTML('<link rel="stylesheet" type="text/css" href="/css/elfinder.min.css">');
	$CFG->SITE->addToHeaderHTML('<link rel="stylesheet" type="text/css" href="/css/theme.css">');

 ?>

<div class="addcoment_body comment-block__input-form">
    <form method="POST" id="comment-form" autocomplete="off">
        <input type="hidden" name="module_id" value="<?=$CFG->pid?>" />
        <input type="hidden" name="page_id" value="<?=$o->id?>" />
        <input type="hidden" name="user_act" value="add_comment" />
        <input type="hidden" name="attach_files_image" value="" class="attach_files_image" />
        <input type="hidden" name="attach_files_music" value="" />
        <input type="hidden" name="attach_сommercial" value="" />
        <input type="hidden" name="pcomment" value="0" id="pcomment_value" />
        <input type="hidden" name="premium_minus" value="" />
        <input type="hidden" name="accessrecord" value="" />
        <input type="hidden" name="premium" value="" />
        <input type="hidden" name="user_premium" value="" />
        <input type="hidden" name="attach_files" value="" class="data_attach_files" />
        <input type="hidden" name="reminder" value="" />
        <input type="hidden" name="big_3" id="big_3" value="" />
        <input type="hidden" name="big_10" id="big_10" value="" />
        <input type="hidden" name="important" id="important" value="0" />
        <div class="textarea_go">
            <textarea class="cops" placeholder="&#8250; Напишите заметку" <?=$e['text']?> name="text"><?=$o['text']?></textarea>
            <input type="submit" value="Сохранить" id="coment" />
        </div>
        <div id="textareaFeedback" class="ps_1"></div>
        <input type="hidden" name="task" id="task" value="0" />
        <input type="hidden" name="inform" id="inform" value="0" />
        <input type="hidden" name="access" id="access" value="0" />
        <input type="hidden" name="cashback" id="cashback_form" value="0" />

        <div class="window-task">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Дедлайн. Крайний срок исполнения задачи.</th>
                    <th>Исполнитель задачи</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><input type="text" name="off-task" value="<? echo date('Y-m-d 19:00:00');?>" id="off-task" /></td>
                    <td><select class="selectpicker show-tick manager" name="doer" data-live-search="true" ><option value="0">Выберите исполнителя</option> <option value="<?=$CFG->USER->USER_ID;?>" selected="selected"><?=$CFG->USER->USER_NAME;?> </option> <? $users = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 ORDER BY name ASC");  for ($i=0; $i<sizeof($users); $i++){ if($users[$i]->id == $CFG->USER->USER_ID) continue; ?> <option value="<?=$users[$i]->id;?>"><?=$users[$i]->name;?></option> <? } ?></select></td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>

        <style>
        #audio_int { margin: 10px 0; clear: both;}
        #audio_int .audio_mask { background: #F1F3F4; width: 300px; height: 54px; border-radius: 54px; display: flex; justify-content: space-around; align-items: center;align-content: center;}
        #audio_int .audio_mask .rec_icon{ background: #F84241; width: 20px; height: 20px; border-radius: 10px; margin-left: 5px;}
        #audio_int .audio_mask .procces{margin-right: 10px}
        .cancel_quick_notification { cursor: pointer; color:#5090C4;}
        .cancel_quick_notification:hover{ text-decoration: underline;}
        </style>


        <div id="audio_int">
          <audio controls src="" id="audio"></audio>
          <div class="option_record">
            <div class="audio_mask">
              <div class="rec_icon"></div>
              <div class="timer"></div>
              <div class="procces">Идет запись...</div>
            </div>
            <a id="stop_wav">Остановить запись</a>
            <a href="#" id="record-save" data-text="" class="disabled">Использовать эту запись</a>
          </div>
        </div>


        <script>

        function update_file_rec(data, id = '<?=$CFG->_GET_PARAMS[0];?>')
        {
          $.ajax({
            url: "/static/updata_record/" + id,
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(data)
            {
               if(data != 0)
               {
                 $('#record-save').attr('data-text', data);
               }
               else
               {

               }
            }
          });
        }

        </script>



         <div id="premium" class="mini" data-id="<?=$CFG->_GET_PARAMS[0];?>" title="Плюсовое самоначисление" data-toggle="tooltip" data-placement="bottom" data-original-title="Плюсовое самоначисление">$+</div>

        <div id="premium_minus" data-id="<?=$CFG->_GET_PARAMS[0];?>" title="Минусовое самоначисление" data-toggle="tooltip" data-placement="bottom" data-original-title="Минусовое самоначисление"></div>

        <div id="rec_wav" data-id="<?=$CFG->_GET_PARAMS[0];?>" title="Записать голосовое сообщение" data-toggle="tooltip" data-placement="top" data-original-title="Записать голосовое сообщение"></div>
        <div id="open_pase" data-id="<?=$CFG->_GET_PARAMS[0];?>" title="Мгновенное извещение" data-toggle="tooltip" data-placement="bottom" data-original-title="Мгновенное извещение"></div>
        <div id="reminder" data-rel="<?=$CFG->_GET_PARAMS[0];?>" title="" style="display:none"></div>
        <div id="file_upload" data-toggle="tooltip" data-placement="bottom" title="Прикрепить файл" data-original-title="Прикрепить файл"></div>
        <div id="file_upload_filebox" data-toggle="tooltip" data-placement="bottom" title="Прикрепить файл с FILE BOX" data-original-title="Прикрепить файл с FILE BOX"></div>

        <? if($CFG->USER->USER_ID == 1 ) { ?>
          <div id="give_out_money" data-toggle="tooltip" data-placement="bottom" title="Выдать деньги" data-original-title="Выдать деньги">₸</div>
        <? } ?>

        <div id="reminder_big_10" data-toggle="tooltip" data-placement="bottom" title="Горячее напоминания через 10 дней. Появится в левом нижнем углу экрана, через 10 дней" data-original-title="Горячее напоминания через 10 дней. Появится в левом нижнем углу экрана, через 10 дней"><span class="big">+10</span><span class="day">Дней</span></div>

        <div id="reminder_important" data-toggle="tooltip" data-placement="bottom" title="Отчетная работа" data-original-title="Отчетная работа">О</div>

        <div id="cashback" data-toggle="tooltip" data-placement="bottom" title="Начислить cashback" data-original-title="Начислить cashback"></div>




        <? if($CFG->USER->USER_WHATSAPP == 1) {  ?>
            <button id="whatsapp" style="border:0; border-radius:3px !important; background: #1EBEA5; color:#FFF;  font-family: 'segoeui';padding: 7px 7px; float:left; margin-top:5px;">  <img  src="/tpl/img/wh.png" style="width:18px;  vertical-align:top; position:relative; top:1px;"></button>
        <? } ?>




<script>
$('#give_out_money').on('click', function(e)
{
		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

		$(".modal-body").append('<h4 class="modal-title">Выдать деньги</h4>');
		$(".modal-body").append('<p><input style="width:240px " type="tel" class="form-control name" value="" placeholder="Сумма" required autofocus="autofocus"></p>');





    $(".modal-body").append('<div class="form-check form-check-inline">' +
      '<input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">' +
      '<label class="form-check-label" for="inlineCheckbox1">1</label>' +
    '</div>' +
    '<div class="form-check form-check-inline">' +
      '<input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">' +
      '<label class="form-check-label" for="inlineCheckbox2">2</label>' +
    '</div>');


		///$(".modal-body").append('<button type="submit" class="btn btn-primary">Продолжить</button>');



		$('.selectpicker').selectpicker();
		$('.form-control.name').attr('autofocus', 'autofocus');

		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{

		});

	e.preventDefault();
});
</script>



<style>
.comment-block-position #file_upload_filebox { border-radius:3px;}

.comment-block-position #give_out_money {
    width: 35px;
    height: 33px;
    margin-top: 7px !important;
    font-family: 'segoeui_b';
    font-size:20px;
    color: #01A54C;
    display: inline-block;
    border-radius: 3px;
    transition: all 0.2s;
    float: left;
    margin-right: 5px !important;
    background: #EFF3F6;
    padding-top: 2px;
    text-align: center;
}
</style>


	  <br clear="all">
        <div style="float:left; display:inline-block; position:relative;top:10px;">
            <select class="options selectpicker show-tick" name="dgdfg">
                <option value="0" selected>Выберите действие</option>
                <option value="1">Установить как задачу</option>
                <option value="2">Напоминание</option>
                <option value="3">Скрыть заметку</option>
            </select>

            <div class="select_all_div">
              <? if($CFG->USER->USER_NOTE_SELECT) { ?><a href="#" onClick="do_this()" class="select_all">Выбрать все</a> <? } ?>
            </div>



        </div>

    </form>

    <br clear="all">
        <div class="input-form__music"></div>
        <div class="input-form__reminder"></div>
        <div class="input-form__inform"></div>

        <div class="input-form__attachments-image"></div>


        <div class="input-form__attachments_files"></div>
        <div class="input-cashback"></div>

    </div>



<?

	$respon = getSQLRowO("SELECT page_id,parent_id,client_id FROM {$CFG->DB_Prefix}news WHERE id= '{$CFG->_GET_PARAMS[0]}' " );

    if($respon->page_id  == 868)
    {
        $mobile = unserialize($respon->data_whatsapp);
        $name = unserialize($respon->data_name);
   	}



    if(($CFG->USER->USER_ID == 85) || ($CFG->USER->USER_ID == 1)  || ($CFG->USER->USER_ID == 86)   || ($CFG->USER->USER_ID == 90)   || ($CFG->USER->USER_ID == 153)  )
    {
         $url = "";
    }
    else
    {
    	$url = "  AND user_id = {$CFG->USER->USER_DIRECTOR_ID} ";
    }

	$whatsapp_namber = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE visible = 1 {$url} order by id ASC " );
?>



<script>

$('#file_upload_filebox').bind('click', function(e)
{
	var fm = $('<div/>').dialogelfinder({
		url : '<? if($CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID ==481 || $CFG->USER->USER_ID ==133 || $CFG->USER->USER_ID ==536){ echo '/php/connector.minimal.php';} else { echo '/php/connector.minimal.manager.php';} ?>',
		height : 600,
		lang : 'ru',
		 resizable: false,
		 useBrowserHistory: false,
		commandsOptions : {
			getfile : {
				oncomplete : 'destroy',
			},

		},
		 quicklook : {
                    sharecadMimes : ['image/vnd.dwg', 'image/vnd.dxf', 'model/vnd.dwf', 'application/vnd.hp-hpgl', 'application/plt', 'application/step', 'model/iges', 'application/vnd.ms-pki.stl', 'application/sat', 'image/cgm', 'application/x-msmetafile'],
                    googleDocsMimes : ['application/pdf', 'image/tiff', 'application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/postscript', 'application/rtf'],
                    officeOnlineMimes : ['application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.oasis.opendocument.presentation']
                },
		getFileCallback : function(files, fm)
		{
			var real_url = '/' +files.path;

			var types = real_url.substring(real_url.lastIndexOf('.')+1, real_url.length) || real_url;

			if(types == 'jpg' || types == 'JPG' || types == 'jpeg' || types == 'GIF' || types == 'gif' || types == 'PNG' || types == 'png')
			{
				var en = ($('.attach_files_image').attr('value') == '') ? '' + real_url : $('.attach_files_image').attr('value') + ',' + real_url;

				$('.attach_files_image').attr('value', en);

				$('.input-form__attachments-image').append('<div class="add_img"><a href="#" class="cancel-attachment-img"><img src="' + real_url + '"/></a></div>');
			}
			else
			{
				var z = real_url.substring(real_url.lastIndexOf('/')+1);
				file_insert(real_url);
				$('#prev_docx').text(z);
				$('.input-form__attachments_files').html('<div class="add_file"><a href="' + z + '" target="_blank" class="cancel-attachment-сommercial">' + z + '</a></div>');
			}
		},


	}).dialogelfinder('instance');

	e.preventDefault();

});

$(document).on('click','#file_wp',function(e)
{
	var fm = $('<div/>').dialogelfinder({
		url : '<? if($CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID ==481){ echo '/php/connector.minimal.php';} else { echo '/php/connector.minimal.manager.php';} ?>',
		width : 840,
		lang : 'ru',
		useBrowserHistory: false,
		commandsOptions : {
			getfile : {
				oncomplete : 'destroy',
			}
		},
		getFileCallback : function(files, fm)
		{
			var real_url = '/' +files.path;

			var types = real_url.substring(real_url.lastIndexOf('.')+1, real_url.length) || real_url;

			if(types == 'jpg' || types == 'JPG' || types == 'jpeg' || types == 'GIF' || types == 'gif' || types == 'PNG' || types == 'png')
			{
				$('#test_wp').val(real_url);
				$('#prev_img').attr('src', real_url);
        $('#prev_docx').html('');
			}
			else
			{

        var z = real_url.substring(real_url.lastIndexOf('/')+1);
        var real_url = '/' +files.path;
        var types = real_url.substring(real_url.lastIndexOf('.')+1, real_url.length) || real_url;
        if(types == 'pdf' || types == 'PDF' )
        {
  				file_insert(real_url);
          $('#test_wp').val('');
          $('#prev_img').attr('src', '');
  				$('#prev_docx').text(z);
          $('.data_attach_files').val(real_url);
          $('.input-form__attachments_files').html('<div class="add_file"><a href="' + z + '" target="_blank" class="cancel-attachment-сommercial">' + z + '</a></div>');
        }
        else
        {
          alert('Отправлять на Whatsapp можно только PDF файлы!');
        }

			}
		},
	}).dialogelfinder('instance');

	e.preventDefault();
});



function file_insert(file)
{
	$.ajax
	({
		url: "/whatsapp/insert-file/",
		type: "POST",
		data: {"file": file, "page_id": "<?=$CFG->_GET_PARAMS[0];?>"},
		cache: true,
		success: function(response)
		{
			$('input[name=attach_files]').val(',' + response);
		}
	});
}



$('#whatsapp').on('click', function(e)
{
	var copy = $('textarea.cops').val();


		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;

		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
		$(".modal-body").append('<h4 class="modal-title"><center>Текст Whatsapp сообщения.</center></h4>');
		$(".modal-body").append('<p><input type="hidden" class="form-control Ondate" value="<?=$CFG->_GET_PARAMS[0];?>">');
		$(".modal-body").append('<textarea class="form-control" rows="8" style="margin-top:7px;" >'+copy+'</textarea>');

		///$(".modal-body").append('<a href="#" id="file_wp">Прикрепить файл</a>');

		$(".modal-body").append('<div id="file_wp" data-toggle="tooltip" data-placement="bottom" title="Прикрепить файл с FILE BOX" data-original-title="Прикрепить файл с FILE BOX"></div>');
		$(".modal-body").append('<div id="ctrl_v" data-id="<?=$CFG->_GET_PARAMS[0];?>" title="Вставить" data-toggle="tooltip" data-placement="bottom" data-original-title="Вставить"></div>');


		$(".modal-body").append('<input type="hidden" id="test_wp" value="">');

		$(".modal-body").append('<div style="clear:both; margin-top:50px;display:block;"></div><img src="" id="prev_img" style="width:200px;">');
		$(".modal-body").append('<div id="prev_docx" style="display:block;"></div>');

		$(".modal-body").append('<br><strong>От кого отправить?!</strong><br><select class="selectpicker show-tick manager list-view-manager from" title="От кого отправить"><? for ($i=0; $i<sizeof($whatsapp_namber); $i++){?> <option value="<?=$whatsapp_namber[$i]->id;?>"><?=$whatsapp_namber[$i]->name;?> - <?=$whatsapp_namber[$i]->namber;?></option> <? } ?></select><br>');

		$(".modal-body").append('<br><strong>Кому отправить?!</strong><br><select class="selectpicker show-tick manager list-view-manager pase" title="Кому отправить"><? wp_client_id($respon->client_id); ?></select><br>');

		$(".modal-body").append('<div class="markS"><button style="margin-top:10px;"  data-rel="2" type="button" class="btn btn-default">Отправить</button></div>');

		$('.selectpicker').selectpicker();

			$('textarea.form-control').keyup(function()
			{
				var curLength = $('textarea.form-control').val().length;
				var curText = $('textarea.form-control').val();
				$('#comment-form textarea').val(curText);
			});

			$('.markS > button').on('click', function(e)
			{
				var manager = $('.list-view-manager.pase').val();
				var from = $('.list-view-manager.from').val();

				var text = $('textarea.form-control').val();
				var Ondate = $('input.form-control.Ondate').val();

				var img_wp = $('input#test_wp').val();

				var file_wp = $('input.data_attach_files').val();

        if(text.length < 5)
        {
          alert('Необходимо ввести текст!'); return false;
        }

				$.ajax
				({
					url: "/whatsapp/send/",
					type: "POST",
					data: {"text": text, "Ondate": Ondate, "manager": manager, "from": from, "img": img_wp, "file_wp": file_wp},
					cache: true,
						beforeSend: function()
						{
							$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
						},
						success: function(response)
						{
							if(response == 1)
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Обновляем страницу...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
									setTimeout(function() {window.location.reload();}, 1000);
							}
							else
							{
								$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка, лучше обратится к администратору :(</h4></div>');
							}

						}
				});

				e.preventDefault();


			});


	e.preventDefault();
});

$('#reminder_big_10').on('click', function(e)
{
	if(  $('#reminder_big_10').hasClass('show') )
	{
		$('#reminder_big_10').removeClass('show');
		$('input#big_10').val('');
	}
	else
	{
		$('#reminder_big_10').addClass('show');
		$('input#big_10').val(10);
	}

	e.preventDefault();
});

$('#reminder_important').on('click', function(e)
{
	if(  $('#reminder_important').hasClass('show') )
	{
		$('#reminder_important').removeClass('show');
		$('input#important').val(0);
	}
	else
	{
		$('#reminder_important').addClass('show');
		$('input#important').val(1);
	}

	e.preventDefault();
});


$('#reminder_big_3').on('click', function(e)
{
	if(  $('#reminder_big_3').hasClass('show') )
	{
		$('#reminder_big_3').removeClass('show');
		$('input#big_3').val('');
	}
	else
	{
		$('#reminder_big_3').addClass('show');
		$('input#big_3').val(3);
	}

	e.preventDefault();
});



$(document).on('click','#ctrl_v',function(e)
{
	var id = $(this).attr('data-id');

	var cnt = 1;

	$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;

	$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

	$(".modal-body").append('<h4 class="modal-title">FAQ - Быстрый ответ</h4>');

	$(".modal-body").append('<select name="country_training" id="faq" class="selectpicker show-tick faq" data-live-search="true" title="Выберите faq"> <option value="0">Первичная рассылка</option> <?

	$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}faq WHERE page_id = 0 AND director_id = {$CFG->USER->USER_DIRECTOR_ID} order by id ASC");

	for ($i=0; $i<sizeof($sql); $i++)
	{
		echo '<option value="'.$sql[$i]->id.'">'.$sql[$i]->name.'</option>';

	}

?> ');
	$('.selectpicker').selectpicker();

		$('.selectpicker.faq').change(function ()
		{
			var id = $(this).find("option:selected").val();

			$.ajax
			({
				url: "/static/links/",
				type: "POST",
				data: {"id": id},
				cache: true,
				beforeSend: function()
				{
					$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
					$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
				},
				success: function(response)
				{
					response = $.parseJSON(response);

					$('textarea.cops').val(response.text);

					$('#whatsapp').click();
					$('#test_wp').val(response.img);
					$('#prev_img').attr('src', response.img);
				}

			});

		});

	e.preventDefault();
});




function abc(n) {
    return (n + "").split("").reverse().join("").replace(/(\d{3})/g, "$1 ").split("").reverse().join("").replace(/^ /, "");
}


$('#cashback').on('click', function(e)
{
		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

		$(".modal-body").append('<h4 class="modal-title">Укажите сумму КЕШБЕКА</h4>');
		$(".modal-body").append('<p><input style="width:240px " type="tel" class="form-control name" value="" placeholder="Сумма" required autofocus="autofocus"></p>');
		$(".modal-body").append('<select data-focus-on="true" class="selectpicker show-tick manager list-view-manager user_id" title="Выберите менеджера"><? wp_client_id($respon->client_id); ?></select>');

		$(".modal-body").append('<button type="submit" class="btn btn-primary" >Сохранить</button>');

		$('.selectpicker').selectpicker();


		$('.form-control.name').attr('autofocus', 'autofocus');

		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{
			var price = $('.form-control.name').val();
			var manager = $('.list-view-manager.user_id').val();

			if(price <= 0 )
			{
				alert("Укажите сумму КЕШБЕКА");
			}
			else
			{
				$.ajax
					({
						url: "/static/cashback/",
						type: "POST",
						data: {"price": price , "manager": manager, "page_id": <? print_r($CFG->_GET_PARAMS[0]);?> , "page_xcode": "<? print_r($CFG->oPageInfo->xcode);?>" },
						cache: true,
							beforeSend: function()
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
							},
							success: function(response)
							{
								 if(response > 0)
							    {
									$('input#cashback_form').val(response);
									$('.input-form__inform').append('<div class="inform" style=" display:block; padding-top:10px; padding-left:5px; "><strong>Вы зачислили КЭШБЭК '+price+' <? print_r($CFG->USER->USER_CURRENCY); ?>, для его активации завершите написание заметки.</strong></div>');
									$("#myModalBox").modal('hide');
									$('.modal-body').html('');

								}
								else
								{
									$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка, попробуйте еще раз :(</h4></div>');
								}

							}
					});

			}
		});

	e.preventDefault();
});


$('#open_pase').on('click', function(e)
{
		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;

		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Укажите менеджера</h4></div>');
    $(".modal-body").append('<p><input value="" type="hidden" class="quick_notification_id"></p>');
    $(".modal-body").append('<div class="quick_notification_div"></div>');

		$(".modal-body").append('<select data-focus-on="true" class="selectpicker show-tick manager list-view-manager pase" multiple data-live-search="true"  title="Выберите менеджера"><? $users = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 ORDER BY name ASC");  for ($i=0; $i<sizeof($users); $i++){ ?> <option value="<?=$users[$i]->id;?>"><?=$users[$i]->name;?></option> <? } ?></select>');
    $(".modal-body").append('<br><br><br><br><button type="submit" class="btn btn-primary" >Сохранить</button>');

		$('.selectpicker').selectpicker();

    $('ul.selectpicker li').removeClass('active');
    $('ul.selectpicker li').addClass('hide');

    var input_form = '.input-block-level.form-control';
		$(document).ready(function()
		{
			setTimeout(function()
      {
				 $('.pase').addClass('open');
         $(input_form).focus();

        }, 500);
		});

    $(input_form).keydown(function(event)
    {
      var keyCode = (event.keyCode ? event.keyCode : event.which);
      if (keyCode == 13)
      {
        var inp = event.target.value;
        if(inp != '')
        {
          EventName($(".list-view-manager.pase ul li.active").text());
        }
      }
    });

    $('ul.selectpicker li').mousedown(function(event)
    {
      event.preventDefault();
      if(event.button == 0)
      {
        EventName($(this).text());
      }
    });


    function EventName(name)
    {
      var user_id = $('select.selectpicker option').filter(function () { return $(this).html() == name; }).val();

      $('.quick_notification_id').val($('.quick_notification_id').val() + user_id + ',' );

      var values = $('.quick_notification_id').val().split(',');
      var uniq = Array.from(new Set(values));

      $('.quick_notification_id').val(uniq.join(','));

      $(".quick_notification_div").html('');

      $.each(uniq, function (index, val)
      {
        if (val != '')
        {
          $(".quick_notification_div").append('<p><span data-id="'+val+'" class="cancel_quick_notification">'+user_name_array(val)+' &#10006;</span></p>');
        }
      });


      $('.input-block-level.form-control').val('');
      $('.input-block-level.form-control').focus();

      $('ul.selectpicker li').removeClass('active');
      $('ul.selectpicker li').addClass('hide');
    }


    function user_name_array(id)
    {
      return $('select.selectpicker.list-view-manager.pase option').filter(function () { return $(this).val() == id; }).text();
    }


		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{
			var manager = $('.quick_notification_id').val();

			if(manager == null)
			{
				alert("Укажите все данные");
			}
			else
			{

				$.ajax
					({
						url: "/static/accessrecord/",
						type: "POST",
						data: {"manager": manager , "page_id":<?=$CFG->_GET_PARAMS[0];?> },
						cache: true,
							beforeSend: function()
							{
								//$(".modal-body").html('<h4 class="modal-title"><center>Создаем извещения...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
							},
							success: function(response)
							{

								response = $.parseJSON(response);

									$('.input-form__inform').append('<div class="inform" style=" display:block; padding-top:10px; padding-left:5px; "><strong>Вы установили извещения для, '+response.manager+' </strong></div>');

									$('input[name=accessrecord]').val($('input[name=accessrecord]').val() + ',' + response.id);

									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});

									$('.modal-body').html('');
							}
					});

			}
		});

	e.preventDefault();
});



$(document).on('click','.cancel_quick_notification',function(e)
{
  var id = $(this).attr('data-id');
  var value = $('.quick_notification_id').val().split(',');

  value.splice( jQuery.inArray(id, value), 1 );
  value.join(',');

  $('.quick_notification_id').val(value);
  $(this).remove();

  e.preventDefault();
});




$('.options.selectpicker').selectpicker();

$('.options').change(function ()
{
    var selectedText = $(this).find("option:selected").val();

	if(selectedText == 1)
	{
		$(".window-task").addClass('show');
		$('input#task').val(1);
	}

	if(selectedText == 2)
	{
		$('#reminder').click();
	}

	if(selectedText == 3)
	{

		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;

		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Укажите менеджера кому будет доступна эта заметка к показу</h4></div><br clear="all">');
		$(".modal-body").append('<select class="selectpicker show-tick manager list-view-manager pase" multiple="multiple" data-live-search="true"  title="Выберите менеджера"><? $users = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 ORDER BY name ASC");  for ($i=0; $i<sizeof($users); $i++){ ?> <option value="<?=$users[$i]->id;?>"><?=$users[$i]->name;?></option> <? } ?></select>');
		$(".modal-body").append('<button type="submit" class="btn btn-primary" >Сохранить</button>');

		$('.selectpicker').selectpicker();

		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{
			var manager = $('.list-view-manager').val();

			if(manager == null)
			{
				alert("Укажите все данные");
			}
			else
			{
				$('.input-form__inform').append('<div class="inform" style=" display:block; padding-top:10px; padding-left:5px; "><strong>Скрытая заметка</strong></div>');
				$('input#access').val(manager);

				$(document).ready(function(){
					$("#myModalBox").modal('hide');
				});

				$('.modal-body').html('');
			}
		});
	}

});


	if( document.getElementById('file_upload'))
	{
		var btn = $('#file_upload');

		 new AjaxUpload(btn,
		  {
		   data: {'pid_id' : '<?=$CFG->_GET_PARAMS[0];?>'},
		   name: 'file',
		   action: '/static/file_upload/',

		   onSubmit: function()
		   {
				$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;

				$(".modal-body").html('<h4 class="modal-title"><center>Идет загрузка файла, подождите пожалуйста...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

			},
			onComplete: function(file, response)
			{
        response = JSON.parse(response);

				switch(response.type)
				{
					case 'audio' :
						$('input[name=attach_files_music]').val(jQuery('input[name=attach_files_music]').val() + ',' + response.big);
						$('.input-form__music').append('<div class="add_file"><a href="' + response.big + '" target="_blank" class="cancel-attachment-music">' + response.name + '</a></div>');
					break;

					case 'image' :
						$('input[name=attach_files_image]').val(jQuery('input[name=attach_files_image]').val() + ',' + response.big);
						$('.input-form__attachments-image').append('<div class="add_img"><a href="#" class="cancel-attachment-img"><img src="' + response.med + '"/></a></div>');
					break;

					case 'other' :
						$('input[name=attach_files]').val($('input[name=attach_files]').val() + ',' + response.big);
						$('.input-form__attachments_files').append('<div class="add_file"><a href="' + response.big + '" target="_blank" class="cancel-attachment-doc">' + response.name + '</a></div>');
					break;
				}
        $(".modal-body").html('');
				$("#myModalBox").modal('hide');
      },dataType: "json"

		 });

	}

		$(document).on('click','.cancel-attachment-music',function(e)
		{
			var url = $(this).attr('href');

			var value = jQuery('input[name=attach_files_music]').val().split(',');
				value.splice( jQuery.inArray(url, value), 1 );
				value.join(',');

			$('input[name=attach_files_music]').val(value);
			$(this).remove();

			e.preventDefault();
		});


		$(document).on('click','.cancel-attachment-doc',function(e)
		{
			var url = $(this).attr('href');

			var value = jQuery('input[name=attach_files]').val().split(',');
				value.splice( jQuery.inArray(url, value), 1 );
				value.join(',');

			$('input[name=attach_files]').val(value);
			$(this).remove();

			e.preventDefault();
		});


		$(document).on('click','.cancel-attachment-сommercial',function(e)
		{
			var url = $(this).attr('href');

			var value = jQuery('input[name=attach_сommercial]').val().split(',');
				value.splice( jQuery.inArray(url, value), 1 );
				value.join(',');

			$('input[name=attach_сommercial]').val(value);
			$(this).remove();

			e.preventDefault();
		});






		$(document).on('click','.cancel-attachment-img',function(e)
		{
			var url = $(this).find('img').attr('src');

			var value = jQuery('input[name=attach_files_image]').val().split(',');
				value.splice( jQuery.inArray(url, value), 1 );
				value.join(',');

			$('input[name=attach_files_image]').val(value);
			$(this).remove();

			e.preventDefault();
		});


</script>
