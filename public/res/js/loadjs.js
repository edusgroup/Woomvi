"use strict";

var wumvi = wumvi || {};
wumvi.LoadScript = (function () {
    /**
     * @param list
     * @param callback
     * @constructor
     */
    function LoadScript(list, callback){
        if (!list) {
            throw Error("List is empty");
        }

        this.list = list;
        this.current = 0;
        this.callback = callback;

        this.initLoadScript();
    }

    LoadScript.prototype.initLoadScript = function() {
        var that = this;
        var isReady = false;
        var scriptObj = document.createElement("script");
        scriptObj.type = "text/javascript";
        scriptObj.src = this.list[this.current];
        scriptObj.onload = scriptObj.onreadystatechange = function() {
            if ( !isReady && (!this.readyState || this.readyState === "complete" || this.readyState === "loaded") ) {
                isReady = true;
                that.current += 1;
                if (that.current === that.list.length) {
                    that.callback();
                } else {
                    that.initLoadScript();
                }
            }
        };
        var parentObj = document.getElementsByTagName("script")[0];
        parentObj.parentNode.insertBefore(scriptObj, parentObj);
    };

    return LoadScript;
})();

