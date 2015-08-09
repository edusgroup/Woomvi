window.wumvi = window.wumvi || {};
var coreUtils = new wumvi.Utils();
coreUtils.initSelectTextInInput();

wumvi.TrashMistake = function () {
    var that = this;

    /**
     *
     * @type {!jQuery}
     */
    this.$root = jQuery('#wumvi-trash-mistake');

    /**
     *
     * @type {Array.<wumvi.TrashMistake.Form>}
     */
    this.formList = [];

    this.$root.find('.item').each(function (num, obj) {
        that.formList.push(new wumvi.TrashMistake.Form(obj, num));
    });

    /**
     *
     * @type {number}
     */
    this.currentItem = 0;



    jQuery(document).trigger(wumvi.TrashMistake.Form.EVENT_ITEM_SHOW);
    this.formList[0].show();

    /**
     *
     * @type {ProgressRound}
     */
    this.progressRound = new ProgressRound('#progressRound', {});
    this.progressRound.next();

    this.$showAnswer = jQuery('#showAnswer');
    this.$checkAnswer = jQuery('#checkAnswer');
    this.$nextQuestion = jQuery('#nextQuestion');

    this.initEvent();
};

wumvi.TrashMistake.prototype.onShowAnswerClick = function() {

};

wumvi.TrashMistake.prototype.onCheckAnswerClick = function() {
    var event = new wumvi.TrashMistake.Form.Event();
    event.setId(this.currentItem);
    if (this.formList[this.currentItem].check()){
        jQuery(document).trigger(wumvi.TrashMistake.Form.EVENT_RIGHT_ANSWER, [event]);
        this.formList[this.currentItem].setRightState();
    } else {
        jQuery(document).trigger(wumvi.TrashMistake.Form.EVENT_WRONG_ANSWER, [event]);
        this.formList[this.currentItem].setWrongState();
    }
};

wumvi.TrashMistake.prototype.onNextQuestionClick = function() {

};

wumvi.TrashMistake.prototype.initEvent = function() {
    var that = this;

    this.$showAnswer.click(function(){
        that.onShowAnswerClick();
        return false;
    });

    this.$checkAnswer.click(function(){
        that.onCheckAnswerClick();
        return false;
    });

    this.$nextQuestion.click(function(){
        that.onNextQuestionClick();
        return false;
    });
};

/**
 *
 * @param itemRoot
 * @param num {number}
 * @constructor
 */
wumvi.TrashMistake.Form = function (itemRoot, num) {
    /** @type {!jQuery} */
    this.$itemRoot = jQuery(itemRoot);

    /** @type {string} */
    this.id = this.$itemRoot.data('id');

    /**
     * @type {number}
     */
    this.num = num;

    /** @type {string} */
    this.regexp = this.$itemRoot.data('regexp');

    /** @type {!jQuery} */
    this.$input = this.$itemRoot.find('input[name="text"]');

    //this.$btnCheck = this.$itemRoot.find('.item-btn.check');
    //this.$btnAnswer = this.$itemRoot.find('.item-btn.answer');

    // this.$itemRoot.find('.box').perfectScrollbar();

    this.RIGHT_ANSWER_CLASS = 'right-answer';
    this.WRONG_ANSWER_CLASS = 'wrong-answer';

    /** @type {string} */
    //this.SHOW_ANSWER_BTN_CLASS = 'show-answer-btn';

    //this.SHOW_ANSWER_CLASS = 'show-block-1';

    //this.STATUS_QUESTION = 'question';
    //this.STATUS_ANSWER = 'answer';

    //this.status = this.STATUS_QUESTION;

    this.attachEvent();
};

wumvi.TrashMistake.Form.EVENT_ITEM_SHOW = 'itemShow';
wumvi.TrashMistake.Form.EVENT_WRONG_ANSWER = 'wrongAnswer';
wumvi.TrashMistake.Form.EVENT_RIGHT_ANSWER = 'rightAnswer';

wumvi.TrashMistake.Form.prototype.show = function () {
    this.$itemRoot.addClass('show');
};

