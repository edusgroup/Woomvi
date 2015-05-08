var wumviLogic = (function(){

    var isScriptLoad = false;

    function logicInit(){
        // console.log(window.jQuery);
        jQuery('body').append('<div id="wumviBox"><div class="innerWumviBox"></div></div>');
        // func. logicInit
    }


    function onScriptLoad(){
        if ( !isScriptLoad && (!this.readyState ||
            this.readyState === "loaded" || this.readyState === "complete") ) {
            isScriptLoad = true;
            logicInit();
        } // if
        // func. onScriptLoad
    }

    function initjQuery(){
        /** @see http://stackoverflow.com/questions/4845762/onload-handler-for-script-tag-in-internet-explorer */
        if ( !window.jQuery ){
            var script=document.createElement('script')
            script.setAttribute("type","text/javascript")
            script.setAttribute("src", 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js')

            script.onload = script.onreadystatechange = onScriptLoad;

            document.getElementsByTagName("head")[0].appendChild(script)
        }else{
            logicInit();
        } // if ( !window.jQuery )
        // func. initjQuery
    }

    function init(){
        initjQuery();
        // func. init
    }

    init();
})();