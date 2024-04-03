<?	$CFG->oPageInfo->html_title = 'Список Whatsapp рассылок'; ?>

<h2>Список Whatsapp рассылок</h2>


<div class="white">       
<style type="text/css">

table.price {  width:100%; margin:0 auto; border-collapse: collapse; font-size:14px; text-align:center; margin-bottom:20px;  font-family: 'segoeui'; text-transform:uppercase}
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align:middle; font-size:12px; color:#000}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:15px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align:center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb';}

</style>
    <table class="price">
        <tr>
          <th>Автор</th>
          <th>Текст</th>
          <th>Картинка</th>
          <th>Дата созд.</th>
          <th>Адресаты</th>
          <th>Статус</th>
        </tr>
       <?
        $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp WHERE visible='1' order by cdate DESC ");
        for ($i=0; $i<sizeof($sql); $i++)
        {
        	$id = $sql[$i]->id;
           ?>
            <tr>
              <td><? $o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$sql[$i]->user_id}'"); echo $o->name;?></td>
              <td><?=$sql[$i]->text;?></td>
              <td><a href="<?=$sql[$i]->img;?>"><img src="<?=$sql[$i]->img;?>" style=" width:50px;"></a></td>
              <td><?=$sql[$i]->cdate;?></td>
              <td></td>
              <td> <? $respon = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_go WHERE whatsapp_id='{$id}' "); echo count($respon); ?> / 
              
              <span style="color:#F00"><? $res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_go WHERE whatsapp_id='{$id}' AND status = 1 "); echo count($res);?> </span>
              
              </td>
            </tr>       
           <?	
        }
       ?>        
    </table>

</div>