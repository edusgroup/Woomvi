window.wumviLogic = (function(){

    var wumviEvent = null;
    var pingIntervalId;

    var extVerstion = '';

    function loadScript(url, callback) {

        var script = document.createElement("script")
        script.type = "text/javascript";

        if (script.readyState) { //IE
            script.onreadystatechange = function () {
                if (script.readyState == "loaded" || script.readyState == "complete") {
                    script.onreadystatechange = null;
                    callback();
                }
            };
        } else { //Others
            script.onload = function () {
                callback();
            };
        }

        script.src = url;
        document.getElementsByTagName("head")[0].appendChild(script);
        // func. loadScript
    }

    function onIconBtnClick(){
        jQuery('#wumviIFrame').toggle();
        //sendDataToContentPage('show');
        // func. onIconBtnClick
    }

    function hideAllButtons(){
        jQuery('#wumviButton').hide();
        jQuery('#wumviIFrame').hide();
        // func. hideAllButtons
    }

    //var pingId = null;
    var lastStatus = '';

    function ping(){
        if ( lastStatus == pingData.status ){
            return;
        }

        switch (pingData.status){
            case 'show':
                sendDataToContentPage({type:'showall', param: pingData.param});
                break;
            case 'hide':
                hideAllButtons();
                break;
        } // switch

        lastStatus = pingData.status;
        // func. ping
    }

    function onCheckLearningTimer(){
        jQuery.getScript('https://wumvi.com/logic/wordstatus/ping/', ping);
        // func.
    }

    var pingTimeOut = 5 * 60 * 1000;

    function jCookieInit(){
        jQuery(document.body).append( '<style>#wumviButton .wum-close{ right: -110px;  } #wumviButton .wum-clock{ left: -110px;  } #wumviButton .wum-actionBox .wum-item{ line-height: 0; width: 90px; background: white; height: auto; font-size: 0; text-align: center;position: absolute; border: 2px solid rgba(0, 0, 0, .5); padding: 5px; border-radius: 5px;top: 30px;}</style>');

        jQuery(document.body).append(
                '<div id="wumviButton" onclick="return wumviLogic.onIconBtnClick(this);">' +
                '<div class="wum-actionBox">'+
                    '<div onclick="return wumviLogic.onAfterBtn(this, event);" title="Remind in 20 minutes" class="wum-item wum-clock" ><img src="https://wumvi.com/res/img/ext/clock-1.png"/></div>'+
                    '<div onclick="return wumviLogic.onNextBtn(this, event);" title="Next card" class="wum-item wum-close" ><img src="https://wumvi.com/res/img/ext/close-1.png"/></div>'+
                '</div>' +
                    '</div><style> #wumviButton{ background: url(\'https://wumvi.com/res/img/ext/button.png\') no-repeat; display: none;  z-index: 99999999999; background-size: 100% auto; width: 90px; height: 90px; cursor: pointer; position: fixed; left: 50%; bottom: 40px; margin-left: -40px;  transition:all 0.1s; } #wumviButton:hover{ /*width: 135px; height: 135px; margin-left: -67.5px; bottom: 20px; */background-image: url(\'https://wumvi.com/res/img/ext/button-h.png\');}</style>')

        jQuery(document.body).append('<iframe id="wumviIFrame" src=""></iframe><style>#wumviIFrame{ 	position: fixed; display: none; z-index: 99999999999;	width: 100%; height: 400px;	left: 0; top: 50%; margin-top: -250px; 	border: 0; }</style>');

        onCheckLearningTimer();
        pingIntervalId = setInterval(onCheckLearningTimer, pingTimeOut);

        jQuery(window).focus(function() {
            pingIntervalId = setInterval(onCheckLearningTimer, pingTimeOut);
        });

        jQuery(window).blur(function() {
            clearInterval(pingIntervalId);
        });
        // func. jCookieInit
    }

    var eventBox = 'wumviMsgId';

    function onDomEventBox(){
        var eventData = document.getElementById(eventBox).innerText;
        var data = JSON.parse(eventData);

        switch(data.type){
            case 'hide':
                hideAllButtons();
                break;
            case 'showall':
                jQuery('#wumviButton').show();
                jQuery('#wumviIFrame').attr('src', 'https://wumvi.com/logic/wordstatus/iframe/?'+data.param);
                break;
            case 'showall-now':
                jQuery.getScript('https://wumvi.com/logic/wordstatus/ajax/?type=showall-now', function(){
                    lastStatus = '';
                    jQuery.getScript('https://wumvi.com/logic/wordstatus/ping/?v='+extVerstion, ping);
                });
                break;
        } // switch

        // func. onDomEventBox
    }

    function jQueryInit(){

        wumviEvent = document.createEvent('Event');
        wumviEvent.initEvent('wumviEvent', true, true);

        document.getElementById(eventBox).addEventListener('wumviContEvent', onDomEventBox);

        /*if ( jQuery.cookie ){
            jCookieInit();
            return;
        }

        loadScript("https://yastatic.net/jquery/cookie/1.0/jquery.cookie.min.js", jCookieInit);*/
        jCookieInit();
        // func. jQueryInit
    }

    function sendDataToContentPage(pData){
        var hiddenDiv = document.getElementById(eventBox);
        hiddenDiv.innerText = JSON.stringify(pData);;
        hiddenDiv.dispatchEvent(wumviEvent);
        // func. sendDataToContentPage
    }

    function onNextBtn(pObj, pEvent){
        jQuery.getScript('https://wumvi.com/logic/wordstatus/ajax/?type=next', function(){
            sendDataToContentPage({type:'hideall'});
        });

        pEvent.stopPropagation();
        return false;
        // func. onNextBtn
    }

    function onAfterBtn(pObj, pEvent){
        jQuery.getScript('https://wumvi.com/logic/wordstatus/ajax/?type=time', function(){
            sendDataToContentPage({type:'hideall'});
        });

        pEvent.stopPropagation();
        return false;
        // func. onNextBtn
    }

    function setExtVersion(pVerstion){
        extVerstion = pVerstion;
        // func. setExtVersion
    }

    function init(){
        if ( window.jQuery ){
            jQueryInit();
            return;
        }

        loadScript("https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js", jQueryInit);
        // func. init
    }

    init();

    return{
        onIconBtnClick: onIconBtnClick,
        onAfterBtn: onAfterBtn,
        onNextBtn: onNextBtn,
        hideAllButtons: hideAllButtons,
        setExtVersion: setExtVersion
       // init: init
    }
})();

