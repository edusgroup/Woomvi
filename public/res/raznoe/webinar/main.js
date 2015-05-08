var logic = (function(){
    var room = 'prep';
    var timestamp = 0;

    var sound = true;

    function getFileCallback(pData){
        if ( timestamp == pData.time){
            return;
        }
        timestamp = pData.time;
        jQuery('#contentBox').html(pData.data);
        // func. getFileCallback
    }

    function soundOnOffBtnClick(){
        sound = !sound;
        adminMvc.setSoundOnOff(sound);

        jQuery('#soundOnOffBtn').toggleClass('sound-off');
        return false;
        // func. soundOnOffBtnClick
    }

    function timerCallback(){
        jQuery.ajax({
            type: 'POST',
            url: '/logic/webinar/user/',
            data: {
                type: 'user',
                room: room,
                method:'get'
            },
            dataType: 'json'
        }).done(getFileCallback);
        // func. timerCallback
    }


    function init(){
        setInterval(timerCallback, 2000);

        jQuery('#soundOnOffBtn').click(soundOnOffBtnClick);
        // func. init
    }
    init();
})();


var adminMvc = (function(){
    var flashPlayer = null;
    function onRequest(){
        // func. onRequest
    }

    function onIOError(){
        // func. onIOError
    }

    function onSwfInit(){
        flashPlayer = document.getElementById('flashBox');
        // func. onSwfInit
    }

    function setSoundOnOff(pStatus){
        flashPlayer.setSoundOnOff(pStatus);
        // func. setSoundOnOff
    }

    return{
        onRequest: onRequest,
        onIOError: onIOError,
        onSwfInit: onSwfInit,

        setSoundOnOff: setSoundOnOff
    }
})();