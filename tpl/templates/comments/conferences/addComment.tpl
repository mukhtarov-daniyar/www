<?
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery/jquery.ui.widget.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery/jquery.fileupload.js"></script>');
    $CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery/jquery.iframe-transport.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/comments.js"></script> ');	
?>

<?
     $o = unserialize($_SESSION['form']);
     $e = unserialize($_SESSION["register"]);
?>

<div class="comment-block__input-form">

    <form id="comment-form" action="<?=$_SERVER['REQUEST_URI']?>/status/" method="POST" autocomplete="off">
        <input type="hidden" name="pcomment" value="0" />
		<input type="hidden" name="user_act" value="add_comment" />
<?
 if($CFG->USER->USER_ID) 
 {
?>

		<input type="hidden" name="attachments" value="0" />

<table class="input-form__table">
    <tr>
        <td><img class="input-form__avatar" src="<?=$CFG->USER->USER_AVATAR?>" /> <span class="font-upper-weight"><?=$CFG->USER->USER_NAME?></span></td>
    </tr>
    <tr>
        <td>
        	<textarea class="input-form__input"<?=$e['text']?> name="text"><?=$o['text']?></textarea><br />
        	<div class="input-form__status-text"> </div>           
        </td>
    </tr>
    <tr>
        <td>
        	<input class="input-form__submit-button button-green font-upper-light" type="submit" value="<?=$CFG->Locale['send']?>" />
        </td>
    </tr>
</table>

<?
  }else
  {
?>
<table class="input-form__table">
    <tr>
    	<td class="input-form__table_td-description font-upper-weight">Ваше Имя</td>
        <td><input type="text" name="name" placeholder="<?=$CFG->Locale["name"]?>" value="<?=$o["name"]?>"<?=$e['name']?> /></td>
    </tr>
    <tr>
    	<td class="input-form__table_td-description font-upper-weight">Ваш Email (не будет опубликован)</td>
        <td><input type="text" name="email" placeholder="Email" value="<?=$o["email"]?>"<?=$e['email']?> /></td>
    </tr>
    <tr>
    	<td class="input-form__table_td-description font-upper-weight">Текст сообщения</td>
        <td><textarea class="comment-block__input-form_input" placeholder="<?=$CFG->Locale["message"]?>"<?=$e['text']?> name="text"><?=$o['text']?></textarea></td>
    </tr>
    <tr>
    	<td class="input-form__table_td-description font-upper-weight">Проверочный код</td>
        <td><input type="text" name="captcha" maxlength="4" placeholder="Введите код"<?=$e['captcha']?> /><img class="input-form__captcha" src="/xnum.php" /></td>
    </tr>
    <tr>
    	<td></td>
        <td>
        	<input class="input-form__submit-button button-green font-upper-light" type="submit" value="<?=$CFG->Locale['send']?>" />
        	<div class="input-form__status-text"> </div>
        </td>
    </tr>
</table>
<? } ?>

   </form>
   
</div>

<?
    unset($_SESSION["register"]);
    unset($_SESSION["form"]);
?>