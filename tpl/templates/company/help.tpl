<?
if($_POST['type'] == 'update')
{
  $text = $CFG->DB->escape($_POST['text']);
  $CFG->DB->query("UPDATE my_company SET big_text = '{$text}'  WHERE id = '{$CFG->USER->USER_DIRECTOR_ID}' "  );
  exit;
}
?>


<style>
.modal-body .bootstrap-select { width:220px !important;}

</style>


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
  <h2 style="background:#fff">Меморандум</h2>
  <div class="white" style="padding:0 25px; ">
    <article class="vacancies_body row">


     <? $respon = getSQLRowO(" SELECT  big_text FROM my_company WHERE id = '{$CFG->USER->USER_DIRECTOR_ID}' "); ?>

      <!-- Place the first <script> tag in your HTML's <head> -->
      <script src="https://cdn.tiny.cloud/1/u1nlf07sv5343b27cuq9m3e4xd9tsj8u7oh9w88r1ekz5tfu/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

      <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
      <script>
        tinymce.init({
          selector: '.edit_textarea',
          plugins: 'anchor ',
          toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
          tinycomments_mode: 'embedded',
          tinycomments_author: 'Author name',
          language: 'ru',
          mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
          ],
          ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
        });
      </script>

      <textarea class="edit_textarea"><?=$respon->big_text;?></textarea>

      <br>
      <div class="row">
        <div class="col-xs-12">
          <button type="button" class="btn btn-danger pull-right save_text p-4">Сохранить</button>
        </div>
      </div>

    </article>

    <br><br>
  </div>
</div>



<script>
$(document).on('click','.save_text',function(e)
{
  var content = tinymce.activeEditor.getContent();
  $('#myModalBox').modal({backdrop: 'static', keyboard: false});
  $.ajax
      ({
        url: "/profile/company/<?=$CFG->USER->USER_DIRECTOR_ID;?>/help/",
        type: "POST",
        data: {"type": "update", "text": content},
        cache: true,
          beforeSend: function()
          {
            $(".modal-body").html('<h4 class="modal-title"><center>Сохраняем текст, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
          },
          success: function(response)
          {
            $(".modal-body").html('<div class="modal-header"><h4 class="modal-title text-center">Текст сохранен, обновляем страницу...</h4></div>');
            setTimeout(function() {window.location.reload();}, 1000);
          }
      });

	e.preventDefault();
});
</script>
