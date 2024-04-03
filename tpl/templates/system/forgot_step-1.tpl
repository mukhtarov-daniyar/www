<?
	$thisXcode = getFullXCodeByPageId($CFG->pid);
?>


<div class="contact-form">
    <p class="logo"><a href="/"><img src="/tpl/img/logo-info.png" alt="" title=""></a></p>
    <p class="access">Восстановить пароль</p>
    <form method="POST"   enctype="multipart/form-data" action="<?=$CFG->oPageInfo->_xcode?>forgot/step-1">
        <input type="hidden" name="user_act" value="forgot_user_password" />
        <input type="hidden" name="page_id" value="<?=$CFG->pid?>" />
        <div class="vvod">

            <p>Введит свой номер телефона</p>
            <input name="tel" type="text" class="mobile" placeholder="Номер телефона" <?=$e['tel']?> value="<?=$o['tel']?>" />

						<br>
						<div class="g-recaptcha" data-sitekey="<?=$CFG->ReCaptcha_site;?>"></div>

            <button type="submit" class="btn">Восстановить</button>
						<script src='https://www.google.com/recaptcha/api.js'></script>
            <br clear="all">
        </div>
    </form>
</div>
