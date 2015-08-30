"use strict";

wumvi.TrashMistake.Form = (function(){
    /**
     * @param itemRoot
     * @param num {number}
     * @constructor
     */
    function Form(itemRoot, num) {
        
        /** @type {!jQuery} */
        this.$itemRoot = jQuery(itemRoot);

        /** @type {string} */
        this.id = this.$itemRoot.data("id");

        /**
         * @type {number}
         */
        this.num = num;

        /** @type {string} */
        this.regexp = this.$itemRoot.data("regexp");

        /** @type {!jQuery} */
        this.$input = this.$itemRoot.find("input[name=\"text\"]");

        this.RIGHT_ANSWER_CLASS = "right-answer";
        this.WRONG_ANSWER_CLASS = "wrong-answer";

        this.attachEvent();
    }


    Form.EVENT_ITEM_SHOW = "itemShow";
    Form.EVENT_WRONG_ANSWER = "wrongAnswer";
    Form.EVENT_RIGHT_ANSWER = "rightAnswer";

    Form.prototype.show = function () {
        this.$itemRoot.addClass("show");
    };

    Form.prototype.hide = function () {
        this.$itemRoot.removeClass("show");
    };

    /**
     *
     * @param {boolean} flag
     */
    Form.prototype.switchAnswerMode = function (flag) {
        if (flag) {
            this.$itemRoot.addClass("answer-mode");
        } else {
            this.$itemRoot.removeClass("answer-mode");
        }
    };

    Form.prototype.check = function () {
        var val = this.$input.val().trim();
        return (new RegExp(this.regexp)).test(val);
    };

    Form.prototype.setWrongState = function () {
        this.$itemRoot.removeClass(this.RIGHT_ANSWER_CLASS).addClass(this.WRONG_ANSWER_CLASS);
    };

    Form.prototype.setRightState = function () {
        this.$itemRoot.removeClass(this.WRONG_ANSWER_CLASS).addClass(this.RIGHT_ANSWER_CLASS);
    };

    Form.prototype.attachEvent = function () {
    };

    return Form;
})();