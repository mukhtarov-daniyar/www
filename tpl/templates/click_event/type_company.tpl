&nbsp; &nbsp; &nbsp; &nbsp;
<a class="click_val type_company" href="#">Убрать все «Отрасли»</a>

<script>
$(document).on('click','.click_val.type_company',function(e)
{
  $('.selectpicker.type_company').val(0);
  $('.selectpicker.type_company').selectpicker('refresh');

  e.preventDefault();
});
</script>
