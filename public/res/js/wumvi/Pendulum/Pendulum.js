'use strict';
/* global ProgressRound */
/**
 *  **************** PENDULUM **********************************************************
 */
var wumvi = wumvi || {};

var slova = [
    'Меньше задумываемся!',
    'Чемпион!',
    'Чуть быстрее!',
    'Выкладываемся на все 120%',
    'Быстрее!',
    'Резче!',
    'Меньше мямлить!',
    'Чеканим каждое слово!',
    'Работаем над произношением!',
    'Внятность - сестра английского!',
    'Я не говорила отдыхать!'
];

wumvi.Pendulum = function () {

    this.$pendulumBox = jQuery('#wumvi-pendulum');

    // Кнопка старта
    this.$startPageBtn = this.$pendulumBox.find('.start-page-btn-js:first');

    // Первый экран
    this.$startPageBox = jQuery('#wumvi-pendulum-start-screen');

    // Экран тестирования
    this.$testingBox = jQuery('#wumvi-pendulum-testing');
    this.$questionBox = this.$testingBox.find('.question-box-js');
    this.$goodJobBox = jQuery('.wumvi-pendulum-end');

    this.showQuestionBox = false;
    this.textNum = 0;

    this.progressSquare = new ProgressRound('#progressSquare', {});

    this.QUESTION_CLASS = 'question-type';
    this.ANSWER_CLASS = 'answer-type';
    this.GOOD_JOB_TYPE = 'good-job-type';

    this.countDownImgSrc = '/res/img/';

    this.$countDownImg = this.$testingBox.find('.count-down-img-js');
    this.countDownSrcTpl = this.$countDownImg.data('src');

    this.intervalHandle = null;

    this.init_();
};

wumvi.Pendulum.prototype.init_ = function () {
    for (var num = 1; num <= 6; num += 1) {
        var img = document.createElement('img');
        img.src = this.countDownSrcTpl.replace('{num}', num);
    }

    this.initEvent_();
};

wumvi.Pendulum.prototype.initEvent_ = function () {
    var that = this;
    this.$startPageBtn.click(function () {
        return that.onStartPageBtnClick();
    });
};

wumvi.Pendulum.prototype.onTimer = function () {
    var that = this;

    // Маятник закончился
    if (this.textNum === wumvi.storage.pendulumList.length) {
        this.$testingBox.removeClass(this.QUESTION_CLASS);
        clearInterval(this.intervalHandle);
        this.$goodJobBox.addClass('show');
        this.$testingBox.removeClass('show');

        this.$startPageBtn.html(this.$startPageBtn.data('text2'));

        setTimeout(function () {
            that.$startPageBox.addClass('show');
            that.$goodJobBox.removeClass('show');
        }, 3000);

        this.textNum = 0;
        return;
    }

    var time;
    // Если true, значит показываем текст вопроса
    if (this.showQuestionBox) {
        clearInterval(this.intervalHandle);
        that.progressSquare.next();

        var text = '“' + wumvi.storage.pendulumList[this.textNum].questionText + '”';

        this.$questionBox.find('.text').html(text);
        this.$testingBox.addClass(this.QUESTION_CLASS).removeClass(this.ANSWER_CLASS);
        time = wumvi.storage.pendulumList[this.textNum].timeBegin;

        setTimeout(function () {
            that.onTimer();
        }, time * 1000);
    } else {
        this.$testingBox.removeClass(this.QUESTION_CLASS).addClass(this.ANSWER_CLASS);
        time = wumvi.storage.pendulumList[this.textNum].timeEnd;

        var num = Math.floor(Math.random() * (slova.length)) + 0;
        jQuery('.motivation-words-js').html(slova[num]);
        this.$countDownImg.attr('src', this.countDownSrcTpl.replace('{num}', time));

        setTimeout(function () {
            that.onTimer();
        }, time * 1000);
        this.textNum += 1;
    }

    this.showQuestionBox = !this.showQuestionBox;
};


/**
 * Клик по кнопке старта маятника
 * @returns {boolean}
 */
wumvi.Pendulum.prototype.onStartPageBtnClick = function () {
    // Скрываем начальный блок
    this.$startPageBox.removeClass('show');
    this.$testingBox.addClass('show');

    this.showQuestionBox = true;
    this.onTimer();
    return false;
};