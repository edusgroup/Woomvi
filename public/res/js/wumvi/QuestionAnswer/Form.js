"use strict";

/**
 *
 * @param itemRoot
 * @constructor
 */
wumvi.QuestionAnswer.Form = function (itemRoot) {
    /** @type {jQuery} */
    this.$itemRoot = jQuery(itemRoot);


    ///** @type {string} */
    //this.id = this.$itemRoot.data("id");
    //
    //this.$btnAnswer = this.$itemRoot.find(".item-btn.answer");
    //
    //this.$itemRoot.find(".box").perfectScrollbar();
    //
    //this.RIGHT_ANSWER_CLASS = "right-answer";
    //this.WRONG_ANSWER_CLASS = "wrong-answer";
    //
    //this.SHOW_ANSWER_CLASS = "show-block-1";
    //
    //this.STATUS_QUESTION = "question";
    //this.STATUS_ANSWER = "answer";
    //
    //this.status = this.STATUS_QUESTION;
    //
    //this.attachEvent();
};

wumvi.QuestionAnswer.Form.prototype.show = function () {
    this.$itemRoot.addClass("show");
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