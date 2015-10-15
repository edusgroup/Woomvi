'use strict';

window.wumvi = window.wumvi || {};

Array.prototype.shuffle = function () {
    var i = this.length, j, temp;
    if (i === 0) {
        return this;
    }

    while (i > 0) {
        j = Math.floor(Math.random() * ( i ));
        i -= 1;
        temp = this[i];
        this[i] = this[j];
        this[j] = temp;
    }
    return this;
};

wumvi.Utils = function () {

};

wumvi.Utils.prototype.initSelectTextInInput = function () {
    jQuery('input[type="text"].select-text').click(function () {
        this.setSelectionRange(0, this.value.length);
    });
};

wumvi.Utils.prototype.initVideoHeight = function () {
    var $video100pr = jQuery('.video-100pr');
    if ($video100pr.length) {
        $video100pr.each(function (num, obj) {
            var $videoObj = jQuery(obj);
            $videoObj.height($videoObj.width() / 1.77777);
        });

        jQuery(window).resize(function () {
            $video100pr.each(function (num, obj) {
                var $videoObj = jQuery(obj);
                $videoObj.height($videoObj.width() / 1.77777);
            });
        });
    }
};

/**
 * @param {!jQuery} $btn
 * @public
 */
wumvi.Utils.prototype.switchTextOfElement = function ($btn) {
    var text = /** @type {string} */ ($btn.text());
    var newText = /** @type {string} */ ($btn.data('text'));
    $btn.html(newText).data('text', text);
};