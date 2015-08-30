"use strict";

/**
 *
 * @param {!jQuery} $itemRoot
 * @param {string} id
 * @constructor
 */
wumvi.Card.Form = function ($itemRoot, id) {
    this.id_ = id;
    this.$itemRoot = $itemRoot;

    this.init_();
};

wumvi.Card.Form.prototype.init_ = function(){

};

/**
 * @public
 * @param mode
 */
wumvi.Card.Form.prototype.switchAnswerMode = function(mode) {
    if (mode) {
        this.$itemRoot.addClass("translate-mode");
    } else {
        this.$itemRoot.removeClass("translate-mode");
    }
};
