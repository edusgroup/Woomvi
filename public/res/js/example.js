var exampleLogic = (function(){
    var lastWordId = -1;
    var lastSentId = -1;

    function getParam(pObj){
        var wordId = jQuery(pObj).attr('rel');

        var sentId = jQuery(pObj).parents('.sent:first').attr('data-id');

        var blockId = null;
        for( blockId in relationList[sentId][wordId] ){
            if ( relationList[sentId][wordId][blockId] == 'main' ){
                break;
            } // if
        } // for( blockId

        return{
            wordId: wordId,
            sentId: sentId,
            blockId: blockId
        }
        // func. getParam
    }

    function onWordMouseOver(pObj){
        /*var wordId = jQuery(pObj).attr('rel');
         //if ( lastWordId == wordId ){
         //   return;
         //}

         lastWordId = wordId;
         var sentId = jQuery(pObj).parents('.sent:first').attr('data-id');

         var blockId = null;
         for( blockId in relationList[sentId][wordId] ){
         if ( relationList[sentId][wordId][blockId] == 'main' ){
         break;
         } // if
         } // for( blockId*/
        var param = getParam(pObj);
        var wordId = param.wordId;
        var sentId = param.sentId;
        var blockId = param.blockId;

        // console.log(wordId, sentId, blockId);

        for( wordId in relationList[sentId] ){
            var type = relationList[sentId][wordId][blockId];
            if ( type ){
                jQuery('#w'+wordId).addClass('highlight-'+type);
            }
        } // for

        if ( translateList[sentId][blockId] ){
            jQuery('#explainPanelBox').html(translateList[sentId][blockId]);
        }

        var $sent = jQuery('#sent'+sentId);
        var pos  = $sent.position();
        var left = pos.left + $sent.outerWidth();
        jQuery('#sendHelp').show().css({top: pos.top, left:left});


        var $timeBlock = $sent.parents('.time-block:first');
        pos = $timeBlock.position();
        left = pos.left - jQuery('#playTimeBlock').width();
        jQuery('#playTimeBlock').show().css({top: pos.top, left:left});

        lastSentId = sentId;
        // func. onWordMouseOver
    }

    function onWordMouseOut(pObj){
        jQuery(pObj).parents('.sent:first').find('.highlight-main,.highlight-second').removeClass('highlight-main highlight-second');
        // func. onWordMouseOut
    }

    function onHtmlDataBoxMouseOut(pEvent){
        if ( jQuery(pEvent.target).hasClass('word') ){
            onWordMouseOut(pEvent.target);
        }
        // func. onHtmlDataBoxMouseOut
    }

    function onHtmlDataBoxMouseOver(pEvent){
        if ( jQuery(pEvent.target).hasClass('word') ){
            onWordMouseOver(pEvent.target);
        }
        // func. onHtmlDataBoxMouseOver
    }

    function onWordClick(pObj){
        var param = getParam(pObj);
        var wordId = param.wordId;
        var sentId = param.sentId;
        var blockId = param.blockId;

        // jQuery('#explainPanelBox .rule:first').addClass('show').load('/ajax/?ajax=expWord&sentId='+sentId+'&blockId='+blockId+'&exampleId='+exampleId);

        // func. onWordClick
    }

    function onHTMLDataBoxClick(pEvent){
        if ( jQuery(pEvent.target).hasClass('word') ){
            onWordClick(pEvent.target);
        }
        // func. onHTMLDataBoxClick
    }

    function onSendHelpClick(pEvent){
        jQuery.getJSON('/ajax/?ajax=expSent&sentId='+lastSentId+'&exampleId='+exampleId, '', function(pData){
            jQuery('#explainPanelBox').html(pData.translate);
            // jQuery('#explainPanelBox .rule:first').html(pData.exp).show();
        });
        // func. onSendHelpClick
    }

    function init(){
        jQuery('#htmlDataBox')
            .mouseover(onHtmlDataBoxMouseOver)
            .mouseout(onHtmlDataBoxMouseOut)
            .click(onHTMLDataBoxClick)
            .append('<div id="sendHelp"><img src="/res/img/sample/help32.png"/></div>')
            .append('<div id="playTimeBlock"><img src="/res/img/sample/play32.png"/></div>');

        jQuery('#sendHelp').click(onSendHelpClick);


        // func. init
    }

    init();
})();