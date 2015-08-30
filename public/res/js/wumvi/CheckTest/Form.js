"use strict";
/* global */

wumvi = window.wumvi || {};

/**
 * @constructor
 */
wumvi.CheckTest.Form = function(obj){
    this.$obj = jQuery(obj);

    this.answerId = /** @type {string} */ (this.$obj.data("aid"));

    this.init_();
};

wumvi.CheckTest.Form.EVENT_WRONG_ANSWER = "wrong-answer";
wumvi.CheckTest.Form.EVENT_RIGHT_ANSWER = "right-answer";

wumvi.CheckTest.Form.prototype.init_ = function(){
    this.initEvent_();
};

wumvi.CheckTest.Form.prototype.initEvent_ = function(){
    var that = this;

    this.$obj.find(".check-list input").change(function(){
        that.onInputAnswerChange_(this);
    });
};

wumvi.CheckTest.Form.prototype.onInputAnswerChange_ = function(obj){
    var $obj = jQuery(obj);
    $obj.prop("disabled", true);

    var val = /** @typeof {string} */ ($obj.val());
    val = parseInt(val);
    if (this.checkAnswer_(val)) {
        $obj.closest("label").addClass("right");
        jQuery(document).trigger(wumvi.CheckTest.Form.EVENT_RIGHT_ANSWER);
    } else {
        $obj.closest("label").addClass("error");
        jQuery(document).trigger(wumvi.CheckTest.Form.EVENT_WRONG_ANSWER);
    }
};

wumvi.CheckTest.Form.prototype.show = function () {
    this.$obj.addClass("show");
};

wumvi.CheckTest.Form.prototype.hide = function () {
    this.$obj.removeClass("show");
};

/**
 *
 * @param {string} val
 */
wumvi.CheckTest.Form.prototype.checkAnswer_ = function(val){
    return val === this.answerId;
};

