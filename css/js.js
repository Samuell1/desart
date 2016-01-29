function addText(elname, strFore, strAft, formname) {
   if (formname == undefined) formname = 'inputform';
   if (elname == undefined) elname = 'message';
   element = document.forms[formname].elements[elname];
   element.focus();
   // for IE 
   if (document.selection) {
	   var oRange = document.selection.createRange();
	   var numLen = oRange.text.length;
	   oRange.text = strFore + oRange.text + strAft;
	   return false;
   // for FF and Opera
   } else if (element.setSelectionRange) {
      var selStart = element.selectionStart, selEnd = element.selectionEnd;
			var oldScrollTop = element.scrollTop;
      element.value = element.value.substring(0, selStart) + strFore + element.value.substring(selStart, selEnd) + strAft + element.value.substring(selEnd);
      element.setSelectionRange(selStart + strFore.length, selEnd + strFore.length);
			element.scrollTop = oldScrollTop;      
      element.focus();
   } else {
			var oldScrollTop = element.scrollTop;
      element.value += strFore + strAft;
			element.scrollTop = oldScrollTop;      
      element.focus();
	}
}

$(function(){

$('.prettyprint').each(function(i, block) {
    hljs.highlightBlock(block);
});

	
        setInterval(function(){
            var profileuser = $(".chatboxmessages"); 
            if (profileuser) {
                var user = profileuser.attr("data-target");
                $("div.chatboxmessages[data-target='"+user+"']").load("/inc/func/messagesbox.php?userid="+user);
            }  
        }, 4000);



    $('.prettyprint').perfectScrollbar({
      wheelSpeed: 2,
      wheelPropagation: true,
      minScrollbarLength: 10,
      includePadding: true
  });

    $('.boxforumscroll').perfectScrollbar({
    	wheelSpeed: 2,
  		wheelPropagation: false,
  		minScrollbarLength: 20
	});

    $('.chatboxmessages').perfectScrollbar({
    	wheelSpeed: 2,
  		wheelPropagation: false,
  		minScrollbarLength: 20
	});
    $('.userslist').perfectScrollbar({
    	wheelSpeed: 2,
  		wheelPropagation: false,
  		minScrollbarLength: 20
	});

    $('body').on('click', function (e) {
    $('[data-toggle="popover"]').each(function () {
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
    });
    
	$('.articleview img').addClass("img-responsive");
    $('.articleview iframe').addClass("embed-responsive-item").attr("width","100%");

	$('.tttoggle').tooltip();
	$('.userlistid,.userlistidnone').tooltip();
	$('.bbcody a').tooltip();
	$('.ratestar').tooltip();
	$('.popovertoggle').popover({ html : true});
    $('#subormodal').modal('show');

    $('#hidesubheader').click(function () {
        if($(".subheaderhide").is(":hidden")){
            $(".subheaderhide").slideDown();
            $("#hidesubheader").text("Skryť panel najnovšia aktivita");
        }else{
            $(".subheaderhide").slideUp();
            $("#hidesubheader").text("Zobraziť panel najnovšia aktivita");
        }
    });

	$('select[name=img]').change(function(event) {
		var textvimg = $('select[name=img]').find(":selected").text();
		if (textvimg != "") {
		$("#imgviewarticle").attr( "src", "/file/articles/"+textvimg);
		}
	});
    $('#loading-example-btn').click(function () {
        var btn = $(this);
        btn.button('loading');
        setTimeout(function() {btn.button('reset');}, 6000);
    });
    
    $('#showcommentarea').click(function () {
	   $('.komentboxarea').slideDown();
	   $('#showcommentarea').hide();
    });
	
	$('#chatform').submit(function(){
    $.ajax({
        type: "POST",
        url: "/inc/func/chatpost.php",
        data: $("#chatform").serialize(),
        success: function(data){
		   $('.chattext').val('');
			var useriddata = $('#touserid').val();
			$(".chatboxmessages").fadeIn("100").load("/inc/func/messagesbox.php?userid="+useriddata);
        }
		, error: function() {
                 alert("Vyskytla sa neznama chyba pri spracovavani!");   
            }
    });
    return false;
	});
	$("#aktivnetemymyshow").click(function () {
        $(".boxforumchangeajaxhide").hide();
		$(".boxforumchangeajax").show().fadeIn("100").load("/inc/func/aktivnetemymy.php");
	});
	$("#oblubenetemy").click(function () {
        $(".boxforumchangeajaxhide").hide();
		$(".boxforumchangeajax").show().fadeIn("100").load("/inc/func/favoritetopics.php");
	});
	$("#mojetemyshow").click(function () {
        $(".boxforumchangeajaxhide").hide();
		$(".boxforumchangeajax").show().fadeIn("100").load("/inc/func/usertopics.php");
	});
	$("#aktivnetemyshow").click(function () {
        $(".boxforumchangeajaxhide").show();
		$(".boxforumchangeajax").hide();
	});
    

	$("#searchuserlist").on('keyup', function () {
		var value = $("#searchchatuser").val();
		$(".userslist").fadeIn("100").load("/inc/func/searchuserlist.php?s="+value);
	});
    
    
	
	$('#showchat,#showchat2').click(function () {
		$('#userchatmodal').modal("show");
	});
	
	$('.userslist').on("click",".userlistid", function(){
      var element = $(this);
      var userprofil = element.attr("id");
      $(".chatboxmessagesbor").show();
      $(".chatboxmessages").fadeIn("100").load("/inc/func/messagesbox.php?userid="+userprofil);
	  $(".chatboxmessages").attr("data-target", userprofil);
      $('#touserid').val(userprofil);
      $(".formchatbox").show();
	  $(".gtipred[data-target='"+userprofil+"']").hide();
	  $(".userlistid").removeClass("active");
      element.addClass("active");
	});
	
	$('.profillink').click(function(){
      var eid = $(this).attr('data-target');
      $.ajax({
        type : 'get',
        url : '/uzivatel/zobrazitprofil.php', 
        data :  'uid='+eid,
		success : function(r)
           { 
			  $('#profilmodal').modal("show");
              $('.userprofile').show().html(r);
           }
		});
	});
	
	$('.sreply').click(function () {
		var idcom = $(this).attr('data-comid');
		$(".kom"+idcom).slideDown().fadeIn();
	});
	
	$("#select1").change(function(){
		if ($(this).val() == "6" ) {
			$("#hide1modf").slideDown();
		} else {
			$("#hide1modf").slideUp();
		}
	});
	
	$('.ratestar').click(function () {
		var idarticlecheck = $(this).attr("idarticle");
		var typeratecheck = $(this).attr("typerate");
		var typepmcheck = $(this).attr("typepm");
		$.ajax({
            type: "POST",
            data: 'idarticle='+idarticlecheck+'&typerate='+typeratecheck+'&typepm='+typepmcheck,
            url: "/inc/func/ratevote.php",
			cache: false,
			success: function(data){$(".rating").html("<div style='font-size:11px;'>Ďakujeme za hodnotenie.</div>").show("fast");}
		});
	});
	
});