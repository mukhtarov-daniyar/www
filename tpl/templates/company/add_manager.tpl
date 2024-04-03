<h2>Добавить пользователя</h2>
<br clear="all"><br clear="all">
<div class="row tab">
    <div class="col-md-6">
        <h1 class="active"><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Добавить нового пользователя</a></h1>
    </div>
    <div class="col-md-6">
        <h1><a href="/structure/">Структура компании</a></h1>
    </div>
</div>

<div class="white">



<?
	$test = $CFG->FORM->getFullForm();
	$e = $CFG->FORM->getFailInputs();
?>


<style>
.contact-form label{ font-family: 'segoeui_sb'; padding:0 !important; margin:0 !important}
.contact-form input{ font-family: 'segoe'; border-radius:5px; font-size:14px; box-shadow:none; border:0; border:#CCC 1px solid; padding:5px 10px;}
.contact-form .btn-group.bootstrap-select.show-tick{ width:190px !important}
.contact-form .btn-group.bootstrap-select.show-tick .btn{ margin:0 !important}
.contact-form  .btn {font-family: 'segoeui_sb'; font-size:14px; margin-top:20px; margin-bottom:20px;}
.contact-form  span.info { color:#999; display:block; font-size:12px;font-family: 'segoeui'; margin-top:20px;}
</style>

	<form class="contact-form" action="/profile/company/<?=$CFG->USER->USER_ID?>/manager/add/" enctype="multipart/form-data" method="POST" style="position: static; width:auto; text-align:left">
        <div class="row join">

            <div class="col-md-1">
            </div>

            <div class="col-md-5 o">
                <p class="brend"></p>

                <p>
                <label>Имя Фамилия</label><br>
                <input type="text"  name="name" value="<?=$test['name']?>" <?=$e["name"]?>></p>

                <p>
                <label>Мобильный телефон	</label><br>
                <input type="text" name="mobile" class="mobile" value="<?=$test['mobile']?>" <?=$e["mobile"]?>></p>

                <p>
                <label>Пароль	</label><br>
                <input type="password" name="passwd" value="<?=$test['passwd']?>" <?=$e["passwd"]?>></p>

                <p>
                <label>Повторите Пароль	</label><br>
                <input type="password" name="passwd2" value="<?=$test['passwd2']?>" <?=$e["passwd2"]?>></p>

                <button class="btn" type="submit">Создать</button>

            </div>

            <!--div class="col-md-5">
            	<br clear="all">
                <span class="info">1. Активный менеджер системы. (Менеджер участвующий во всех списках системы при формировании данных. Есть доступ ко всем разрешённым записям, возможность назначать уведомления, устанавливать будильники, назначать себе самоначисления быть участником бухгалтерии в получении и внесению финансов)</span>
                <span class="info">2. Пассивный сотрудник системы. Например - ГРУЗЧИК КОМПАНИИ! Учавствует только в бухгалтерских инструментах - начисление премий другими менеджерами. В систему сам не заходит, логина не имеет, его профиль может просматиривать любой сотрудник компании.  Он не отражается в клиентских списках.</span>
                <span class="info">3. Скрытый менеджер системы. Тот кто не учавствует в работе менеджеров и в системе фианнсов.</span>
            </div!-->

        </div>
    </form>




</div>
