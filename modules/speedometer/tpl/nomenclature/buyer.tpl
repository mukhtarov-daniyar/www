<?
  if(count($l) > 0)
  {
      ?>

<style>
.rec_buyer { position: relative;}
.modal_rec { position: absolute; top: 0; left: 0; background: #fff; border: 1px solid #404040; margin: 0; padding: 0; list-style: none;}
.modal_rec li { margin: 0; padding: 0; font-size: 12px; padding:5px 5px; border-bottom:#ccc solid 1px; cursor: pointer; font-family: 'segoeui';}
.modal_rec li:hover { font-family: 'segoeui_b';}
</style>

    <ul class="modal_rec">
      <?
        foreach ($l as $value)
        {
          ?> <li><?=$value;?></li><?
        }
      ?>
    </ul>
      <?
  }
?>
