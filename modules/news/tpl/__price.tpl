<script>
//Вывести сумму заметок
$(document).ready(function()
{
  var arr = [];
  const myElement = document.querySelectorAll('.comment-block__comment-post .comment-post-body');
  for (let i = 0; i < myElement.length; i++)
  {

    var text = myElement[i].innerHTML;
    var repl = text.replace(/##(\w+)/g, '$1');
    text.replace(/##(\w+)/g,function(s,m1){  arr[i] = Number(m1); } );
  }
  const reducer = (previousValue, currentValue) => previousValue + currentValue;
  console.log(arr.reduce(reducer));
});
//Вывести сумму заметок
</script>
