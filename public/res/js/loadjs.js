function loadJsCallback(list, func){
    var num = ++func.num;
    if (list.length == num) {
        return;
    }

    func(list[num], list, loadJsCallback);
}

function loadJsList(list) {
    if (!list) {
        return;
    }
    loadScript.num = 0;
    var src = list[loadScript.num];
    loadScript(src, list, loadJsCallback);
}

function loadScript(src, list, callback)
{
    var func = arguments.callee;
    // callback(list, arguments.callee);
    var isReady = false;
    var scriptObj = document.createElement('script');
    scriptObj.type = 'text/javascript';
    scriptObj.src = src;
    scriptObj.onload = scriptObj.onreadystatechange = function() {
        if ( !isReady && (!this.readyState || this.readyState == 'complete' || this.readyState == 'loaded') ) {

            isReady = true;
            callback(list, func);
        }
    };
    var parentObj = document.getElementsByTagName('script')[0];
    parentObj.parentNode.insertBefore(scriptObj, parentObj);
}