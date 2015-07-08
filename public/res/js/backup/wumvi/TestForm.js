window.wumvi = window.wumvi || {};

wumvi.TestForm = function(rootId){
    this.$root = jQuery(rootId);

    this.$root.find('.item').each(function(num, obj){
        new  wumvi.TestForm.Form(obj);
    });
}

wumvi.TestForm.Form = function(itemRoot){
    /** @type {jQuery} */
    this.$itemRoot = jQuery(itemRoot);
    /** @type {string} */
    this.id = this.$itemRoot.data('id');
    /** @type {jQuery} */
    this.$input = this.$itemRoot.find('input[name="text"]');

    this.SHOW_ANSWER_CLASS = 'show-answer';

    this.initHeight();

    this.attachEvent();
}

wumvi.TestForm.Form.prototype.initHeight = function() {
    var $questionBox = this.$itemRoot.find('.question-box:first');
    var $answerBox = this.$itemRoot.find('.answer-box:first');

    var answerHeight = $answerBox.outerHeight();
    var questionHeight = $questionBox.outerHeight();

    if (questionHeight < answerHeight) {
        this.$itemRoot.css({'height': answerHeight});
    }else{
        this.$itemRoot.css({'height': questionHeight});
    }

    $answerBox.find('.answer-text').addClass('short-height');
}

wumvi.TestForm.Form.prototype.attachEvent = function() {
    var that = this;
    this.$itemRoot.find('.btn.check').click(function(){
        return that.answerBtnClick();
    });

    this.$itemRoot.find('.close').click(function(){
        return that.closeAnswerBtnClick();
    });
}

wumvi.TestForm.Form.prototype.closeAnswerBtnClick = function() {
    this.$itemRoot.removeClass(this.SHOW_ANSWER_CLASS);
    return false;
}

wumvi.TestForm.Form.prototype.answerBtnClick = function() {
    this.$itemRoot.addClass(this.SHOW_ANSWER_CLASS);
    return false;
}