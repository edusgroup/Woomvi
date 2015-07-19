wumvi = window.wumvi || {};

wumvi.Login = function () {
    var that = this;

    this.$emailInput = jQuery('input[name="login"]');
    this.$pwdInput = jQuery('input[name="pwd"]');
    this.$loginBtn = jQuery('a.submit');
    //this.$registrBtn = jQuery('#registration');
    this.$forgotPwdBtn = jQuery('#forgot-pwd');

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

    var login = this.$emailInput.val().trim();
    if (login == ''){
        alert('Введите логин');
        return false;
    }

    var pwd = this.$pwdInput.val().trim();
    if (pwd == ''){
        alert('Введите пароль');
        return false;
    }

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

    });

};

wumvi.Login.prototype.onAuthDone = function(data, textStatus, jqXHR){
    console.log(data);

    var urlFrom = jQuery('#userData').data('url-from');
    if (urlFrom != '') {
        window.location.href = urlFrom;
    } else {
        window.location.href = '/';
    }
};

new wumvi.Login();