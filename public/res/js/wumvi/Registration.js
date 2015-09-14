"use strict";

wumvi = window.wumvi || {};

wumvi.Registration = function () {
    this.$emailInput = jQuery("input[name=\"login\"]");
    this.$pwdInput = jQuery("input[name=\"pwd\"]");
    this.$nameInput = jQuery("input[name=\"name\"]");

    this.$loginBtn = jQuery("a.submit");
    //this.$registrBtn = jQuery("#registration");
    this.$forgotPwdBtn = jQuery("#forgot-pwd");

    this.$preloaderBox = jQuery("#login-box .preloader");

    this.validation = new wumvi.Validation();

    this.init();
};

wumvi.Registration.prototype.init = function () {
    this.validation.add(
        "name",
        jQuery("#login-box input[name=\"name\"]"),
        /^[^\s]{2,}/,
        wLangRes["name-more-length"]
    );

    this.validation.add(
        "login",
        jQuery("#login-box input[name=\"login\"]"),
        this.validation.EMAIL_REGEXP,
        wLangRes["bad-email-format"]
    );

    this.validation.add(
        "pwd",
        jQuery("#login-box input[name=\"pwd\"]"),
        /^[^\s]{2,}/,
        wLangRes["pwd-more-length"]
    );

    this.initEvent();
};

wumvi.Registration.prototype.initEvent = function () {
    var that = this;

    this.$forgotPwdBtn.click(function(){
        alert(wLangRes["in-development"]);
        return false;
    });

    this.$loginBtn.click(function(){
        return that.loginBtnClick();
    });
};

wumvi.Registration.prototype.loginBtnClick = function(){
    var that = this;

    if (!this.validation.isValid()) {
        return false;
    }

    var login = this.$emailInput.val().trim();
    var pwd = this.$pwdInput.val().trim();
    var name = this.$nameInput.val().trim();

    var url = jQuery("#urlList").data("registration-url-ajax");
    var data = {"login": login, "pwd": pwd, "name": name};

    this.$preloaderBox.show();

    jQuery.ajax({
        method: "POST",
        dataType: "json",
        url: url,
        data: data
    }).done(function(data, textStatus, jqXHR){
        that.onRegistrationDone(data, textStatus, jqXHR);
    }).fail(function(jqXHR, textStatus, errorThrown){
        alert(wLangRes["someError"]);
    }).always(function(){
        that.$preloaderBox.hide();
    });
};

wumvi.Registration.prototype.onRegistrationDone = function(data, textStatus, jqXHR){
    if (data["$ret"] !== 1) {
        alert(wLangRes[data["$msg"]] ? wLangRes[data["$msg"]] : data["$msg"]);
        return;
    }

    //data["email"]
    jQuery("#maincontent .login-form-box").hide();
    jQuery("#maincontent .email-result-box").show();
};

