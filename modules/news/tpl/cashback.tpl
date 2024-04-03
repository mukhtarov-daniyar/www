<?

    $mobile = unserialize($o->name_client_mobile); $search =  $mobile[0];
    
	$sql = getSQLArrayO("SELECT price FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$search}%' AND visible='1' AND status = 1 ");
    
    for($z=0;$z<sizeof($sql);$z++)
    {
    	$sumplus[] = $sql[$z]->price;
    }
    
 	$sql = getSQLArrayO("SELECT price FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$search}%' AND visible='1' AND status = 0 ");
    
    for($y=0;$y<sizeof($sql);$y++)
    {
    	$summinus[] = $sql[$y]->price;
    }
    
    $all = array_sum($sumplus) - array_sum($summinus);
    
?>




     <div class="col-md-12 textS">
        <div class="obj">
            <p class="gray"><strong>Кешбек клиента по номеру <span style="font-family: 'segoeui_sb';"><?=$search;?></span>  составляет: <span style="font-family: 'segoeui_sb';"><? echo $all.' '.$CFG->USER->USER_CURRENCY;?></span></strong></a></p>
        </div>
    </div> 
    <br clear="all">
    <br clear="all">
