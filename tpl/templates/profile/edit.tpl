<?
	$MODULE_ = getFullXCodeByPageId($CFG->pid);
	$e = $CFG->FORM->getFailInputs();

	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');
?>

<style>
.white .avatar { width:150px; display:block;  margin-top:10px; margin-bottom:20px;}
.white .avatar img#upSAvatar{ width:150px; border-radius:75px; border:#CCC 1px solid}
.white .avatar #upAvatar{ display:block; color:#000; text-decoration:underline; font-size:14px; height:auto !important; width: auto !important; font-weight:600}

</style>

<?=showHeader2("Редактирование профиля");?>


<div class="white">
    <article class="vacancies_body row">
        <div class="table-responsive kriteri" style="width:100%">

        <form method="POST" enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>">
            <input type="hidden" name="user_act" value="update_profile" />

			<input type="hidden" name="id" value="<?=$data['id']?>"<?=$e['id']?> />
			<input type="hidden" id="avatar_input" name="avatar" value="<?=$data['avatar']?>"<?=$e['avatar']?> />

            <div class="avatar">
            	<a href="#" id="upAvatar"><img id="upSAvatar" src="<? echo $data['avatar'];?>" alt="" />
            	Загрузить аватар <span style="color:#F00; display:inline-block">*</span></a>
            </div>

 			<p>
            	<label>Имя Фамилия <span style="color:#F00; display:inline-block">*</span></label><br>
                <input type="text" name="name" placeholder="Ваше имя" value="<?=$data['name']?>"<?=$e['name']?> /></p>
 			<p>
            	<label>Выберите пол: <span style="color:#F00; display:inline-block">*</span><span style="display:inline-block; font-size:12px; color:#999; padding-left:5px; font-weight:100">(Обязательное поле)</span></label><br>
 					<select name="pauls" class="selectpicker">
                   <? $sex = array(0=>'Не указан', 1=>'Мужской', 2=>'Женский');
                    for($z=0; $z<sizeof($sex); $z++)
                    {
                        ($data['pauls'] == $z) ? $sel = "selected" : $sel = "";?>
                        <option value="<?=$z?>"<?=$sel?>><?=$sex[$z]?></option>
                   <? } ?>
                    </select>
                    </p>

             <p>
             	<label>Дата рождения: <span style="color:#F00; display:inline-block">*</span><span style="display:inline-block; font-size:12px; color:#999; padding-left:5px; font-weight:100">(Обязательное поле)</span></label><br>
                <input id="type-vse-date" type="text" name="dob" value="<?=$data['dob']?>"<?=$e['dob']?> placeholder="Дата рождения:"/></p>


             <p>
             	<label>Телефон <span style="color:#F00; display:inline-block">*</span><span style="display:inline-block; font-size:12px; color:#999; padding-left:5px; font-weight:100">(Обязательное поле)</span></label><br>
                <input type="text" class="mobile" name="mobile" placeholder="Телефон" value="<?=$data['mobile']?>"<?=$e['mobile']?> /></p>



                <p>
             	<label>E-mail <span style="color:#F00; display:inline-block">*</span><span style="display:inline-block; font-size:12px; color:#999; padding-left:5px; font-weight:100">(Обязательное поле)</span></label><br>
                <input type="text" name="email" placeholder="Электроный адрес:" value="<?=$data['email']?>"<?=$e['email']?> /></p>

                <p>
             	<label>Город: <span style="color:#F00; display:inline-block">*</span><span style="display:inline-block; font-size:12px; color:#999; padding-left:5px; font-weight:100">(Обязательное поле)</span></label><br>
                    <select name="city" class="selectpicker show-tick" data-live-search="true">
                   <?
                    $city = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}city  WHERE visible='1' ORDER BY id ASC");
                    for($i=0;$i<sizeof($city);$i++)
                    {
                        ($data['city'] == $city[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                        <option value="<?=$city[$i]->id?>"<?=$sel?>><?=$city[$i]->name?></option>
                  <? } ?>
                    </select></p>
               <p>
             	<label>Куратор</label><br>
                    <select name="curator" class="selectpicker show-tick" data-live-search="true">
                    <option value="0">Выберите куратора</option>
                   <?


                    $taks_id = getSQLArrayO("SELECT id,name FROM {$CFG->DB_Prefix}users  WHERE visible='1' and user_id='{$CFG->USER->USER_DIRECTOR_ID}' ORDER BY id ASC");
                    for($i=0;$i<sizeof($taks_id);$i++)
                    {
                        ($data['curator'] == $taks_id[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                        <option value="<?=$taks_id[$i]->id?>"<?=$sel?>><?=$taks_id[$i]->name?></option>
                  <? } ?>
                    </select></p>


                <p>
             	<label>Должность<span style="color:#F00; display:inline-block">*</span><span style="display:inline-block; font-size:12px; color:#999; padding-left:5px; font-weight:100">(Обязательное поле)</span></label><br>
                <input type="text" name="position" placeholder="Должность:" value="<?=$data['position']?>"<?=$e['position']?> /></p>

                <p>
             	<label>Стартовая страница</label><br>
                <input type="text" name="home_url" placeholder="Стартовая страница:" value="<?=$data['home_url']?>"<?=$e['home_url']?> /></p>



                <p>
             	<label>Обо мне, в свободной форме<span style="color:#F00; display:inline-block">*</span><span style="display:inline-block; font-size:12px; color:#999; padding-left:5px; font-weight:100">(Обязательное поле)</span></label><br>
                <textarea style="width:410px; height:30px; font-size:14px;" name="info"<?=$e['info']?>><?=$data['info']?></textarea> </p>




               <p>
             	<label>Страница премирования</label><br>
                    <select name="taks_id" class="selectpicker show-tick" data-live-search="true">
                    <option value="0">Выберите запись</option>
                   <?
                    $taks_id = getSQLArrayO("SELECT id,name_company FROM {$CFG->DB_Prefix}news  WHERE visible='1' and page_id=976 and manager_id='{$data[id]}' ORDER BY id ASC ");
                    for($i=0;$i<sizeof($taks_id);$i++)
                    {
                        ($data['taks_id'] == $taks_id[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                        <option value="<?=$taks_id[$i]->id?>"<?=$sel?>><?=$taks_id[$i]->name_company?></option>
                  <? } ?>
                    </select></p>


             	<p><label>Страница основных средств</label><br>
                    <select name="os_id" class="selectpicker show-tick" data-live-search="true">
                    <option value="0">Выберите запись</option>
                   <?
                    $taks_id = getSQLArrayO("SELECT id,name_company FROM {$CFG->DB_Prefix}news  WHERE visible='1' AND find_in_set(10011926,type_company_id) <> 0 and page_id=976 and manager_id='{$data[id]}' ORDER BY id ASC");

                    for($i=0;$i<sizeof($taks_id);$i++)
                    {
                        ($data['os_id'] == $taks_id[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                        <option value="<?=$taks_id[$i]->id?>"<?=$sel?>><?=$taks_id[$i]->name_company?></option>
                  <? } ?>
                    </select></p>




                <p>
             	<label>Страница должностных инструкций</label><br>
                    <select name="official_id" class="selectpicker show-tick" data-live-search="true">
                    <option value="0">Выберите запись</option>
                   <?
                    $taks_id = getSQLArrayO("SELECT id,name_company FROM {$CFG->DB_Prefix}news  WHERE visible='1' AND find_in_set(10011927,type_company_id) <> 0 and page_id=976 and manager_id='{$data[id]}' ORDER BY id ASC ");
                    for($i=0;$i<sizeof($taks_id);$i++)
                    {
                        ($data['official_id'] == $taks_id[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                        <option value="<?=$taks_id[$i]->id?>"<?=$sel?>><?=$taks_id[$i]->name_company?></option>
                  <? } ?>
                    </select></p>

                <p>
             	<label>Страница физ. лицо</label><br>
                    <select name="chl_id" class="selectpicker show-tick" data-live-search="true">
                    <option value="0">Выберите запись</option>
                   <?

                    $taks_id = getSQLArrayO("SELECT id,name, mobile FROM {$CFG->DB_Prefix}face  WHERE mobile LIKE '%{$data[mobile]}%' AND visible='1'  AND page_id=1012 and manager_id='{$data[id]}'  ORDER BY id ASC");
                    //print_r($taks );
                    for($i=0;$i<sizeof($taks_id);$i++)
                    {
                        ($data['chl_id'] == $taks_id[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                        <option value="<?=$taks_id[$i]->id?>"<?=$sel?>><?=$taks_id[$i]->name;?> <?=$taks_id[$i]->mobile;?></option>
                  <? } ?>
                    </select></p>

				<?  if($CFG->USER->USER_BOSS == 1 || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 547) { ?>

				<p>
             	<label>Оклад БУ+УУ (отражается в СРМ, в Бюджетировании) </label><br>
                <input type="text" name="salary_total" placeholder="" value="<?=$data['salary_total']?>"<?=$e['salary_total']?> /></p>

				<p><label>Оклад БУ (оригинальная цифра с 1С)</label><br>
           <input type="text" name="salary_bu" placeholder="" value="<?=$data['salary_bu']?>"<?=$e['salary_bu']?> />
				 </p>

				 <p><label>Оклад УУ (оригинальная цифра в СРМ)</label><br>
            <input type="text" name="salary_uu" placeholder="" value="<?=$data['salary_uu']?>"<?=$e['salary_uu']?> />
 				 </p>

				<? } ?>

				<hr><span style="display:inline-block; font-size:12px; color:#999;font-weight:100">Рекомендуем создать новый пароль, что бы его никто не знал.</span>
				<p>
             	<label>Новый пароль</label><br>
                <input type="password" name="passwd" placeholder="" value="<?=$data['passwd']?>"<?=$e['passwd']?> /></p>

              	<p>
             	<label>Повторите новый пароль</label><br>
                <input type="password" name="passwd2" placeholder="" value="<?=$data['passwd2']?>"<?=$e['passwd2']?> /></p>

        		<br clear="all">
        		<input type="submit" value="Сохранить" class="btn none" />
                <br clear="all"><br clear="all"><br clear="all"><br clear="all"><br clear="all"><br clear="all">
        </form>
     </div>
</article>


<script type="text/javascript">

	if( document.getElementById('upAvatar') )
	{
		var btn = $('#upAvatar');

		 new AjaxUpload(btn,
		  {
		   data: {'user_act' : 'user_avatars'},
		   name: 'avatar',
		   action: '/static/user_avatar/',
		   autoSubmit: true,
		   onComplete: function(file, response)
		   {
			response = $.parseJSON(response);

			if( typeof response.med == 'undefined' || response.med == null )
			{
			 return false;
			}


			$('#upSAvatar').attr('src', response.med);
			document.getElementById('avatar_input').value = response.med;

		   }
		  });
	}

</script>
