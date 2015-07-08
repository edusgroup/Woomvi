var menuSelect = (function(){



    function init(pParam){
        var cookieVal = jQuery.cookie('menuSelect');
        cookieVal = cookieVal != null ? JSON.parse(cookieVal) : {id:'',list:[]};

        if ( cookieVal.id != pParam.id){
            cookieVal.list = [];
        }

        for( var id in pParam.list ){
            var isNotSee = cookieVal.list.indexOf(id) == -1;

            console.log(isNotSee)

            if ( isNotSee) {
                if ( pParam.list[id].test(window.location.pathname)){
                    cookieVal.list.push(id);
                }else{
                    jQuery('#' + pParam.prefix + 'item-' + id + ' a:first').addClass('show').find('.num').html('1');
                } // if
            } // if

        } // for(id)

//        console.log(cookieVal.list);

        cookieVal.id = pParam.id;
        cookieVal = JSON.stringify(cookieVal);
        jQuery.cookie('menuSelect', cookieVal, { expires : 60*60*24*365, path: '/' });
        // func. init
    }

    return{
        init: init
    }
})();

menuSelect.init({
    list: {
       // 23:/\/shop\//, 21:/\/programm\//
    },
    id: 2,
    prefix: 'main-menu-'
})