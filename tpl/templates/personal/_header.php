<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <meta property="og:title" content="<?=ecrane($CFG->oPageInfo->html_title)?> -<?=$CFG->Locale["title"];?>" />
    <meta property="og:description" content="<?=ecrane($CFG->oPageInfo->html_description!="" ? $CFG->oPageInfo->html_description : $CFG->aSystemOptions["description"])?>" />
    <meta property="og:url" content="http://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>" />
    <meta property="og:image" content="<?=ecrane($CFG->oPageInfo->tmpl_a!="" ? $CFG->oPageInfo->tmpl_a : $CFG->aSystemOptions["tmpl_a"])?>" />
    <link rel="image_src" href="<?=ecrane($CFG->oPageInfo->tmpl_a!="" ? $CFG->oPageInfo->tmpl_a : $CFG->aSystemOptions["tmpl_a"])?>" />
    
    <meta name='keywords' content="<?=ecrane($CFG->oPageInfo->html_keywords!="" ? $CFG->oPageInfo->html_keywords : $CFG->aSystemOptions["keywords"])?>">
    <meta name="description" content="<?=ecrane($CFG->oPageInfo->html_description!="" ? $CFG->oPageInfo->html_description : $CFG->aSystemOptions["description"])?>">
    
    <title><?=ecrane($CFG->oPageInfo->html_title)?> - <?=$CFG->Locale["title"];?></title>

    <link rel="stylesheet" type="text/css" href="/tpl/css/style.css" />
    
    <link rel="icon" href="/tpl/img/favicon.png" type="image/x-icon" />
    <!--[if lt IE 9]><script src="/tpl/js/css3-mediaqueries.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="/tpl/js/html5.js"></script><![endif]-->	
    
    
    <!-- Bootstrap -->
    <link href="/tpl/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/tpl/bootstrap/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/tpl/css/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" />  

    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    
    <script type="text/javascript" src="/tpl/js/jquery.min.js"></script>
    
    <script type="text/javascript" src="/tpl/js/jquery.fancybox-1.3.4.js"></script>
    
    <script type="text/javascript" src="/tpl/js/jquery.maskedinput.js"></script>
    
    <script type="text/javascript" src="/tpl/js/jquery.placeholder.min.js"></script>
    
    
    <link rel="stylesheet" href="/tpl/css/input/form-field-tooltip.css" media="screen" type="text/css">
    <script type="text/javascript" src="/tpl/js/input/rounded-corners.js"></script>
    <script type="text/javascript" src="/tpl/js/input/form-field-tooltip.js"></script>
    
    <script src="/tpl/js/jquery-ui-1.10.3.custom.min.js"></script>
    <link rel="stylesheet" href="/tpl/css/jquery-ui.css">
    
    
    
    <script type="text/javascript">
        jQuery(function($){$(".mobile").mask("+9 (999) 999-99-99");});	
        jQuery(function($){$(".website").mask("http://.");});
		
		
		
        jQuery(function(){ jQuery('input[placeholder], textarea[placeholder]').placeholder();});
    </script>  

    <link href="/tpl/player/css/jplayer.blue.monday.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/tpl/player/jquery.jplayer.min.js"></script>
     
     <!-- BOOTSTRAP JS -->
    <script type="text/javascript" src="/tpl/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/tpl/bootstrap/js/bootstrap-select.js"></script>
    <script type="text/javascript" src="/tpl/bootstrap/assets/js/bootstrap-datepicker.js"></script>

<?
	for($i=0; $i<sizeof($this->headerblock); $i++)
	{
		echo $this->headerblock[$i];
	}

?>

</head>

	<body>
    
    
	<div class="alert"></div>
    
    
    
    <div class="navbar-inverse info" role="navigation">
    
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <h1><a href="/">Система IMPRESS</a></h1>
        </div>




    <div class="collapse navbar-collapse">

        <ul class="nav navbar-nav">
                   
           <? if($CFG->USER->USER_ID == 0){?>
            <li><a href="/auth/" class="sign">Войти</a></li>
           <? } ?>
           <? if($CFG->USER->USER_ID > 0){?>
			
            <li><a href="/auth/logout">Выход</a></li>
           
           <? } ?>          
            </ul>
          </li>
        </ul>
    </div><!--/.nav-collapse -->



</div><!-- / navbar-inverse /!-->

<div class="container">
	<div class="content">