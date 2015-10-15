'use strict';
/* global EventDomDispatcher */

var wumvi = wumvi || {};
wumvi.wordspeed = wumvi.wordspeed || {};
wumvi.wordspeed.ctrl = (function () {
    var EVENT_BOX_NAME = 'wumvi.module.wordspeed';
    var EVENT_ON_START = 'wumvi.module.wordspeed.onStart';
    var EVENT_STOP = 'wumvi.module.wordspeed.stop';
    var EVENT_ON_CHANGE_SPEED = 'wumvi.module.wordspeed.onChangeSpeed';

    /**
     * @constructor
     */
    function WordSpeedTrainer() {
        this.SWITCH_SPEED_ADVANSED_TIME = 800;
        this.SWITCH_SPEED_BEGINER_TIME = 1300;

        this.QUESTION_MODE_FLAG = 'question-mode';
        this.ANSWER_MODE_FLAG = 'answer-mode';

        this.wordNum_ = 0;
        this.$wordBox_ = jQuery('#word-box');
        this.$wordItemText_ = this.$wordBox_.find('.text-js');
        this.speedTime_ = this.SWITCH_SPEED_BEGINER_TIME;
        this.wordList_ = wumvi.wordspeed.wordList.shuffle();
        this.eventDispatcher_ = new EventDomDispatcher(EVENT_BOX_NAME, false);
    }

    /**
     * @public
     */
    WordSpeedTrainer.prototype.init = function (params) {
        this.initEvent_();
    };

    /**
     * @private
     */
    WordSpeedTrainer.prototype.initEvent_ = function (params) {
        var that = this;

        this.eventDispatcher_.addListener(EVENT_ON_START, function () {
            that.startTesting_();
        });

        this.eventDispatcher_.addListener(EVENT_ON_CHANGE_SPEED, function (event, data) {
            that.changeSpeed_(data);
        });

    };

    WordSpeedTrainer.prototype.changeSpeed_ = function (data) {
        if (data.speed === 'profi') {
            this.speedTime_ = this.SWITCH_SPEED_ADVANSED_TIME;
            return;
        }

        this.speedTime_ = this.SWITCH_SPEED_BEGINER_TIME;
    };

    /**
     * @private
     */
    WordSpeedTrainer.prototype.setWordItemText_ = function (text) {
        this.$wordItemText_.html(text);
    };

    WordSpeedTrainer.prototype.startTesting_ = function () {
        this.showWordItem();
    };

    WordSpeedTrainer.prototype.nextWordItem = function () {
        var that = this;

        var wordItem = this.wordList_[this.wordNum_];
        this.setWordItemText_(wordItem.transl);
        this.$wordBox_.addClass(this.ANSWER_MODE_FLAG).removeClass(this.QUESTION_MODE_FLAG);
        setTimeout(function () {
            that.showWordItem();
        }, this.speedTime_);

        this.wordNum_ = this.wordNum_ + 1;
    };

    WordSpeedTrainer.prototype.showWordItem = function () {
        if (this.wordNum_ === this.wordList_.length) {
            var text = /** @type {string} */ (this.$wordBox_.data('end-text'));
            this.setWordItemText_(text);
            this.eventDispatcher_.emit(EVENT_STOP);
            this.$wordBox_.removeClass(this.ANSWER_MODE_FLAG);
            this.wordNum_ = 0;
            return;
        }
        var that = this;

        var wordItem = this.wordList_[this.wordNum_];
        this.setWordItemText_(wordItem.text);
        this.$wordBox_.addClass(this.QUESTION_MODE_FLAG).removeClass(this.ANSWER_MODE_FLAG);

        setTimeout(function () {
            that.nextWordItem();
        }, this.speedTime_);
    };

    function init(params) {
        return (new WordSpeedTrainer()).init(params);
    }

    return {
        WordSpeedTrainer: WordSpeedTrainer,
        init: init
    };
})();