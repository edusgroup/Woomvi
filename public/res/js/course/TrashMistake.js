window.wumvi = window.wumvi || {};
var coreUtils = new wumvi.Utils();
coreUtils.initSelectTextInInput();

wumvi.TrashMistake = function () {
    this.$root = jQuery('#wumvi-trash-mistake');
    this.$root.find('.item').each(function (num, obj) {
        new wumvi.TrashMistake.Form(obj);
    });
};

wumvi.TrashMistake.Form = function (itemRoot) {
    /** @type {jQuery} */
    this.$itemRoot = jQuery(itemRoot);
    /** @type {string} */
    this.id = this.$itemRoot.data('id');
    /** @type {string} */
    this.regexp = this.$itemRoot.data('regexp');

    /** @type {jQuery} */
    this.$input = this.$itemRoot.find('input[name="text"]');

    this.$btnCheck = this.$itemRoot.find('.item-btn.check');
    this.$btnAnswer = this.$itemRoot.find('.item-btn.answer');

    this.$itemRoot.find('.box').perfectScrollbar();

    this.RIGHT_ANSWER_CLASS = 'right-answer';
    this.WRONG_ANSWER_CLASS = 'wrong-answer';

    /** @type {string} */
    this.SHOW_ANSWER_BTN_CLASS = 'show-answer-btn';

    this.SHOW_ANSWER_CLASS = 'show-block-1';

    this.STATUS_QUESTION = 'question';
    this.STATUS_ANSWER = 'answer';

    this.status = this.STATUS_QUESTION;

    this.attachEvent();
};

wumvi.TrashMistake.Form.prototype.checkBtnClick = function () {
    var val = this.$input.val().trim();
    if ((new RegExp(this.regexp)).test(val)) {
        this.$itemRoot.removeClass(this.WRONG_ANSWER_CLASS).addClass(this.RIGHT_ANSWER_CLASS);
    } else {
        this.$itemRoot.removeClass(this.RIGHT_ANSWER_CLASS).addClass(this.WRONG_ANSWER_CLASS);
    }

    this.$itemRoot.addClass(this.SHOW_ANSWER_BTN_CLASS);
    return false;
};

wumvi.TrashMistake.Form.prototype.attachEvent = function () {
    var that = this;
    this.$btnCheck.click(function () {
        return that.checkBtnClick();
    });

    this.$btnAnswer.click(function () {
        return that.answerBtnClick();
    });

    //this.$itemRoot.find('.close').click(function () {
    //    return that.closeAnswerBtnClick();
    //});
};

wumvi.TrashMistake.Form.prototype.closeAnswerBtnClick = function () {
    this.status = this.STATUS_QUESTION;
    this.$itemRoot.removeClass(this.SHOW_ANSWER_CLASS);
    this.switchShowAnswerText();
    return false;
};

wumvi.TrashMistake.Form.prototype.answerBtnClick = function () {
    if (this.status == this.STATUS_ANSWER) {
        this.closeAnswerBtnClick();
        return false;
    }

    this.status = this.STATUS_ANSWER;
    this.$itemRoot.addClass(this.SHOW_ANSWER_CLASS);
    this.switchShowAnswerText();
    return false;
};

wumvi.TrashMistake.Form.prototype.switchShowAnswerText = function() {
    var text = this.$btnAnswer.data('text');
    var oldtext = this.$btnAnswer.html();
    this.$btnAnswer.data('text', oldtext);
    this.$btnAnswer.html(text);
};

new wumvi.TrashMistake();