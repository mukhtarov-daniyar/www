<?
if($_POST['type'] == 'select')
{
  $num = $_POST['num'];

  $l = getSQLArrayO(" SELECT my_reminderhtml.*,  SUBSTRING(my_comments.text, 1, 100) AS short_text, my_users.name, my_news.name_company FROM my_reminderhtml JOIN my_comments ON my_reminderhtml.coment_id = my_comments.id JOIN my_users ON my_reminderhtml.user_id = my_users.id JOIN my_news ON my_reminderhtml.page_id = my_news.id WHERE my_comments.visible = 1 AND my_users.user_id = {$CFG->USER->USER_DIRECTOR_ID} ORDER BY my_reminderhtml.id DESC LIMIT 10 OFFSET {$num}; ");

  foreach ($l as $key => $value)
  {
    ?>
    <tr>
       <td><a href="/search/*<?=$value->page_id;?>#comment-post_<?=$value->coment_id;?>"><strong>*<?=$value->page_id;?></strong></a></td>
       <td><a href="/search/*<?=$value->page_id;?>#comment-post_<?=$value->coment_id;?>"><?=dateSQL2TEXT($value->cdate, "DD.MM.YY hh:mm");?></a></td>
       <td><a href="/profile/view/<?=$value->user_id;?>"><?=$value->name;?></a></td>
       <td><a href="/search/*<?=$value->page_id;?>#comment-post_<?=$value->coment_id;?>"><strong><?=$value->name_company;?></strong></a></td>
       <td><a href="/search/*<?=$value->page_id;?>#comment-post_<?=$value->coment_id;?>"><?=$value->short_text;?></a></td>
    </tr>
    <?
  }
  exit;
}


$l = getSQLArrayO(" SELECT my_reminderhtml.*,  SUBSTRING(my_comments.text, 1, 100) AS short_text, my_users.name, my_news.name_company FROM my_reminderhtml JOIN my_comments ON my_reminderhtml.coment_id = my_comments.id JOIN my_users ON my_reminderhtml.user_id = my_users.id JOIN my_news ON my_reminderhtml.page_id = my_news.id WHERE my_comments.visible = 1 AND my_users.user_id = {$CFG->USER->USER_DIRECTOR_ID} ORDER BY my_reminderhtml.id DESC LIMIT 10 OFFSET 0; ");
?>


<br clear="all">

<div class="row tab">
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/">Самоначисления</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/money/">Касса</a></h1>
    </div>
    <div class="col-md-3">
        <h1 class="active"><a href="/profile/company/<?=$CFG->USER->USER_ID?>/other/">Дополнительно</a></h1>
    </div>
</div>

<div class="content">
  <h2 style="background:#fff">Те кто установил маркер</h2>
  <div class="white" style="padding:0 25px; ">
    <article class="vacancies_body row">

    <table class="users sticker">
     <tbody>
       <tr>
         <th><strong>№ записи</strong></th>
         <th><strong>Дата</strong></th>
         <th><strong>Юзер</strong></th>
         <th><strong>Компания</strong></th>
         <th><strong>Текст заметка</strong></th>
       </tr>

      <? if($l) {
        foreach ($l as $key => $value)
        {
          ?>
          <tr>
             <td><a href="/search/*<?=$value->page_id;?>#comment-post_<?=$value->coment_id;?>"><strong>*<?=$value->page_id;?></strong></a></td>
             <td><a href="/search/*<?=$value->page_id;?>#comment-post_<?=$value->coment_id;?>"><?=dateSQL2TEXT($value->cdate, "DD.MM.YY hh:mm");?></a></td>
             <td><a href="/profile/view/<?=$value->user_id;?>"><?=$value->name;?></a></td>
             <td><a href="/search/*<?=$value->page_id;?>#comment-post_<?=$value->coment_id;?>"><strong><?=$value->name_company;?></strong></a></td>
             <td><a href="/search/*<?=$value->page_id;?>#comment-post_<?=$value->coment_id;?>"><?=$value->short_text;?></a></td>
          </tr>
          <?
        }
        ?>

      <? } ?>

     </tbody>
    </table>

    <div class="col-md-12 more" style="border-top:solid 1px #ccc; padding-top:20px; margin-bottom:50px;">
     <div class="load_sticker" style="padding:7px 20px;">Загрузить еще</div>
     <div class="static sticker">Загружено  <span class="rebut_sticker">10</span></div>
    </div>

    </article>

    <br><br>
  </div>
</div>


<style>
.load_sticker {
    display: inline-block;
    margin: 0 auto;
    text-align: center;
    background: #F8403E;
    padding: 7px 20px;
    color: #FFF;
}
</style>

<script>
$(document).ready(function()
{
  var num = 10;
  $('.load_sticker').on('click', function(e)
  {
    $.ajax
    ({
      url: "/profile/company/<?=$CFG->USER->USER_DIRECTOR_ID;?>/sticker/",
      type: "POST",
      data: {"num": num, "type": "select" },
      cache: true,
      beforeSend: function()
      {
        $('#myModalBox').modal({backdrop: 'static', keyboard: false});

        $(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
      },
      success: function(response)
      {
        $(document).ready(function(){
          $("#myModalBox").modal('hide');
        });

        $('table.sticker').append(response);

        num = num  + 10;

        $('.static.sticker > .rebut_sticker').html(num);

        $("html, body").stop().animate({scrollTop: $('.SPDOWN').offset().top - 0 + 'px'}, 500);

      }

    });
  });
});
</script>
