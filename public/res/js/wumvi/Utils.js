window.wumvi = window.wumvi || {};

wumvi.Utils = function() {
    jQuery('input[type="text"].select-text').click(function(){
        this.setSelectionRange(0, this.value.length);
    })

    var $video100pr = jQuery('.video-100pr');
    if ($video100pr.length) {
        $video100pr.each(function(num, obj){
            var $videoObj = jQuery(obj);
            $videoObj.height($videoObj.width() / 1.77777);
        })

        jQuery( window ).resize(function() {
            $video100pr.each(function(num, obj){
                var $videoObj = jQuery(obj);
                $videoObj.height($videoObj.width() / 1.77777);
            })
        });
    }



}