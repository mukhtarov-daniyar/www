<?

if(isset($_POST[id]))
{
  $sql = getSQLArrayO("SELECT DISTINCT
  face_id,
  (SELECT concat(name, ' ', whatsapp) FROM my_face WHERE id = face_id  ) AS name,
  visible FROM my_tmp_whatsapp_rss WHERE whatsapp_id='{$_POST[id]}' ORDER BY id ASC limit 0, ".$_POST['limit']);

  foreach ($sql as $key => $value)
  {
    if( $value->visible == 1)
      {
        echo '<img src="/tpl/img/ok_on.png"  data-id="'.$value->face_id.'" style="width:16px; vertical-align:middle"><a href="/person/'.$value->face_id.'" target="_blank"> '.$value->name.'</a><br>';
      }
    if( $value->visible == 0)
      {
        echo '<img src="/tpl/img/ok_off.png" data-id="'.$value->face_id.'" style="width:16px; vertical-align:middle"><a href="/person/'.$value->face_id.'" target="_blank"> '.$value->name.'</a><br>';
      }
  }

  ?>
  <br><br>
  <div class="row text-center">
    <a href="#" class="btn btn-success mx-auto more_stat">Загрузить еще</a>
  </div>
  <?

}
