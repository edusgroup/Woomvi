"use strict";
/* global ProgressRound, console */

var wumvi = wumvi || {};

//var coreUtils = new wumvi.Utils();
//coreUtils.initSelectTextInInput();

/**
 * @constructor
 */
wumvi.TrashMistake = function() {
    /**
     * @type {!jQuery}
     */
    this.$root = jQuery("#wumvi-trash-mistake");

    /**
     * Объекты с заданиями
     * @type {Array.<wumvi.TrashMistake.Form>}
     */
    this.formList = [];

    /**
     * Текущий элемент задания
     * @type {number}
     */
    this.currentItem = 0;

    /**
     * Прогресс бар, сколько всего заданий
     * @type {ProgressRound}
     */
    this.progressRound = new ProgressRound("#progressRound", {});

    this.$showAnswer = jQuery("#showAnswer");
    this.$checkAnswer = jQuery("#checkAnswer");
    this.$nextQuestion = jQuery("#nextQuestion");

    this.showAnswerMode = false;

    this.init_();
};

wumvi.TrashMistake.prototype = Object.create(wumvi.ContentSlideBox.prototype);

/**
 *
 * @private
 */
wumvi.TrashMistake.prototype.init_ = function () {
    var that = this;

    this.progressRound.next();

    this.$root.find(".item").each(function (num, obj) {
        that.formList.push(new wumvi.TrashMistake.Form(obj, num));
    });

    jQuery(document).trigger(wumvi.TrashMistake.Form.EVENT_ITEM_SHOW);
    this.formList[0].show();

    this.initEvent_();
};

wumvi.TrashMistake.prototype.initEvent_ = function () {
    var that = this;

    this.$showAnswer.click(function () {
        that.onShowAnswerClick();
        return false;
    });

    this.$checkAnswer.click(function () {
        that.onCheckAnswerClick();
        return false;
    });

    this.$nextQuestion.click(function () {
        that.onNextQuestionClick();
        return false;
    });
};

wumvi.TrashMistake.prototype.onShowAnswerClick = function () {
    this.switchAnswerMode();
};

/**
 *
 */
wumvi.TrashMistake.prototype.switchAnswerMode = function () {
    this.showAnswerMode = !this.showAnswerMode;
    this.formList[this.currentItem].switchAnswerMode(this.showAnswerMode);
    this.switchLabelBtn_(this.$showAnswer);
};

wumvi.TrashMistake.prototype.onCheckAnswerClick = function () {
    var event = new wumvi.TrashMistake.Event();
    event.setId(this.currentItem);
    if (this.formList[this.currentItem].check()) {
        jQuery(document).trigger(wumvi.TrashMistake.Form.EVENT_RIGHT_ANSWER, [event]);
        this.formList[this.currentItem].setRightState();
    } else {
        jQuery(document).trigger(wumvi.TrashMistake.Form.EVENT_WRONG_ANSWER, [event]);
        this.formList[this.currentItem].setWrongState();
    }

    this.$nextQuestion.parents(".panel-button").addClass("show");

    if (this.currentItem + 1 === this.formList.length) {
        this.showNextTaskBtn();
    }
};

wumvi.TrashMistake.prototype.onNextQuestionClick = function () {
    this.formList[this.currentItem].hide();
    this.currentItem += 1;
    this.formList[this.currentItem].show();

    this.showAnswerMode = false;

    this.$nextQuestion.parents(".panel-button").removeClass("show");

    if (this.currentItem + 1 === this.formList.length) {
        this.$nextQuestion.addClass("hide");
    }
};

wumvi.TrashMistake.prototype.showNextTaskBtn = function () {
    console.log("next task");
};

/**
 *
 * @param data {wumvi.TrashMistake.Event}
 * @param type {string}
 * @private
 */
ProgressRound.prototype.setState_ = function (data, type) {
    var num = data.getId();
    jQuery("#" + this.ID_ITEM_PROGRESS + num).removeClass("wrong right").addClass(type);
};

ProgressRound.prototype.initEventParent_ = ProgressRound.prototype.initEvent_;
ProgressRound.prototype.initEvent_ = function () {
    var that = this;
    ProgressRound.prototype.initEventParent_();

    jQuery(document).on(wumvi.TrashMistake.Form.EVENT_WRONG_ANSWER, function (event, data) {
        that.setState_(data, "wrong");
    });

    jQuery(document).on(wumvi.TrashMistake.Form.EVENT_RIGHT_ANSWER, function (event, data) {
        that.setState_(data, "right");
    });
};