/**
 *  **************** PENDULUM **********************************************************
 */
var wumviPendulum = (function(){
    var textNum = 0;
    var showTextStatus = null;
    var $pendulumPoint = null;
    var $buttonStart = null;

    function onTimer(){
        if ( textNum == pendulumData.length ){
            $buttonStart.html('Заново').css({'display':'inline-block'});
            $pendulumPoint.removeClass('block').addClass('hide');
            textNum = 0;
            return;
        }

        if ( showTextStatus ){
            $pendulumPoint.addClass('text').removeClass('block').html(pendulumData[textNum]['text']);
            setTimeout(onTimer, pendulumData[textNum]['time1']);
        }else{
            $pendulumPoint.addClass('block').removeClass('text');
            setTimeout(onTimer, pendulumData[textNum]['time2']);
            textNum++;
        }

        showTextStatus = !showTextStatus;
        // func. onTimer
    }

    function onStart(pObj){
        $buttonStart = jQuery(pObj);
        $buttonStart.hide();
        showTextStatus = true;
        onTimer();
        return false;
        // func. onStart
    }

    function init(){
        $pendulumPoint = jQuery('#wumviBox .pendulumPoint');
        // func. init
    }

    init();

    return{
        onStart: onStart
    }
})();

/**
  *  **************** PLAYER **********************************************************
 */
var wumviPlayer = (function(){
    var html5player = (function(){
        function play(pUrl){
            audioControl.type = fileType;
            audioControl.src = pUrl + fileExt;

            audioControl.play();
            // func. play
        }
        return{
            play: play
        }
    })();



    var audioControl = null;
    var fileExt = null;
    var fileType = null;

    var isHTML5Support = null;

    //return !!(a.canPlayType && a.canPlayType('audio/mpeg;').replace(/no/, ''));

    function initAudioFileExt(){
        var contentType = {
            '.mp3': 'audio/mpeg',
            '.m4a': 'audio/mp4',
            '.ogg': 'audio/ogg',
            '.webm': 'audio/webma',
            '.wav': 'audio/wav'
        }

        for( var ext in contentType ){
            var type = contentType[ext];
            if ( audioControl.canPlayType(type+';').replace(/no/, '') ){
                fileExt = ext;
                fileType = type;
                break;
            }
        } // for

        return fileExt;
        // func. initAudioFileExt
    }

    function dontSupportHTML5(){
        isHTML5Support = false;
        // func. dontSupportHTML5
    }

    function supportHTML5(){
        if ( !initAudioFileExt() ){
            dontSupportHTML5();
            return;
        }
        isHTML5Support = true;

        // func. supportHTML5
    }

    function play(pUrl){
        if ( isHTML5Support == null ){
            console.error('Player is not ready');
            return;
        }

        html5player.play(pUrl);
        // func. play
    }

    function init(){
        audioControl = document.createElement('audio');
        // Поддерживали ли HTML5 audio
        if ( audioControl.canPlayType){
            supportHTML5();
        }else{
            dontSupportHTML5();
        }
        // func. init
    }

    init();

    return{
        play: play
    }
})();


/**
 * **************** BOX LOGIC **********************************************************
 */
var wumviBoxLogic = (function(){
    function onPlay(pObj){
        var url = jQuery(pObj).attr('href');
        wumviPlayer.play(url);
        return false;
        // func. onPlay
    }

    function init(){
        // func. init
    }

    init();

    return{
        onPlay: onPlay
    }
})();



var wimviMistake = (function(){

    function showAnswer(pObj){
        jQuery('#wumviBox .answer').show();
        jQuery(pObj).hide();
        return false;
        // func. onPlay
    }

    function init(){
        // func. init
    }

    init();

    return{
        showAnswer: showAnswer
    }
})();

var wumviQA = (function(){

    function showAnswer(pObj){
        jQuery('#wumviBox .answer').show();
        jQuery(pObj).hide();
        return false;
        // func. onPlay
    }

    function init(){
        // func. init
    }

    init();

    return{
        showAnswer: showAnswer
    }
})();