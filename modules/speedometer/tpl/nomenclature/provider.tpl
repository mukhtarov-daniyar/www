<?
  if(count($l) > 0)
  {
      ?>
    <ul class="provider_rec">
      <?
        foreach ($l as $value)
        {
          ?> <li data-id="<?=$value->id;?>"><?=$value->name;?></li><?
        }
      ?>
    </ul>
      <?
  }
?>
