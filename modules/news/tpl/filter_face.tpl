<?

$data = $CFG->FORM->getFullForm();

?>





    <link href="/tpl/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/tpl/bootstrap/css/bootstrap-select.min.css" rel="stylesheet">


	  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


    <script type="text/javascript" src="/tpl/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/tpl/bootstrap/js/bootstrap-select.js"></script>



<style>
.add_face p { padding: 0; margin: 0;}
.add_face h2 { font-size: 15px; display: block; text-align: center;padding: 0; margin: 0; margin-top: 10px; font-weight: 600}

.add_face .col-md-4{ margin-bottom:10px;}
.add_go_face_data { display: block; text-align: center; text-align: center; margin-top:15px;}
</style>

    <div class="col-md-12 filter_hide block"><br>
		<form method="GET" class="add_face" action="/record/">
      <input type="hidden" name="company" value="<? echo $CFG->USER->USER_DIRECTOR_ID; ?>">
      <input type="hidden" name="ajax_pids" value="<? echo $_GET['ajax_pids'];?>">


            <div class="col-md-4">
            <p>Введите слово</p>
             <input type="text" name="search" placeholder="Поиск..." value="<?=$data['search']?>" id="full_search_input" <?=$e['search']?>/>
            </div>

            <div class="col-md-4">
                <p>Отрасль клиента:</p>
                <select name="type_company[]" class="selectpicker show-tick" id="type" >
                <option value="0">Любая</option>

               <?
                $type_company = AndDataArray('type_company', 'page_id', 868, 1, $order = 'pos, id ASC');

                for($i=0;$i<sizeof($type_company);$i++)
                {
                  if($data['type_company'][0] == $type_company[$i]->id) { $sel = ' selected="selected"'; }

                 ?>
                    <option value="<?=$type_company[$i]->id?>"<?=$sel?>><?=$type_company[$i]->name;?></option>
              <?  $sel = ""; } ?>
                </select>
            </div>


            <div class="col-md-4">
                <p>Местонахождение</p>
                <select name="country_training" id="country" class="selectpicker show-tick">
                <option value="0" selected>Выберите страну</option>
               <?
                $country_training = SelectDataArray('country_training', 1, 'name ASC');
                for($i=0;$i<sizeof($country_training);$i++)
                {
                    ($data['country_training'] == $country_training[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$country_training[$i]->id?>"<?=$sel?>><?=$country_training[$i]->name;?></option>
              <? } ?>
                </select>

                <div id="result"></div>
                <img src="/tpl/img/loading.gif" id="imgLoad" alt=""  />

                <? if(($data['country_training'] > 0)){?>
                <div class="okcity">
                	<?
                    	$dataS = SelectDataParent('city','page_id',$data['country_training']);
                        echo ' <select name="city" class="selectpicker show-tick" data-live-search="true" >';
                        echo ' <option value="0" selected>Любой</option>';
                        for($z=0;$z<sizeof($dataS);$z++)
                        {
                        	$key = array_search($dataS[$z]->id, $data['city']);

                            $hz = $data['city'];

                        	if($hz == $dataS[$z]->id)
                            {
                            	$sel = " selected";
                            }
                    		else
                            {
                            	$sel = "";
                            }
                        ?>
                          <option value="<?=$dataS[$z]->id?>" <?=$sel?>><?=$dataS[$z]->name;?></option>
                        <? }
                        echo ' </select>';

                    ?>

                    </div>
                <? }?>
            </div>

 			        <?  include("./modules/news/tpl/manager/boss.tpl"); ?>


           <div class="col-md-4">
            	<button type="submit" class="add_face_submit">Поиск</button>
            </div>
            <? 	echo ' <h2>Выбрано записей ('.$s.')</h2>'; ?>
            <a href="#" class="add_go_face_data">Добавить этот критерий к рассылке</a>
        </form>
	</div>

  <script>
  	$("#imgLoad").hide();
  /* Фильтр. Выбор местонахождение */
	$("#country").change(function()
	{
		if($(this).val() == 0)
		{
			$("form.response").append('<div class="fdfdfd"><input name="city" type="hidden" value="0"></div>');
		} else {	$(".fdfdfd").html('');	}

		$("#imgLoad").show();
		$(".selajax").hide();
		$(".okcity").hide();

		$.ajax
		({
			url: "/static/city/",
			type: "POST",
			data: {"country": $(this).val()},
			cache: true,
			success: function(response)
			{
				if(response == 0)
				{
					$("#result").html('');
					$("#imgLoad").hide();
				}
				else
				{
					$(".selajax").hide();
					$("#result").html(response);
					$('.selectpicker').selectpicker();
					$("#imgLoad").hide();

				}
			}
		});
	});
	/* Фильтр. Выбор местонахождение. End */


  /* Фильтр. Выбор менеджеров */
$("#manager").change(function()
{
  $("#imgLoadCompany").show();
  $("#resultCompany").hide();
  $(".refresh").hide();

  $.ajax
  ({
    url: "/static/company/",
    type: "POST",
    data: {"company": $(this).val()},
    cache: true,
    success: function(response)
    {
      if(response == 0)
      {
        $("#resultCompany").html('');
        $(".refresh").html('');
        $("#imgLoadCompany").hide();
      }
      else
      {
        $(".refresh").html('');
        $("#resultCompany").show();
        $("#resultCompany").html(response);
        $('.selectpicker').selectpicker();
        $("#imgLoadCompany").hide();

      }
    }
  });

});
/* Фильтр. Выбор менеджеров . End */

$(document).on('click','.add_face_submit',function(e)
{
  var targetForm = $('.add_face');
  var urlWithParams = targetForm.attr('action') + "?" + targetForm.serialize();
  var urls = urlWithParams+'&ajax=yes';

  $.get(urls, function(data, status)
  {
      location.href = urls;
  });

  e.preventDefault();
});

$(document).on('click','.add_go_face_data',function(e)
{

  var targetForm = $('.add_face');
  var urlWithParams = targetForm.attr('action') + "?" + targetForm.serialize();
  var urls = urlWithParams+'&&add=8';

  $.get(urls, function(data, status)
  {
    $('body').html('<br><br><br><br><center><h4>Критерий добавлен! Закройте окно</h4></center>');
  });


  e.preventDefault();
});



  </script>
