"use strict";

/**
 *
 * @param itemRoot
 * @constructor
 */
wumvi.QuestionAnswer.Form = function (itemRoot) {
    /** @type {jQuery} */
    this.$itemRoot = jQuery(itemRoot);

    this.name_ = this.$itemRoot.data("id");
};

wumvi.QuestionAnswer.Form.prototype.show = function () {
    this.$itemRoot.addClass("show");
};

wumvi.QuestionAnswer.Form.prototype.getName = function () {
    return this.name_;
};

wumvi.QuestionAnswer.Form.prototype.hide = function () {
    this.$itemRoot.removeClass("show");
};

wumvi.QuestionAnswer.Form.prototype.switchAnswerMode = function (flag) {
    if (flag) {
        this.$itemRoot.addClass("answer-mode");
    } else {
        this.$itemRoot.removeClass("answer-mode");
    }
};