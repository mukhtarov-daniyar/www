<a class="click_val city" href="#" data-id="4">Астана</a> &nbsp; &nbsp; &nbsp;
<a class="click_val city" href="#" data-id="13">Алматы</a> &nbsp; &nbsp; &nbsp;
<a class="click_val city" href="#" data-id="81">Красноярск</a> &nbsp; &nbsp; &nbsp;
<a class="click_val city" href="#" data-id="102">Москва</a> &nbsp; &nbsp; &nbsp;
<a class="click_val city" href="#" data-id="101">Кемерово</a> &nbsp; &nbsp; &nbsp;
<a class="click_val city" href="#" data-id="87">Новосибирск</a>

<script>
$(document).on('click','.click_val.city',function(e)
{
  var id = $(this).attr('data-id');

  $('.selectpicker.city').val($(this).attr('data-id'));

  $('.selectpicker.city').selectpicker('refresh')

  e.preventDefault();
});
</script>
