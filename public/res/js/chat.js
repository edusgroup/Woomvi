


var charlog = (function(){
    var $chatObj;
    var $msgBox;
    var msgLastId = null;
    var initOptions;

    /**
     * Add message to Message box
     * @param pObj type of {msg:"",date:"",nick:"",type:$typelist, id:34}. $typelist => ['teacher', 'question', 'user']
     */
    function addOneMsg(pObj){
        $msgBox.append(' <div class="msg-item '+pObj.type+'" id="msg-' + pObj.id+'" data-msgid="' + pObj.id+'" data-userid="'+pObj.userid+'"> \
            <div class="info"> \
            <div class="nick">'+pObj.nick+'</div> \
            <div class="date">'+pObj.date+'</div> \
            <div class="admin"><img src="http://wumvi.com/res/img/chat/ban.png" data-type="ban"/>\
            <img src="http://wumvi.com/res/img/chat/delete.png" data-type="rmmsg"/></div> \
            </div> \
            <div class="msg"> \
            '+pObj.msg+'\
            </div> \
            </div>');


        // ufnc. addOneMsg
    }


    function addMessages(pMessageList){
        for( var i in pMessageList ){
            addOneMsg(pMessageList[i]);
        } // for

        if ( pMessageList.length != 0 ) {
            msgLastId = pMessageList[i].id;
            $msgBox.animate({
                //scrollTop: jQuery('#msg-' + msgLastId).offset().top + 1000000
                scrollTop: jQuery('#charBox .msg-box')[0].scrollHeight + 100
            });
        }


        // func. addMessage
    }


    /**
     * Remove message from message box
     * @param pList is [msgId, msgId]
     */
    function removeMessage(pList){
        for(var i in pList){
            jQuery('#msg-'+pList[i]).remove();
        }
        // func. removeMessage
    }

    function updateActionCallback(pData){
        for( var i in pData.list){
            switch(pData.list[i].type){
                case 'msg':
                    addMessages(pData.list[i].obj);
                    break;
            }
        } // for

        jQuery('#waitbox').html('');


        setTimeout(updateAction, 1500);
        // func. updateActionCallback
    }

    function updateAction(){
        var data = {
            type: 'update',
            lastid: msgLastId
        }

        jQuery.ajax({
            url: '/logic/chat/',
            data: data,
            type: 'POST',
            dateType: 'json'
        }).done(updateActionCallback);
        // func. updateAction
    }

    function addMsgCallback(pData){
        if ( pData.status != 0){
            alert(pData.msg);
        }
        // func. addMsgCallback
    }

    function sendMsgClick(){
        var msg = jQuery('#msgInput').val().trim();
        if ( msg == '' ){
            alert('Введите сообщение');
            return false;
        }

        jQuery('#waitbox').html('<img src="http://wumvi.com/res/img/raznoe/ajax-loader.gif"/>');

        var isQuestion = jQuery('#itsQuestion').prop("checked")? 1 : 0


        var data = {
            type: 'addmsg',
            msg: msg,
            question:  isQuestion
        }

        jQuery.ajax({
            url: '/logic/chat/',
            data: data,
            type: 'POST',
            dateType: 'json'
        }).done(addMsgCallback);

        jQuery('#itsQuestion').prop("checked", false);
        jQuery('#msgInput').val('');
        return false;
        // func. sendMsgClick
    }

    function showRegFormBtnClick(){
        jQuery('#reg-box').show();
        jQuery('#charBox .singin-box').hide();
        return false;
        // func. showRegFormBtnClick
    }

    function singInCallback(pData){
        if ( pData.obj.status == 0 ){
            jQuery('#charBox .singin-box').hide();
            jQuery('#charBox .ctrl-box').show();
            return;
        }

        alert(pData.obj.msg);
        // func. singInCallback
    }

    function signInBtnClick(){
        var email = jQuery('#charBox .singin-box input[name="email"]').val().trim();
        if ( email == '' ){
            alert('Введите email');
            return;
        } // if

        var pwd = jQuery('#charBox .singin-box input[name="pwd"]').val().trim();
        if ( pwd == '' ){
            alert('Введите пароль');
            return;
        } // if

        var data = {
            type: 'singin',
            email: email,
            pwd: pwd
        }

        jQuery.ajax({
            url: '/logic/chat/reg/',
            data: data,
            type: 'POST',
            dateType: 'json'
        }).done(singInCallback);
        return false;
        // func. signInBtnClick
    }

    function regSendCallback(pData){
        if ( pData.obj.status == 0 ){
            alert('Пароль выслан на вашу почту');
            jQuery('#reg-box').hide();
            jQuery('#charBox .singin-box').show();
            return;
        }
        alert(pData.obj.msg);
        // func. regSendCallback
    }

    function regSendBtnClick(){
        var email = jQuery('#reg-box input[name="email"]').val().trim();
        if ( email == '' ){
            alert('Введите email');
            return;
        } // if

        var name = jQuery('#reg-box input[name="name"]').val().trim();
        if ( name == '' ){
            alert('Введите Имя');
            return;
        } // if

        var data = {
            type: 'reg',
            email: email,
            name: name
        }

        jQuery.ajax({
            url: '/logic/chat/reg/',
            data: data,
            type: 'POST',
            dateType: 'json'
        }).done(regSendCallback);
        return false;
        // func. regSendBtnClick
    }

    function backToSingInClick(){
        jQuery('#reg-box').hide();
        jQuery('#charBox .singin-box').show();
        return false;
        // func. backToSingInClick
    }

    function setUserAuthStatus(pUserStatus){
        if ( pUserStatus == 1 ) {
            jQuery('#charBox .singin-box').hide();
            jQuery('#charBox .ctrl-box').show();
            return;
        }

        jQuery('#charBox .singin-box').show();
        // func. setUserAuthStatu
    }

    function msgInputKeyPress(pEvent){
        var keycode = pEvent.keyCode;
        if ( keycode  == 13 ){
            sendMsgClick();
        }else
        if ( keycode == 10 ){
            //document.getElementById('msgInput').value += "\n\r";
        }

        // func. msgInputKeyPress
    }

    function closeChatClick(){
        $chatObj.hide();
        return false;
        // func. closeChatClick
    }

    function showChatClick(){
        $chatObj.show();
        return false;
        // func. showChatClick
    }

    function init(pInitOption){
        initOptions = pInitOption;
        $chatObj = jQuery('#charBox');

        $msgBox = $chatObj.find('.msg-box:first');


        updateAction();

        jQuery('#signInBtn').click(signInBtnClick);
        jQuery('#showRegFormBtn').click(showRegFormBtnClick);

        jQuery('#sendMsg').click(sendMsgClick);
        jQuery('#regSendBtn').click(regSendBtnClick);
        jQuery('#backToSingIn').click(backToSingInClick);

        jQuery('#reg-box input[name="email"]').val('');
        jQuery('#reg-box input[name="name"]').val('');

        jQuery('#msgInput').keypress(msgInputKeyPress);

        $chatObj.draggable({ handle: ".window-title" });

        jQuery('#charBox .closechat').click(closeChatClick);
        jQuery('#showChatBtn').click(showChatClick);

        // func. init
    }



    return{
        init: init,
        setUserAuthStatus: setUserAuthStatus
    }
})();

charlog.init({
    type: 'user'
});