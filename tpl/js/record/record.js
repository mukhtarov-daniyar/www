/* Секундомер */
function trim(string)
{
  return string.replace (/\s+/g, " ").replace(/(^\s*)|(\s*)$/g, '');
}

var init=0;
var startDate;
var clocktimer;

function clearFields()
{
 init = 0;
 clearTimeout(clocktimer);
}

function startTIME()
{
 var thisDate = new Date();
 var t = thisDate.getTime() - startDate.getTime();
 var ms = t%1000; t-=ms; ms=Math.floor(ms/10);
 t = Math.floor (t/1000);
 var s = t%60; t-=s;
 t = Math.floor (t/60);
 var m = t%60; t-=m;
 t = Math.floor (t/60);
 var h = t%60;
 if (h<10) h='0'+h;
 if (m<10) m='0'+m;
 if (s<10) s='0'+s;
 if (ms<10) ms='0'+ms;
 if (init==1)  $('.audio_mask .timer').html(h + ':' + m + ':' + s + '.' + ms);
 clocktimer = setTimeout("startTIME()",10);
}

function findTIME()
{
 if (init==0) {
  startDate = new Date();
  startTIME();
  init=1;
 }
 else
 {  $('.audio_mask .timer').html('00:00:00:00');
    clearFields();
 }
}
/* Секундомер END */


/* запись с микрофона END */
function restore()
{
  Fr.voice.stop();
}



$(document).ready(function()
{
  //Скпываем блок #  Запись с микрофона  #
  $("#audio_int").hide();

	$(document).on("click", "#rec_wav", function()
	{
		Fr.voice.record($("#live").is(":checked"), function()
		{
      findTIME();
      $("#audio_int").show();
      $("audio#audio").hide();
      $("#rec_wav").hide();
      $("#record-save").hide();

      $(".audio_mask").show();
      $("#stop_wav").show();
		});
	});


	$(document).on("click", "#stop_wav", function()
	{
    findTIME();

    $("audio#audio").show();
    $(".audio_mask").hide();
    $("#record-save").show();
    $("#stop_wav").hide();
    $("#rec_wav").show();

	  Fr.voice.export(function(url)
	  {
		   $("#audio").attr("src", url);
    }, "URL");

    Fr.voice.export(function(blob){
      var data = new FormData();
      data.append('file', blob);
      update_file_rec(data);
    }, "blob");



    Fr.voice.export(function(blob)
    {
      console.log(blob);
    }, "blob");


	  restore();
	});

  $(document).on("click", "#record-save", function(e)
	{
     var id = $(this).attr('data-text');
     response = $.parseJSON(id);

     $('input[name=attach_files_music]').val($('input[name=attach_files_music]').val() + ',' + response.id);
		 $('.input-form__music').append('<div class="add_file"><a href="' + response.id + '" target="_blank" class="cancel-attachment-music">Голосовое сообщение</a></div>');
     $("#audio_int").hide();
     e.preventDefault();
	});

  $(document).on("click", "#play:not(.disabled)", function(){

	   $("#audio").show();

    Fr.voice.export(function(url){
      $("#audio").attr("src", url);
      $("#audio")[0].play();
    }, "URL");
    restore();
  });

  $(document).on("click", "#download:not(.disabled)", function(){
    Fr.voice.export(function(url){
      $("<a href='"+url+"' download='MyRecording.wav'></a>")[0].click();
    }, "URL");
    restore();
  });

  $(document).on("click", "#base64:not(.disabled)", function()
  {
    Fr.voice.export(function(url)
    {
      console.log("Here is the base64 URL : " + url);
    }, "base64");
    restore();
  });


  $(document).on("click", "#mp3:not(.disabled)", function()
  {
    alert("The conversion to MP3 will take some time (even 10 minutes), so please wait....");
    Fr.voice.export(function(url){
      console.log("Here is the MP3 URL : " + url);
      alert("Check the web console for the URL");

      $("<a href='"+ url +"' target='_blank'></a>")[0].click();
    }, "mp3");
    restore();
  });


$(document).on("click","#save:not(.disabled)",function()
{
	Fr.voice.export(function(blob)
	{
		var formData=new FormData();
		formData.append('file',blob);

		console.log(formData);


	},"blob");

	restore();});
});
