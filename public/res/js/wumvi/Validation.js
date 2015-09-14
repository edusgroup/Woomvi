"use strict";

window.wumvi = window.wumvi || {};

wumvi.Validation = function () {
    this.ruleList = [];

    this.WRONG_CLASS = "input-wrong";
    this.WRONG_TEXT_CLASS = "error-msg";
    this.EMAIL_REGEXP = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/;

    this.init();
};

wumvi.Validation.prototype.setError = function (rule) {
    rule.$obj.parent().addClass(this.WRONG_CLASS);
    var $parent = rule.$obj.parent();
    var $errorBox = $parent.find("." + this.WRONG_TEXT_CLASS + ":first");
    if ($errorBox.length === 0) {
        var div = document.createElement("div");
        div.innerHTML = rule.msg;
        jQuery(div).addClass(this.WRONG_TEXT_CLASS);
        $parent.append(div);
    } else {
        $errorBox.html(rule.msg);
    }
};

wumvi.Validation.prototype.removeError = function (rule) {
    var $parent = rule.$obj.parent();
    $parent.removeClass(this.WRONG_CLASS);
};

wumvi.Validation.prototype.isValid = function () {
    var that = this;
    var globalFlag = true;
    jQuery.each(this.ruleList, function (i, rule) {
        var flag = true;
        if (typeof rule.rule === "function") {
            if (!rule.rule(rule)) {
                that.setError(rule);
                globalFlag = flag = false;
            }
        } else {
            if (!rule.rule.test(rule.$obj.val().trim())) {
                that.setError(rule);
                globalFlag = flag = false;
            }
        }

        if (flag) {
            that.removeError(rule);
        }
    });

    return globalFlag;
};

wumvi.Validation.prototype.init = function () {

};

/**
 *
 * @param {string} name
 * @param {!jQuery} $obj
 * @param {Function|RegExp} rule
 * @param {string} msg
 */
wumvi.Validation.prototype.add = function (name, $obj, rule, msg) {
    if (typeof name !== "string") {
        throw Error("name must be string");
    }

    if (!($obj instanceof jQuery)) {
        throw Error("$obj must be jQuery instance");
    }

    if (!(typeof rule === "function" || rule instanceof RegExp)) {
        throw Error("rule must be function or RegExp instance");
    }

    if (typeof msg !== "string") {
        throw Error("msg must be string");
    }

    this.ruleList.push({
        $obj: $obj,
        rule: rule,
        msg: msg
    });
};