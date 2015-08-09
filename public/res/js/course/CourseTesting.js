window.wumvi = window.wumvi || {};

wumvi.CourseTesting = function () {
    this.$root = jQuery('#wumvi-question-answer');
    this.$root.find('.item').each(function (num, obj) {
        new wumvi.CourseTesting.Form(obj);
    });
};

wumvi.CourseTesting.Form = function (itemRoot) {
    /** @type {jQuery} */
    this.$itemRoot = jQuery(itemRoot);
    /** @type {string} */
    this.id = this.$itemRoot.data('id');

    this.$btnAnswer = this.$itemRoot.find('.item-btn.answer');

    this.$itemRoot.find('.box').perfectScrollbar();

    this.RIGHT_ANSWER_CLASS = 'right-answer';
    this.WRONG_ANSWER_CLASS = 'wrong-answer';

    this.SHOW_ANSWER_CLASS = 'show-block-1';

    this.STATUS_QUESTION = 'question';
    this.STATUS_ANSWER = 'answer';

    this.status = this.STATUS_QUESTION;

    this.attachEvent();
};

wumvi.CourseTesting.Form.prototype.attachEvent = function () {
    var that = this;

    this.$btnAnswer.click(function () {
        return that.answerBtnClick();
    });

    //this.$itemRoot.find('.close').click(function () {
    //    return that.closeAnswerBtnClick();
    //});
};

wumvi.CourseTesting.Form.prototype.closeAnswerBtnClick = function () {
    this.status = this.STATUS_QUESTION;
    this.$itemRoot.removeClass(this.SHOW_ANSWER_CLASS);
    this.switchShowAnswerText();
    return false;
};

wumvi.CourseTesting.Form.prototype.answerBtnClick = function () {
    if (this.status == this.STATUS_ANSWER) {
        this.closeAnswerBtnClick();
        return false;
    }

    this.status = this.STATUS_ANSWER;
    this.$itemRoot.addClass(this.SHOW_ANSWER_CLASS);
    this.switchShowAnswerText();
    return false;
};

wumvi.CourseTesting.Form.prototype.switchShowAnswerText = function() {
    var text = this.$btnAnswer.data('text');
    var oldtext = this.$btnAnswer.html();
    this.$btnAnswer.data('text', oldtext);
    this.$btnAnswer.html(text);
};

new wumvi.CourseTesting();