<?

switch ($CFG->_GET_PARAMS[0]) 
{
    case 'tovar':
        include('tpl/tovar.tpl');
     break;
	
    default:
       include('tpl/default.tpl');
	break;
}


?>




<style>
.white { background: top url('/tpl/img/bentley.jpg'); background-size:100%}
.white .speedometer { background: center no-repeat url('/tpl/img/bentley.png'); display:block; width:850px; height:376px; margin:0 auto; position:relative}
.white .speedometer .i_tovarov{  width:215px; height:215px; border-radius:50%;  position:absolute; top:81px; left:556px; color:#FFF;font-family: 'segoeui_sb'; }
.white .speedometer .i_tovarov span { position:absolute; bottom:-5px; width:50%; text-align:center; left:25%; font-size:11px}

.probeg { font-size:18px; text-transform:uppercase; font-family: 'segoeui_sb'; color:#FFF; text-align:center; position:absolute; top:57px; left:358px;line-height:16px;}
.probeg  span{ font-size:10px; text-transform: none; font-family: 'segoeui_sb'; color:#FFF; display:block; }

.b-gauge__label { font-family: 'segoeui_sb'; font-size:12px}

</style>




    <link href="/modules/speedometer/tpl/jquery-gauge.css" type="text/css" rel="stylesheet">
    <style>

        .demo2 {
            position: absolute;
            width: 215px;
            height: 215px;
            box-sizing: border-box;
            float:right;
            margin:0px;
			z-index:10000000
        }
    </style>




<h2>Управленческий учет</h2>
<div class="white">
	<div class="speedometer">
    
        <div class="probeg">
            <? $query = getSQLRowO("SELECT SUM(count) FROM {$CFG->DB_Prefix}data_1c WHERE visible = 1 "); echo number_sum($query->{'SUM(count)'}); ?>
            <span>общее количество товаров</span>
        </div>    
            
    
    	<div class="i_tovarov">
        	 <div class="gauge2 demo2"></div>
             <span>общая сумма товаров на складе</span>
        </div>
    </div>
</div>





   <script type="text/javascript" src="/modules/speedometer/tpl/jquery-gauge.min.js"></script>
    <script>

      

        // second example
        $('.gauge2').gauge({
            values: {
                0 : '0 МЛН',
                20: '100 МЛН',
                40: '400 МЛН',
                60: '800 МЛН',
                80: '1.2 МЛРД',
                100: '1.6 МЛРД'
            },
            colors: {
                0 : '#666',
				9 : '#378618',
                60: '#ffa500',
                80: '#f00'
            },
            angles: [
                129,
                413
            ],
            lineWidth: 3,
            arrowWidth: 8,
            arrowColor: '#f00',
            inset:true,

            value:  100
        });
    </script>




Все цифры указанны в <strong>ТЕНГЕ</strong><br>
Количество позиций <strong><? $query = getSQLRowO("SELECT COUNT(id) FROM {$CFG->DB_Prefix}data_1c WHERE visible = 1 "); echo number_sum($query->{'COUNT(id)'}); ?></strong> <br>
Общее количество товаров <strong><? $query = getSQLRowO("SELECT SUM(count) FROM {$CFG->DB_Prefix}data_1c WHERE visible = 1 "); echo number_sum($query->{'SUM(count)'}); ?></strong> <br>
Общая сумма товаров на складе по закупочной цене  <strong><? $query = getSQLRowO("SELECT SUM(total) FROM {$CFG->DB_Prefix}data_1c WHERE visible = 1 "); echo number_sum($query->{'SUM(total)'}); ?></strong> <br>


