&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<a class="click_val floor" href="#" data-id="1">Мужской</a> &nbsp; &nbsp; &nbsp;
<a class="click_val floor" href="#" data-id="2">Женский</a>

<script>
$(document).on('click','.click_val.floor',function(e)
{
  var id = $(this).attr('data-id');

  $('.selectpicker.floor').val($(this).attr('data-id'));

  $('.selectpicker.floor').selectpicker('refresh');

  e.preventDefault();
});
</script>
