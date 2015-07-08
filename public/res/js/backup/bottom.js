/*var headcontainerHeight = jQuery('#headcontainer').height();
var windowHeight = jQuery(window).height();


function windowResize(){
    var windowHeight = jQuery(window).height();
    jQuery('.block').css({height: windowHeight});

    var $block= jQuery('div.block .text:first');
    var marginHeight = ( windowHeight - $block.height() ) / 2;
    $block.css({'top': marginHeight});

    jQuery('.resize-image').each(function(pNum, pObj){
        jQuery(pObj).find('img:first').css({'max-height': windowHeight*0.9});
    });


    // func. windowResize
}


jQuery(window).resize(windowResize).ready(windowResize);


var leftPanel = (function(){
    jQuery('body:first').prepend(' <nav class="b-nav" id="navRight"><div class="b-nav-buts"></div><div class="b-nav-arrs"><div class="b-nav-arr_top b-nav-arr_disabled"><div></div></div><div class="b-nav-arr_bot"><div></div></div></div></nav>')

    var html = '';
    jQuery('.block').each(function(pNum, pObj){
        var id = jQuery(pObj).attr('id');
        html = html + '<a class="b-nav-buts-item"  href="#'+id+'"><div class="b-nav-buts-item-i"></div></a>';
    });

    jQuery('#navRight').prepend(html);
})();*/