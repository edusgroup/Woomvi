var engMvc = (function(){

    var lastHighlightPartNum = 0;

    function highlightPart(pTime){
        var time = Math.round(pTime);
        var $part = jQuery('#second' + time);
        if ( $part.length == 0 ){
            return null;
        }
        jQuery('#second' + lastHighlightPartNum).removeClass('highlight');
        var $obj = jQuery('#second' + time);
        $obj.addClass('highlight');
        lastHighlightPartNum = time;
        return $obj;
        // func. highlightPart
    }

    function removeHighlight(){
        jQuery('#second' + lastHighlightPartNum).removeClass('highlight');
        // func. cbPlayComplete
    }

    function findHighLightPart(pTime){
        var time = Math.round(pTime);
        for( var i = time; i >= 0; i-- ){
            var $part = jQuery('#second' + i);
            if ( $part.length == 0 ){
                continue;
            }
            highlightPart(i);
            return $part;
        }
        return null;
        // func. findHighLightPart
    }

    function init(){
        // func. init
    }

     return{
        highlightPart: highlightPart,
        findHighLightPart: findHighLightPart,
        removeHighlight: removeHighlight,
        init: init
    }
})();

engMvc.init();


/** ============================================================================================ **/
/** @see https://developers.google.com/youtube/js_api_reference */
var playerVideoMvc = (function(){
    var ytplayer;

    var playInterval;
    var playerTime;

    function intervalAction(){
        var time = ytplayer.getCurrentTime() - 0.5;
        if ( playerTime == time ){
            return;
        }
        playerTime = time;
        var $obj = engMvc.highlightPart(time);
        scrollGoto($obj);
        // func. intervalAction
    }

    function scrollGoto($obj){
        if ( $obj != null ){
            var htmlDataBoxTop = jQuery('#htmlDataBox').offset().top;
            var scrollOffset = ($obj.offset().top - htmlDataBoxTop);

            scrollOffset -= getMaskHeight() / 2  - $obj.outerHeight();

            //jQuery('#sourceBox').scrollbar.goto({v:scrollOffset});
            //jQuery('#sourceBox').scrollTo( {top:scrollOffset+ 'px', left: '0px'}, 300);
        }
        // func. scrollGoto
    }

    function onStateChange(newState){
        /*-1 (unstarted)
         0 (ended)
         1 (playing)
         2 (paused)
         3 (buffering)
         5 (video cued)*/
        switch(newState){
            case 0:
                console.log('end movie');
            // break специально не стоит
            case 5:
                console.log('stop download');
                engMvc.removeHighlight();
                stop();
                break;
            case 2:
                // Получаем текущее время видео
                var posSec = ytplayer.getCurrentTime() - 0.5;
                // Выделяем предложение
                var $obj = engMvc.findHighLightPart(posSec);
                // Проматываем до выделенного предложения
                scrollGoto($obj);
                // Останавливаем видео
                stop();
                break;
            case 1:
                playInterval = setInterval(intervalAction, 800);
                break;
        } // switch(newState){
        // func. onStateChange
    }

    function stop(){
        if ( playInterval == null){
            return;
        }
        clearInterval(playInterval);
        // func. stop
    }

    function setPosition(posMilis){
        var posSec = posMilis/1000
        ytplayer.seekTo(posSec);
        // func. setPosition
    }

    function getMaskHeight(){
        return 300;
        // func. getMaskHeight
    }

    function init(){
        ytplayer = document.getElementById("playerObjId");
        ytplayer.addEventListener("onStateChange", 'playerVideoMvc.onStateChange');
        // func. init
    }

    function toggle(){
        var state = ytplayer.getPlayerState();
        if ( state == 1 ){
            ytplayer.pauseVideo();
        }else{
            ytplayer.playVideo();
        }
        // func. toggle
    }

    return {
        onStateChange: onStateChange,
        getMaskHeight: getMaskHeight,
        setPosition: setPosition,

        toggle: toggle,
        scrollGoto: scrollGoto,

        init: init
    }
})();

function onYouTubePlayerReady(playerId) {
    playerVideoMvc.init();
    // func. onYouTubePlayerReady
}

swfobject.embedSWF("http://www.youtube.com/v/"+fileId+"?enablejsapi=1&playerapiid=ytplayer",
    "playerBox", "580", "326", "8", null, null, { allowScriptAccess: "always", wmode: "opaque" },
    { id: "playerObjId",  name: "playerObjId" });