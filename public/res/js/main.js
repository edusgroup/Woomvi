
function rememberMeEmailBtnClick(pEvent){
    var typeAction = jQuery(pEvent.target).attr('data-type');
    var email = jQuery('#remember-me-email input:first').val();
    jQuery.post('/ajax/remember-me-email/',
        {email: email, type: typeAction},
        function(data){
            if ( data == 'ok' ) {
                alert('Данные сохранились. Ожидайте письма');
            }else{
                alert('Ошибка №1. Что-то пошло не так.');
            }
        }
    ).fail(function() {
            alert('Ошибка №2. Что-то пошло не так.');
        })
    return false;
    // func. rememberMeEmailBtnClick
}

jQuery(document).ready(function() {
    jQuery('#top_menu a').each(function(ind, obj){
        var rgx = /[^/]+$/i;
        var href = document.location.href.replace(rgx ,"");
        if ( href === obj.href ){
            jQuery(obj).addClass('active');
        }
    });

    jQuery('#remember-me-email .btn:first').click(rememberMeEmailBtnClick);


    resizeWidth();
    //jQuery(window).resize(resizeWidth);
});

function resizeWidth(){

}

var logic = (function(){

    var shp_name = '';
    var shp_email = '';

    var fieldList = {};

    var $form;

    function payClick(pObjBtn){
        var $box = jQuery(pObjBtn).parents('form:first');

        //var $box = jQuery(pObjBtn).parents('#payBox');
        shp_name = $box.find('input[name="shp_name"]').val();
        shp_email = $box.find('input[name="shp_email"]').val();

        var dataSend = {};
        $box.find('input').each(function(pNum, pObj){
            var prename = pObj.name.trim().substr(0,4).toLowerCase();
            if ( prename != 'shp_'){
                return;
            }
            pObj.value = pObj.value.replace(/[:=&]/, '');
            var name = jQuery(pObj).attr('name');
            dataSend[name] = pObj.value;
            fieldList[name] = pObj.value;
        });

        $box.find('textarea').each(function(pNum, pObj){
            var prename = pObj.name.trim().substr(0,4).toLowerCase();
            if ( prename != 'shp_'){
                return;
            }
            pObj.value = pObj.value.replace(/[:=&]/, '');
            var name = jQuery(pObj).attr('name');
            dataSend[name] = pObj.value;
            fieldList[name] = pObj.value;
        });

        dataSend['sum'] = $form.find('input[name="sum"]').val();

        jQuery.post('/ajax/robokassa/', dataSend,
            function(roboKassaResult){
                $form.find('input[name="SignatureValue"]:first').val(roboKassaResult.crc);
                $form.find('input[name="OutSum"]:first').val(roboKassaResult.sum);
                $form.find('input[name="MrchLogin"]:first').val(roboKassaResult.login);
                $form.find('input[name="InvId"]:first').val(roboKassaResult.invId);
                $form.attr('action', roboKassaResult.url);


                /*$form.find('input[name="shp_name"]').val(shp_name);
                 $form.find('input[name="shp_email"]').val(shp_email);*/

                for( var name in fieldList ){
                    $form.append('<input type="hidden" name="'+name+'" value="'+fieldList[name]+'"/>');
                }


                $form.submit();
            }, 'json');
        return false;
        // func. payClick
    }

    function init(){
        $form = jQuery('#payForm');
        // func. init
    }

    jQuery(document).ready(function() {
        init();
    });

    return{
        payClick: payClick
    }
})();

function UserCabinetData(){
	this.userId = null;
}

UserCabinetData.prototype.setUserId = function(userId) {
	this.userId = userId;
}

UserCabinetData.prototype.getUserId = function(userId) {
	return this.userId;
}

var userCabinet = (function(){
    var userId = null;
    var $form = null;
    var requiredSumm = 0;

    function payBtnClick(){

        var dataSend = {};
        dataSend['shp_userid'] = userId;
        dataSend['sum'] = requiredSumm;

        jQuery.post('/ajax/robokassa/', dataSend,
            function(roboKassaResult){
                $form.find('input[name="SignatureValue"]:first').val(roboKassaResult.crc);
                $form.find('input[name="OutSum"]:first').val(roboKassaResult.sum);
                $form.find('input[name="MrchLogin"]:first').val(roboKassaResult.login);
                $form.find('input[name="InvId"]:first').val(roboKassaResult.invId);
                $form.attr('action', roboKassaResult.url);

                $form.find('input[name="shp_userid"]:first').val(userId);

                $form.submit();
            }, 'json');
        return false;
        // func. payBtnClick
    }

    function init(pUserId, pRequiredSumm){
        userId = pUserId;
        requiredSumm = pRequiredSumm;
        $form = jQuery('#payForm');

        jQuery('#payBtn').click(payBtnClick);
        // func. init
    }

    return{
        init: init
    }
})();



/*
function resizeWidth(){
    var mainContentWidth = jQuery('#maincontent').width();

    jQuery('.block-item-list').each(function(pNum, pBlockItemObj){

        var width = 0;
        var num = 0;
        var numMax = 0;
        var tw = 0;
        jQuery(pBlockItemObj).find('.item').each(function(pIteNum, pItemObj){

            tw = jQuery(pItemObj).outerWidth(true);
            //console.log(width + tw);
            if ( width + tw >= mainContentWidth ){
                width = 0;
                numMax = num;
                num = 0;
                return;
            }

            num++;
            width += tw;
        });

        //alert(tw * num);

        jQuery(pBlockItemObj).width(tw * numMax);


        //jQuery(pObj).with
    });

    // func. resizeWidth
}
    */
