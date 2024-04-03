<?

$MODULE_ = getFullXCodeByPageId($CFG->pid); 
$e = $CFG->FORM->getFailInputs();
$data = $CFG->FORM->getFullForm();



?>


<br>

    <div class="row tab">
        <div class="col-md-3">
            <h1><a href="/record/">Клиентские</a></h1>
        </div>    	
        <div class="col-md-3">
            <h1><a href="/office/">Служебные</a></h1>
        </div>
        

        <? if($CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 244 || $CFG->USER->USER_ID == 311 || $CFG->USER->USER_ID == 310) { ?>     
        <div class="col-md-4">
            <h1 class="active"><a href="/alimzhanov-history/">Личный дневник</a></h1>
        </div>
        <? } ?>
       
    </div>



    <div class="col-md-12 filter_hide block">
    <br clear="all">
		<form method="GET" enctype="multipart/form-data" class="response" action="/office/">

            <div class="col-md-9">  
             <input type="text" name="search" value="<?=$data['search']?>" placeholder="Введите текст или номер *записи. ВНИМАНИЕ - вводить 1 слово"  id="full_search_input"<?=$e['search']?>/>
            </div>
           
            <div class="col-md-3" style="text-align:left; position:relative; top:-17px;">  
                <button type="submit"><span class="glyphicon glyphicon-search"></span> &nbsp; Поиск</button>
            </div>
                        
            
        </form>  
        <br clear="all">
	</div>


