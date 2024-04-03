    <div class="navbar-inverse" role="navigation">
    
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <h1><a href="/<?=$CFG->SYS_LANG_NAME;?>/">Центр Карьеры<span>Ассоциация «Болашак»</span></a></h1>
        </div>
    
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
               <? if($CFG->USER->USER_ID == 0){?>
                
                <li><a href="/<?=$CFG->SYS_LANG_NAME?>/auth/" class="log">Войти</a></li>
                <li><a href="/<?=$CFG->SYS_LANG_NAME?>/registration/step_1/" class="sign">Зарегистрироваться</a></li> 
               <? } ?>
               <? if($CFG->USER->USER_ID > 0){?>
               	<? include($CFG->sidebar);?>
                <li><a href="/<?=$CFG->SYS_LANG_NAME?>/profile/view/<?=$CFG->USER->USER_ID;?>/" class="log"><img src="/tpl/img/login.png" alt="<?=$CFG->USER->USER_NAME;?>" class="auth" /><?=$CFG->USER->USER_NAME;?></a></li>
                <li><a href="/<?=$CFG->SYS_LANG_NAME?>/auth/logout" class="sign"><?=$CFG->Locale['quit']?></a></li>
               <? } ?>          
              
              <li class="dropdown navbar-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$CFG->SYS_LANG_NAME?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                   <? $LANG = getSQLArrayO("SELECT shortname,title FROM {$CFG->DB_Prefix}langs  WHERE visible='1' ORDER BY pos DESC");
                        for($i=0;$i<sizeof($LANG);$i++) 
                            {	$SYS_LANG = $LANG[$i];
                                ($CFG->SYS_LANG_NAME == $SYS_LANG->shortname) ? $param = ' class="active"' : $param = '';?>
                        <li<?=$param?>><a href="/<?=$LANG[$i]->shortname;?>/" title="<?=$LANG[$i]->title;?>"><?=$LANG[$i]->title;?></a></li>
                   <? }?>
                    
                </ul>
              </li>
            </ul>
        </div><!--/.nav-collapse -->
    
        <div class="container form_search">
            <div class="row">
                <form action="/<?=$CFG->SYS_LANG_NAME;?>/vacancy/" method="get">
                    <div class="col-md-2">
                    </div>
    
                    <div class="col-md-6">
                        <input type="text" name="search" placeholder=" &nbsp;<?=$CFG->Locale['search']?>"  id="go">
                    </div>
    
                    <div class="col-md-2">
                        <button type="submit"><span class="glyphicon glyphicon-search"></span>&nbsp; &nbsp;<?=$CFG->Locale['search']?></button>
                    </div>
    
                    <div class="col-md-2">
                    </div>    
                </form>
    
            </div>
            
                <div class="col-md-2">
                </div>
        
                <div class="col-md-6">
                    <a href="#showfilter" class="filter">Расширенный поиск</a>
                </div>
                
                <div class="col-md-4">
                </div>

        </div><!-- / form_search /!-->

    </div><!-- / navbar-inverse /!-->

<? include("./modules/news/tpl/filter_home.tpl");?>
