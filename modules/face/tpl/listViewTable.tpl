<style>
  table.table.person_table tbody tr:hover{ background:#EFEFEF; cursor: pointer;}
  table.table.person_table img{ width: 54px; height: 54px; border-radius: 50%;}
  table.table.person_table th, table.table.person_table td{ text-align: center; vertical-align: middle; padding:2px 5px;}
  table.table.person_table th{ font-weight: 100;     font-size: 15px;font-family: 'segoeui_sb';}
  table.table.person_table td span{ display: block; color: #999999; }
  table.table.person_table td a{  color: #F8403E; text-decoration: none;}

  table.table.person_table .siren img{ border: solid 2px #B200FF}
</style>

<table class="table person_table">
  <thead>
    <tr>
      <th scope="col"></th>
      <th scope="col">Обращение</th>
      <th scope="col">Мобильный</th>
      <th scope="col">Дата созд.</th>
      <th scope="col">Дата измен.</th>
      <th scope="col">Автор</th>
      <th scope="col">#</th>
    </tr>
  </thead>
  <tbody>
    <?
     for ($i=0; $i<sizeof($l); $i++)
     {
       $o = $l[$i];

       $name_others = explode(" ", $o->name_other);

       if( strlen($name_others[1]) > 1)
       {
         $acts = ' class="siren" ';
       }else {  $acts = '';  }
        ?>
       <tr <?=$acts;?> data-id="<?=$o->id;?>">
         <td><?=$FACE->logo($o->img, $o->id);?></td>
         <td><strong style="color:#F8403E"><?=$o->name;?></strong></td>
         <td><strong><? if($o->mobile !=''){echo $o->mobile;}else{ echo $o->international;} ?></strong></td>
         <td><? echo date('d.m.y', $o->cdate); ?> <span><? echo date('H:i', $o->cdate); ?></span></td>
         <td><? if($o->edate == null) {echo '-';} else echo date('d.m.y', $o->edate).'<span>'.date('H:i', $o->edate).'</span>';?></td>
         <td><? echo $o->name_user;?></td>
         <td>
           <a href="#"><i title="Редактировать лицо" data-toggle="tooltip" data-placement="bottom" class="glyphicon glyphicon-pencil" data-id="<?=$o->id;?>" ></i></a><br>
             <a href="#" ><i title="Отправить контакт на WhatsApp" data-mobile="<? if($o->mobile !=''){echo $o->mobile;}else{ echo $o->international;} ?>" data-toggle="tooltip" data-placement="bottom" class="glyphicon glyphicon glyphicon-share-alt wp_send_VCF"  data-id="<?=$o->id;?>"></i></a>

         </td>
       </tr>
  <?	}  ?>
  </tbody>
</table>





<script>




/*
document.querySelector('table.table.person_table tbody').addEventListener('mousedown',(event) => {
  if (event.buttons === 4)
  {
    event.preventDefault();
    let id = event.target.closest('tr').dataset.id;


var win =  window.open('/person/' + id, '_blank');
win.blur();

window.focus();

return false;

    //openInNewTab("https://google.com");
  }
});
*/


  $(document).on('click','table.table.person_table tbody tr',function(e)
  {
     var id = $(this).attr('data-id');
     document.location.href = "/person/" + id;
     e.preventDefault();
  });




  $(document).on('click','table.table.person_table .glyphicon-pencil',function(e)
  {
     var id = $(this).attr('data-id');
     document.location.href = "/profile/edit_person/" + id;

     return false;
  });


  let btnKeys = document.querySelectorAll('table.table.person_table tbody tr');
  for (var i = 0; i < btnKeys.length; i++)
  {
    btnKeys[i].addEventListener('mousedown', function(event)
    {
      if(event.which == 2)
      {
        window.open('/person/' + this.getAttribute('data-id'), '_blank');
      }
    });
  }


</script>