$(document).ready(function() {
    $("#content").find("[id^='tab']").hide(); // Hide all content
    $("#tabs li:first").attr("id","current"); // Activate the first tab
    $("#content #tab1").fadeIn(); // Show first tab's content

    $('#tabs a').click(function(e) {
        e.preventDefault();
        if ($(this).closest("li").attr("id") == "current"){ //detection for current tab
            return;
        }
        else{
            $("#content").find("[id^='tab']").hide(); // Hide all content
            $("#tabs li").attr("id",""); //Reset id's
            $(this).parent().attr("id","current"); // Activate this
            $('#' + $(this).attr('name')).fadeIn(); // Show content for the current tab
        }
    });

});

if ( userId ){
    jQuery('#top-panel .cabinet .dropdown').remove();
    jQuery('#top-panel .cabinet>a').attr('href', '/cabinet/')
}


var notifyBoxLogic = (function(){

    var $notifyBox = null;

    function show(){
        $notifyBox.show();

        var posY= ( jQuery(window.top).height() -  $notifyBox.find('.inner-wrap:first').height() ) / 2;
        $notifyBox.find('.inner-wrap:first').css({'margin-top': posY});
        return false;
        // func. show
    }

    function closeBtnClick(){
        $notifyBox.hide();
        return false;
        // func. closeBtnClick
    }

    function init(){
        $notifyBox = jQuery('#notify-box');

        $notifyBox.find('.inner-wrap:first').append('<a href="#" class="close"></a>');
        $notifyBox.find('.close:first').click(closeBtnClick);

        // func. init
    }

    return{
        init: init,
        show: show
    }
})();


var shopBuyBox = (function(){
    
    function show(){
        jQuery('#buy-box').show();

        var posY= ( jQuery(window.top).height() -  jQuery('#buy-box .inner-wrap:first').height() ) / 2;
        jQuery('#buy-box .inner-wrap:first').css({'margin-top': posY});
        return false;
        // func. show
    }

    function closeBtnClick(){
        jQuery('#buy-box').hide();

        return false;
        // func. closeBtnClick
    }

    function init(pSum, pTitle){
        jQuery('#buy-box h2:first').html(pTitle);
        jQuery('#buy-box input[name="sum"]').val(pSum)

        jQuery('#buy-box .inner-wrap').append('<a href="#" class="close"></a>');

        jQuery('#buy-box .close').click(closeBtnClick);

        // func. init
    }

    return{
        init: init,
        show: show
    }
})();


jQuery('#loginForm .btn').click(function(){
    var data = jQuery('#loginForm').serialize();
    jQuery.ajax({
        url: '/ajax/login/',
        data: data,
        dataType: 'json',
        type: 'POST',
        success: function(pResult){
            if ( !pResult.status || pResult.status != 'ok'){
                alert('Логин/пароль неверный');
                return;
            }

            window.location = '/cabinet/';
            // func.
        }
        });
    return false;
});





jQuery('#showMainContent .btn:first').click(function(pEvent){
   jQuery('#main-index-block').css({height: 'auto'});
    jQuery('#main-index-block').find('.gradient-line:first').hide();
   jQuery(pEvent.target).hide();
   return false;
});

var $mp3recordList = jQuery('.mp3record');
if ( $mp3recordList.length > 0 ) {

    var iOS = ( navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false );
    if (iOS) {
        jQuery(pObj).html('Тут должен быть плеер. Простите нас. Мы пока еще не создали код,<br/>чтобы звук поддерживался на iOS.<br/>' +
        'На обычном ноутбуке или компьютере всё будет работать.').addClass('warning-box');
    } else if (!swfobject.hasFlashPlayerVersion("9.0.115")) {
        jQuery(pObj).html('Тут должен быть плеер. Но скорей всего у вас отключён или не установлен Flash Player. Скачайте и установите его сайта ' +
            ' <a href="http://get.adobe.com/ru/flashplayer/" rel="nofollow">http://get.adobe.com/ru/flashplayer/</a> ')
            .addClass('warning-box');
    } else {
        $mp3recordList.each(function (pNum, pObj) {
            var id = 'mp3-n' + pNum;
            var url = jQuery(pObj).attr('data-url');
            jQuery(pObj).html('<div id="' + id + '"></div>');
            swfobject.embedSWF("/res/plugin/player/uppod-audio2.swf",
                id, "100%", "36", "8",
                null,
                {st: '/res/plugin/player/style.txt', file: url},
                { allowScriptAccess: "always", wmode: "opaque", allowFullScreen: 'false' },
                { id: "playerObjId", name: "playerObjId" });
        });
    }
}


function UserOffice(){
    var that = this;
    this.$cabinetShowBtn = jQuery('#top-panel .cabinet:first');
	
	
	this.initEvent();
}

UserOffice.prototype.initEvent = function() {
	var that = this;

	this.$cabinetShowBtn.find('a:first').click(function(event){
		that.onShowRegistrationPanel(event);
	});
	
	jQuery(document).click(function(event){
		that.windowClick(event);
	});
}

UserOffice.prototype.windowClick = function(event) {
	if (jQuery(event.target).parents('.cabinet:first').length != 0){
		console.log('sdf3333');
		return false;
	}
	this.$cabinetShowBtn.toggleClass('show', false);
}

UserOffice.prototype.onShowRegistrationPanel = function(event) {
    jQuery(this.$cabinetShowBtn).toggleClass('show');
	return false;
}

var userOffice = new UserOffice();

