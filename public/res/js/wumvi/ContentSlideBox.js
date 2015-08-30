"use strict";

var wumvi = wumvi || {};

/**
 * @typedef {Object} wumvi.ContentSlideBox
 * @constructor
 */
wumvi.ContentSlideBox = function()
{
    /**
     * Объект плеера
     * @type {jQuery}
     */
    this.$playerBox = null;
};

/**
 * @param {!jQuery} $btn
 * @private
 */
wumvi.ContentSlideBox.prototype.switchLabelBtn_ = function($btn){
    var text = /** @type {string} */ ($btn.text());
    var newText = /** @type {string} */ ($btn.data("text"));
    $btn.html(newText).data("text", text);
};

wumvi.ContentSlideBox.prototype.initSound = function(id){
    this.$playerBox = jQuery(id);
    this.$playerBox.jPlayer({
        swfPath: "/res/bower_package/jplayer/dist/jplayer",
        //solution: "flash, html",
        supplied: "mp3, oga",
        wmode: "window",
        useStateClassSkin: true,
        autoBlur: false,
        smoothPlayBar: true,
        keyEnabled: true,
        remainingDuration: true,
        toggleDuration: true
    });
};

/**
 *
 * @protected
 */
wumvi.ContentSlideBox.prototype.playSound_ = function(urlTpl, id){
    var url = urlTpl.replace("%name", id);
    this.$playerBox.jPlayer("setMedia", {
        mp3: url.replace("%ext", "mp3"),
        oga: url.replace("%ext", "ogg")
    }).jPlayer("play");
};