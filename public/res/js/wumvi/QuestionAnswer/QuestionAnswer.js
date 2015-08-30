"use strict";

/* global ProgressRound, console */

window.wumvi = window.wumvi || {};

wumvi.QuestionAnswer = function () {
    this.$root = jQuery("#question-answer-box");

    /**
     * Объекты с заданиями
     * @type {Array.<wumvi.QuestionAnswer.Form>}
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
    this.$soundExample = jQuery("#soundExample");
    this.$nextQuestion = jQuery("#nextQuestion");

    this.showAnswerMode = false;

    this.init_();
};

wumvi.QuestionAnswer.prototype = Object.create(wumvi.ContentSlideBox.prototype);

wumvi.QuestionAnswer.prototype.init_ = function(){
    var that = this;
    this.progressRound.next();

    this.$root.find(".item").each(function (num, obj) {
        that.formList.push(new wumvi.QuestionAnswer.Form(obj, num));
    });

    // jQuery(document).trigger(wumvi.QuestionAnswer.Form.EVENT_ITEM_SHOW);
    this.formList[0].show();

    this.initEvent_();
};

wumvi.QuestionAnswer.prototype.initEvent_ = function(){
    var that = this;

    this.$showAnswer.click(function () {
        that.onShowAnswerClick();
        return false;
    });

    this.$soundExample.click(function () {
        that.onSoundExampleClick();
        return false;
    });

    this.$nextQuestion.click(function () {
        that.onNextQuestionClick();
        return false;
    });
};

wumvi.QuestionAnswer.prototype.onShowAnswerClick = function () {
    this.switchAnswerMode();
};

/**
 *
 */
wumvi.QuestionAnswer.prototype.switchAnswerMode = function () {
    this.showAnswerMode = !this.showAnswerMode;
    this.formList[this.currentItem].switchAnswerMode(this.showAnswerMode);
    this.switchLabelBtn_(this.$showAnswer);
};


wumvi.QuestionAnswer.prototype.onSoundExampleClick = function () {
    console.warn("sound exmple");
};

wumvi.QuestionAnswer.prototype.onNextQuestionClick = function () {
    this.formList[this.currentItem].hide();
    this.currentItem += 1;
    this.formList[this.currentItem].show();

    this.showAnswerMode = false;

    this.$nextQuestion.parents(".panel-button").removeClass("show");

    this.progressRound.next();

    if (this.currentItem + 1 === this.formList.length) {
        this.$nextQuestion.addClass("hide");
        this.showNextTaskBtn();
    }
};

wumvi.QuestionAnswer.prototype.showNextTaskBtn = function () {
    console.warn("next task");
};