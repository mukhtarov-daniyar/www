&nbsp; &nbsp;
<a class="click_val skidka_led" href="#" data-id="1.15">1.15</a> &nbsp; &nbsp; &nbsp;
<a class="click_val skidka_led" href="#" data-id="1.00">1.00</a>&nbsp; &nbsp; &nbsp;
<a class="click_val skidka_led" href="#" data-id="0.97">0.97</a>&nbsp; &nbsp; &nbsp;
<a class="click_val skidka_led" href="#" data-id="0.94">0.94</a>

<script>
$(document).on('click','.click_val.skidka_led',function(e)
{
  var id = $(this).attr('data-id');

  $('input.skidka_led').val($(this).attr('data-id'));

  e.preventDefault();
});
</script>
