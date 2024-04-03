<? 
	$MODULE_ = getFullXCodeByPageId($CFG->pid); 
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
?>


<link rel="stylesheet" href="/tpl/media/wysiwyg/elrte/css/elrte.min.css" type="text/css" media="screen" charset="utf-8">
<script src="/tpl/media/wysiwyg/elrte/js/jquery-ui-1.8.13.custom.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/tpl/media/wysiwyg/elrte/js/elrte.min.js"  type="text/javascript" charset="utf-8"></script>
<script src="/tpl/media/wysiwyg/elrte/js/i18n/elrte.ru.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" src="/js/upload/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/js/upload/jquery.fileupload.js"></script>
<script type="text/javascript" src="/js/upload/jquery.iframe-transport.js"></script>

<script type="text/javascript" src="/tpl/js/comments.js"></script>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {

	elRTE.prototype.options.panels.web2pyPanel = ['bold', 'italic', 'underline', 'forecolor', 'justifyleft', 'justifyright', 'justifycenter', 'justifyfull', 'insertorderedlist', 'insertunorderedlist'];
	elRTE.prototype.options.toolbars.web2pyToolbar = ['web2pyPanel'];
	
	var opts = {
		toolbar  : 'web2pyToolbar',
		cssClass : 'el-rte',
		lang     : 'ru',
		height   : 200,
		width    : 600,
		allowSource : false,
	}	

	$('#wysiwyg').elrte(opts);

});
</script>

<div class="zayavka pp window">
    <div class="content">
    
       <?=showHeader2('Профиль');?>
              
        <div class="profil_menu">
            <a href="<?=$MODULE_?>edit">Мой профиль</a>
            <a href="<?=$MODULE_?>project" class="act">Мои проекты</a>
            <!--a href="<?=$MODULE_?>comment">Мои обсуждения</a!-->
            <a href="/<?=$CFG->SYS_LANG_NAME?>/auth/logout">Выход</a>
        </div>
        
        <br clear="all">      
        	<div class="black"> <span class="icon-arrow-left"></span> <a href="/<?=$CFG->SYS_LANG_NAME?>/profile/project/">вернуться</a></div>
        <br clear="all">

    
        <div class="bbbb">
            <div class="content_body prof">
               <? 
                    $sql = "SELECT * FROM {$CFG->DB_Prefix}news WHERE sys_language='{$CFG->SYS_LANG}' and user_id='{$CFG->USER->USER_ID}' and id='{$CFG->_GET_PARAMS[1]}'";
                    $top = getSQLArrayO($sql);
                ?>

				<div class="hed-title"><?=$top[0]->name?></div>
                <div class="addcoment_body">
                    <form id="comment-form-form-upload" method="POST" autocomplete="off">
                        <input type="hidden" name="module_id" value="460" />
                        <input type="hidden" name="page_id" value="<?=$top[0]->id?>" />
                        <input type="hidden" name="ltype" value="<?=$CFG->SYS_LANG?>" />
                        <input type="hidden" name="pcomment" value="0" id="pcomment_value" />
                        <input type="hidden" name="attach_files" value="0" />
                        <input type="hidden" name="user_act" value="edit_simple_comment" />
                        <input type="text" name="news_name" placeholder="Название проекта" value="<?=$top[0]->name?>" id="news_name" />
                        <select name="kat" class="kat">
                           <? 
                            $sql = "SELECT * FROM {$CFG->DB_Prefix}kat WHERE sys_language='{$CFG->SYS_LANG}' and page_id='{$top[0]->page_id}' and visible='1' ORDER BY id ASC";
                            $tops = getSQLArrayO($sql);
                            for ($i=0; $i<sizeof($tops); $i++)
                            {
                                ($top[0]->kat == $i+1) ? $sel = "selected" : $sel = "";
                            ?>
                            <option value="<?=$tops[$i]->id;?>"<?=$sel?>><?=$tops[$i]->name;?></option>
                           <? } ?>
                        </select>
                        <input type="text" name="intro" placeholder="Краткое описание" value="<?=$top[0]->intro?>" id="intro" />
                        <textarea id="wysiwyg" placeholder="<?=$CFG->Locale["message"]?>"<?=$e['text']?> name="text"><?=$top[0]->body?></textarea> 
                        <input type="submit" value="<?=$CFG->Locale['send']?>" class="btn btn-danger send" />
                    </form>
                
                    <form id="form-upload">
                        <input type="hidden" name="user_act" value="attachment_to_comment" />
                        <input class="input-form__input-file" type="file" name="comment-file" multiple="" data-url="/sys_comment.php" />
                    </form>
                
                
                    <div class="input-form__status-text"> </div>
                    <div class="input-form__attachments" style=" margin-left:30px; margin-bottom:30px;"> 
                        <div class="input-form__attachments-files"> </div>
                        <div class="input-form__attachments-image"> 
                        
                       <? 
                        if ( $top[0]->img != '' || $top[0]->img != '0')
                        {
                        $images = explode(",", $top[0]->img);
                        
                            for($n=0; $n<sizeof($images); $n++){
                            
                            if ( $images[$n] == '' || $images[$n] =='0') continue;
                             
                                $gallery = makePreviewName("/tpl/img/nophoto.jpg", 110, false, 2); 
                                    if ($images[$n] != "")
                                $gallery = makePreviewName($images[$n], 110, false, 2);
                        ?>
                        <a href="#" class="cancel-attachment"><img style="width: 110px; padding: 4px; float: left;" class="image" src="<?=$gallery?>"/></a> 
                           <? } ?>
                       <? } ?>
                        </div>
                    </div>
                   <br clear="all">
                   <br clear="all">
                </div>
			</div>
		</div>
	</div>
<div style="height:30px;"></div>
</div>

<script type="text/javascript">
$(window).bind('resize', function(e)
{
	$('.window').attr('style', 'height:' + (($(document).height()/100)*100) + 'px');
});
</script>