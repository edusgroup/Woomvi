wumvi = window.wumvi || {};

wumvi.Login = function () {
    var that = this;

    this.$emailInput = jQuery('input[name="login"]');
    this.$pwdInput = jQuery('input[name="pwd"]');
    this.$loginBtn = jQuery('a.submit');
    //this.$registrBtn = jQuery('#registration');
    this.$forgotPwdBtn = jQuery('#forgot-pwd');

    this.$preloaderBox = jQuery('#login-box .preloader');
    this.$warningErrorBox = jQuery('.warning-box.error:first');

    this.init();
};

wumvi.Login.prototype.init = function () {
    this.initEvent();
};

wumvi.Login.prototype.initEvent = function () {
    var that = this;

    this.$forgotPwdBtn.click(function(){
        alert('В процессе разработки');
        return false;
    });

    this.$loginBtn.click(function(){
        return that.loginBtnClick();
    });

};

wumvi.Login.prototype.loginBtnClick = function(){
    var that = this;

    this.$warningErrorBox.hide();

    var login = this.$emailInput.val().trim();
    if (login == ''){
        this.$warningErrorBox.show().html(wLangRes['enter-login']);
        return false;
    }

    var pwd = this.$pwdInput.val().trim();
    if (pwd == ''){
        this.$warningErrorBox.show().html(wLangRes['enter-pwd']);
        return false;
    }

    that.$preloaderBox.show();


    var url = jQuery('#urlList').data('login-url-ajax');
    var data = {'login': login, 'pwd': pwd};

    jQuery.ajax({
        method: "POST",
        dataType: 'json',
        url: url,
        data: data
    }).done(function(data, textStatus, jqXHR){
        that.onAuthDone(data, textStatus, jqXHR);
    }).fail(function(jqXHR, textStatus, errorThrown){
        that.$warningErrorBox.show().html(wLangRes['someError']);
    }).always(function(){
        that.$preloaderBox.hide();
    });

};

wumvi.Login.prototype.onAuthDone = function(data, textStatus, jqXHR){
    var urlFrom = jQuery('#userData').data('url-from');
    if (urlFrom != '') {
        window.location.href = urlFrom;
    } else {
        window.location.href = '/';
    }
};

new wumvi.Login();