wumvi.TrashMistake.Form.prototype.check = function () {
    var val = this.$input.val().trim();
    return (new RegExp(this.regexp)).test(val);
};

wumvi.TrashMistake.Form.prototype.setWrongState = function () {
    this.$itemRoot.removeClass(this.RIGHT_ANSWER_CLASS).addClass(this.WRONG_ANSWER_CLASS);
};

wumvi.TrashMistake.Form.prototype.setRightState = function () {

    this.$itemRoot.removeClass(this.WRONG_ANSWER_CLASS).addClass(this.RIGHT_ANSWER_CLASS);
};

//wumvi.TrashMistake.Form.prototype.checkBtnClick = function () {
//    var val = this.$input.val().trim();
//    if ((new RegExp(this.regexp)).test(val)) {
//        this.$itemRoot.removeClass(this.WRONG_ANSWER_CLASS).addClass(this.RIGHT_ANSWER_CLASS);
//    } else {
//        this.$itemRoot.removeClass(this.RIGHT_ANSWER_CLASS).addClass(this.WRONG_ANSWER_CLASS);
//    }
//
//    this.$itemRoot.addClass(this.SHOW_ANSWER_BTN_CLASS);
//    return false;
//};

wumvi.TrashMistake.Form.prototype.attachEvent = function () {
    var that = this;

    /*jQuery(document).on(wumvi.TrashMistake.Form.EVENT_ITEM_SHOW, function(event, data) {
        console.log(event, data);
    });*/


    //this.$btnCheck.click(function () {
    //    return that.checkBtnClick();
    //});
    //
    //this.$btnAnswer.click(function () {
    //    return that.answerBtnClick();
    //});

    //this.$itemRoot.find('.close').click(function () {
    //    return that.closeAnswerBtnClick();
    //});
};

//wumvi.TrashMistake.Form.prototype.closeAnswerBtnClick = function () {
//    this.status = this.STATUS_QUESTION;
//    this.$itemRoot.removeClass(this.SHOW_ANSWER_CLASS);
//    this.switchShowAnswerText();
//    return false;
//};
//
//wumvi.TrashMistake.Form.prototype.answerBtnClick = function () {
//    if (this.status == this.STATUS_ANSWER) {
//        this.closeAnswerBtnClick();
//        return false;
//    }
//
//    this.status = this.STATUS_ANSWER;
//    this.$itemRoot.addClass(this.SHOW_ANSWER_CLASS);
//    this.switchShowAnswerText();
//    return false;
//};

//wumvi.TrashMistake.Form.prototype.switchShowAnswerText = function() {
//    var text = this.$btnAnswer.data('text');
//    var oldtext = this.$btnAnswer.html();
//    this.$btnAnswer.data('text', oldtext);
//    this.$btnAnswer.html(text);
//};

wumvi.TrashMistake.Form.Event = function(){
    this.id_ = 0;
};

wumvi.TrashMistake.Form.Event.prototype.setId = function(id) {
    this.id_ = id;
};

wumvi.TrashMistake.Form.Event.prototype.getId = function() {
    return this.id_;
};

/**
 *
 * @param data {wumvi.TrashMistake.Form.Event}
 * @private
 */
ProgressRound.prototype.setState_ = function(data, type) {
    var num = data.getId();
    jQuery('#' + this.ID_ITEM_PROGRESS + num).removeClass('wrong right').addClass(type);
};

ProgressRound.prototype.initEventParent_ = ProgressRound.prototype.initEvent_;
ProgressRound.prototype.initEvent_ = function(){
    var that = this;
    ProgressRound.prototype.initEventParent_();

    jQuery(document).on(wumvi.TrashMistake.Form.EVENT_WRONG_ANSWER, function(event, data) {
        that.setState_(data, 'wrong');
    });

    jQuery(document).on(wumvi.TrashMistake.Form.EVENT_RIGHT_ANSWER, function(event, data) {
        that.setState_(data, 'right');
    });
};

new wumvi.TrashMistake();