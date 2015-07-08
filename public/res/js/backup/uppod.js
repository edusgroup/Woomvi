var playerMvc = (function(){
    function init(){
        var iOS = ( navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false );
        if ( iOS ){
            jQuery('#playerBox').html('Тут должен быть плеер. Простите нас. Мы пока еще не создали код,<br/>чтобы звук поддерживался на iOS.<br/>' +
                'На обычном ноутбуке или компьютере всё будет работать.')
                .addClass('warning-box');
            return;
        }

        if(!swfobject.hasFlashPlayerVersion("9.0.115")){
            jQuery('#playerBox').html('Тут должен быть плеер. Но скорей всего у вас отключён или не установлен Flash Player. Скачайте и установите его сайта ' +
                ' <a href="http://get.adobe.com/ru/flashplayer/" rel="nofollow">http://get.adobe.com/ru/flashplayer/</a> ')
                .addClass('warning-box');
            return;
        }

        swfobject.embedSWF("/res/plugin/player/uppod-audio2.swf",
            "playerBox", "100%", "36", "8",
            null,
            {st:'/res/plugin/player/style.txt', file:fileId},
            { allowScriptAccess: "always", wmode: "opaque", allowFullScreen: 'false' },
            { id: "playerObjId",  name: "playerObjId" });
        // func. init
    }

    init();
})();




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

