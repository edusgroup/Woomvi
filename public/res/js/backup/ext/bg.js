wumviContentOut = function(){

    function onBgMessage(msg, pSender, pSendResponse){
        console.log(msg.type);
        switch( msg.type ){
            case 'hideall':
                _sendDataToContentPage({type: 'hide'});
                break;
            case 'showall':
                _sendDataToContentPage(msg);
                break;
            case 'showall-now':
                pSendResponse({type:'get'});
                _sendDataToContentPage({type: 'showall-now'});
                break;
        } // switch

        // func. onBgMessage
    }

    var eventBox = 'wumviMsgId';

    function onDomEventData(){
        var eventData = document.getElementById(eventBox).innerText;
        var data = JSON.parse(eventData);

        /*switch(data.type){
            case 'hideall':
            break;
        }*/

        //console.log(port);
        port.postMessage(data);
        // func. onDomEventData
    }

    function init(){
        jQuery(document.body).append('<div id="'+eventBox+'" style="display: none"></div><script src="https://wumvi.com/logic/wordstatus/wrap/?v="+manifest.version></script>');
        document.getElementById(eventBox).addEventListener('wumviEvent', onDomEventData);
        // func. init
    }

    return{
        init: init,
        onBgMessage: onBgMessage
    }
};

wumviBGOut = (function(){
    function setToAllContent(pMsg){
        chrome.tabs.query({}, function(tabList) {
            if (!tabList || tabList.length == 0) {
                return;
            }
            for( var i in tabList ){
                //if ( /^https?/.test(tabList[i].url) ) {
                    //chrome.tabs.executeScript(tabList[i].id, {code: 'console.log(window);'});
                    chrome.tabs.sendMessage(tabList[i].id, pMsg);
               // }
            } // for
        });
        // func. setToAllContent
    }

    function onExtensionMessage(pMsg, sender,sendResponse){
        //console.log(_activeTabList);
        if ( !pMsg ){
            return;
        }
        if ( pMsg.type == 'showall-now'){
            if ( _activeTabList.length == 0 ){
                return;
            }

            chrome.tabs.sendMessage(_activeTabList[0], pMsg);
        } // if
        // func. onExtensionMessage
    }



    function onMessage(pMsg, portTab, tabId){
        //if ( !/^https?/.test(portTab.sender.tab.url) ){
        //    return;
        //}
        //console.log(_activeTabList);
        switch(pMsg.type){
            case 'init':
                _activeTabList.push(portTab.sender.tab.id);
                portTab.postMessage({'type':'init', data: wumviContentOut.toString()});
                break;
            case 'hideall':
                setToAllContent(pMsg);
                break;
            case 'showall':
                setToAllContent(pMsg);
                break;
            case 'showall-now':
                console.log('bg', 'showall-now');
                break;
        } // switch
        // func. onMessage
    }


    function init(){
        // func. init
    }


    return{
        init: init,
        onMessage: onMessage,
        onExtensionMessage: onExtensionMessage
    }
})();

