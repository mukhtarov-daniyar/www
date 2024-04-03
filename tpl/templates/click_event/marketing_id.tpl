&nbsp; &nbsp; &nbsp; &nbsp;
<a class="click_val marketing" href="#">Убрать все маркетинг планы</a>

<script>
$(document).on('click','.click_val.marketing',function(e)
{
  $('.selectpicker.marketing').val(0);
  $('.selectpicker.marketing').selectpicker('refresh');

  e.preventDefault();
});
</script>
