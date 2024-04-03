<script>
//Вывести сумму заметок
$(document).ready(function()
{
  var arr = [];
  var cnt = 0;
  const myElement = document.querySelectorAll('.comment-block__comment-post .comment-post-body');

  for (let i = 0; i < myElement.length; i++)
  {
    var text = myElement[i].innerHTML;
    var repl = text.replace(/##(\w+)/g, '$1');
    text.replace(/##(\w+)/g,function(s,m1){  arr[i] = Number(m1); cnt ++; } );
  }

  //console.log( arr);

  if(cnt > 0)
  {
    const reducer = (previousValue, currentValue) => previousValue + currentValue;

    if(arr.reduce(reducer) > 0)
    {
      document.querySelector('.previousValue span').innerHTML = Number.parseInt(arr.reduce(reducer)).toLocaleString('ru');
    }
    else
    {
      document.querySelector('.previousValue span').innerHTML = 0;
    }
  }
  else
  {
    $(".previousValue").remove();
  }


});
//Вывести сумму заметок
</script>

<div class="col-md-12 textS previousValue" style=" margin-top:10px; margin-bottom:10px; display: block;">
   <div class="obj">
       <p class="gray"><strong>Cумма по заметкам: <span style="font-family: 'segoeui_sb';"></span> тг.</strong></a></p>
   </div>
</div>
