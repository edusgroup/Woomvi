"use strict";
/* global ProgressRound */

wumvi = window.wumvi || {};

/**
 * @typedef {wumvi.ContentSlideBox} wumvi.CheckTest
 *
 * @constructor
 */
wumvi.CheckTest = function () {
    /**
     * @type {!jQuery}
     */
    this.$root = jQuery("#wumvi-check-test");
    this.$itemList = this.$root.find(".item");
    this.$errorLost = jQuery("#error-lost");

    /**
     * Количество разрешенных ошибок
     * @private
     * @type {Number}
     */
    this.errorCount_ = parseInt(this.$errorLost.data("count"));

    /**
     * Объекты с заданиями
     * @type {Array.<wumvi.CheckTest.Form>}
     */
    this.formList = [];

    /**
     * Прогресс бар, сколько всего заданий
     * @type {ProgressRound}
     */
    this.progressRound = new ProgressRound("#progressRound", {});

    this.currentItem = 0;

    this.init_();
};

wumvi.CheckTest.prototype.init_ = function(){
    var that = this;

    this.$itemList.eq(0).addClass("show");

    this.progressRound.next();

    this.$root.find(".item").each(function (num, obj) {
        that.formList.push(new wumvi.CheckTest.Form(obj));
    });

    this.initEvent_();
};

wumvi.CheckTest.prototype.initEvent_ = function(){
    var that = this;
    jQuery(document).on(wumvi.CheckTest.Form.EVENT_WRONG_ANSWER, function (event, data) {
        that.onAnswerWrong_();
    });

    jQuery(document).on(wumvi.CheckTest.Form.EVENT_RIGHT_ANSWER, function (event, data) {
        setTimeout(function(){
            that.onAnswerRight_();
        }, 500);
    });
};

wumvi.CheckTest.prototype.onAnswerRight_ = function(){
    if (this.currentItem + 1 === this.formList.length) {
        console.log("show button next level");
        return;
    }

    this.progressRound.next();

    this.formList[this.currentItem].hide();
    this.currentItem += 1;
    this.formList[this.currentItem].show();
};

wumvi.CheckTest.prototype.onAnswerWrong_ = function(){
    var that = this;
    this.errorCount_ -= 1;
    if (this.errorCount_ < 0) {
        setTimeout(function(){
            that.tooManyErrors_();
        }, 500);
        return;
    }

    this.$errorLost.html(this.errorCount_).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
};

wumvi.CheckTest.prototype.tooManyErrors_ = function(){
    this.$root.addClass("too-many-errors");
};