
<? echo list_boss_manager($data['manager_id']);?>
<br>
 &nbsp; &nbsp; &nbsp;<a class="click_val manager" href="#" data-id="133">Анастасия Замятина</a>
 &nbsp; &nbsp; &nbsp;<a class="click_val manager" href="#" data-id="572">Аягоз Сагитова</a>
 &nbsp; &nbsp; &nbsp;<a class="click_val manager" href="#" data-id="143">Дина Дюсембаева</a>

<script>
$(document).on('click','.click_val.manager',function(e)
{
  var id = $(this).attr('data-id');

  $('.selectpicker.manager').val($(this).attr('data-id'));

  $('.selectpicker.manager').selectpicker('refresh')

  e.preventDefault();
});
</script>
