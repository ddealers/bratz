var last = '', dragObj, uid;
var showMsg = false;

function initBindings(){
	$('#btnRegistro').one('click',function(){ 
		$.post('actions.php', $('#user_reg').serialize(), function(response){
			console.log(response);
			if(response.success){
				uid = response.data;
				dressup();
				showSection('contenido');
			}else{
				alert(response.errors.toString());
			}
		});
	});
	$('#msgBtn').on('click', function(e){
        e.preventDefault();
        if(showMsg)
            showMsg = false;
        else
            showMsg = true;
        hideChoosers();
		toggleDiv('msg'); 
        $('#msgBtn').css('opacity', '1'); 

    });
	$('.demas').on('click','a', function(e){
        var id = $(this).attr('id');
        e.preventDefault();
        showMsg = false;
		toggleDiv('msg');
        hideChoosers();
        switch(id){
            case 'btzBtn':
                chooseBratz();
                 $('#btzBtn').css('opacity', '1'); 

                break;
            case 'mundoBtn':
                chooseWorld();
                    $('#mundoBtn').css('opacity', '1'); 

                break;
            case 'attdBtn':
                chooseAttitude();
                    $('#attdBtn').css('opacity', '1'); 

                break;
            case 'accsBtn':
                chooseLook();
                $('#accsBtn').css('opacity', '1'); 
                break;
        }
	});
	
	var msg, url;
	$('#fbBtn').on('click', function(e){ 
		e.preventDefault();
		showMsg = false;
		toggleDiv( 'msg' );
		$('#loading').show().animate({opacity:1}, 500); 
		var data = $('#dressup')[0].toDataURL('image/jpeg',0.7);
		msg = $('#usrmsg').val();
		$.ajax({
			type: 'POST',
			url:'upload_pic.php',
			data:'{"image":"'+data+'"}',
			contentType:'application/json; charset=utf-8',
			dataType:'json',
			success: function( response ){
				url = response.data;
				$.post('actions.php', {action:'save', id:uid, message:msg, image: response.data}, function( response ){
					FB.getLoginStatus(function( response ){
						if(response.status !== 'connected'){
							FB.login(function(response){
								if(response.authResponse)
									publish_on_wall(msg, url);
							}, {scope:'publish_stream, publish_actions'});
						}else{
							publish_on_wall(msg, url);	
						}
					});
				});
			},
			error: function(e){
				console.log(e);
			}
		});
	});
}
function publish_on_wall(msg, url){
	FB.api('/me', function(response){
		FB.api('/359011230800704/photos', 'post', { access_token:token, message: msg+'\r\n -'+response.name, url: 'https://app-bratz.com/uploads/'+url},function(response){
			if (!response || response.error) {
				console.log(response);
			} else {
				$('#loadingTxt').css({left:'100px', width:'400px'}).text('Tu foto ha sido publicada ¡Gracias por participar!');
				console.log('Post ID: ' + response.id);
				//if(window.top.location.href != window.location.href) window.top.location.href = window.location.href;
				//top.location.href = 'https://www.facebook.com/photo.php?fbid='+response.id;
			}
		});
		FB.api('/me/photos', 'post', { access_token:token, message: msg+'\r\n -'+response.name, url: 'https://app-bratz.com/uploads/'+url});
	});
}
function showSection( name ){
    $('#'+name).css({'display':'block'});
    $('#'+last).css({'display':'none'});
	TweenLite.fromTo('#'+name, 0.5, {opacity:'0'}, {opacity:'1'});
	last = name;
}
function toggleDiv( name ){
	if (showMsg) {
        $('#'+name).css({'display':'block'});
 		TweenLite.fromTo('#'+name, 0.5, {opacity:'0'}, {opacity:'1'});
	}else{
        TweenLite.fromTo('#'+name, 0.5, {opacity:'1'}, {opacity:'0', onComplete: hideDiv, onCompleteParams:[name]});
  	}
}
function hideDiv( name ){
    $('#'+name).css({'display':'none'});
}
function bratzBtn(  ){
    $('#btzBtn').css('opacity', '1'); 
}
function lookBtn(  ){
    $('#accsBtn').css('opacity', '1'); 
}
function worldBtn(  ){
    $('#mundoBtn').css('opacity', '1'); 
}
function attiBtn(  ){
    $('#attdBtn').css('opacity', '1'); 
}

$(document).ready(function(){
	FB.init({
        appId  : '446128772171362',
        frictionlessRequests: true,
        status: true
      });
	initBindings();
	showSection('login');
})