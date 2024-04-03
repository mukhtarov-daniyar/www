<?
	$thisXcode = getFullXCodeByPageId($CFG->pid);

?>

<div class='contact-form'>
    <p class='logo'><img src='/tpl/img/logo-info.png' alt='' title=''></p>
    <form method='POST' enctype='multipart/form-data' action='<?=$_SERVER['REQUEST_URI']?>'>
        <input type='hidden' name='user_act' value='enter_user' />
        <input type='hidden' name='page_id' value='<?=$CFG->pid?>' />
        <div class='vvod'>
            <p>Мобильный номер</p>
            <input placeholder='Введите свой мобильный' name='mobile' class="mobile" type='tel' <?=$e['mobile']?> value='<?=$o['mobile']?>' />

            <p>Пароль <a href='/auth/forgot/' class="parse">Забыли пароль?</a></p>
            <input placeholder='Введите свой пароль' name='passwd' class="passwd" type='password'<?=$e['password']?> value='<?=$o['password']?>' />
						<br>

            <button type='submit' class='btn'>Войти</button>
            <br clear='all'>
        </div>

    </form>
</div>
