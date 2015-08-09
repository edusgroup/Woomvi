/**
 *  **************** PENDULUM **********************************************************
 */
window.wumvi = window.wumvi || {};

wumvi.Pendulum = function () {

    this.$pendulumBox = jQuery('#wumvi-pendulum');

    // Кнопка старта
    this.$startPageBtn = this.$pendulumBox.find('.start-page-btn:first');

    // Первый экран
    this.$startPageBox = jQuery('#wumvi-pendulum-start-screen');

    // Экран тестирования
    this.$testingBox = jQuery('#wumvi-pendulum-testing');
    this.$questionBox = this.$testingBox.find('.questionBox')

    this.showQuestionBox = false;
    this.textNum = 0;

    this.progressSquare = new ProgressRound('#progressSquare', {});
    this.progressRound = new ProgressRound('#progressRound', {});

    this.QUESTION_CLASS = 'question-type';
    this.ANSWER_CLASS = 'answer-type';
    this.CAKE_CLASS = 'cake-type';

    this.countDown = 0;
    this.intervalHandle = null;

    this.init_();
};

wumvi.Pendulum.prototype.init_ = function(){
    this.initEvent_();
};

wumvi.Pendulum.prototype.initEvent_ = function(){
    var that = this;
    this.$startPageBtn.click(function(){
        return that.onStartPageBtnClick();
    });


    that.onStartPageBtnClick();
};

wumvi.Pendulum.prototype.onTimer = function() {
    var that = this;
    if (this.textNum == wumvi.storage.pendulumList.length) {
        this.$testingBox.removeClass(this.QUESTION_CLASS);
        clearInterval(this.intervalHandle)
        //$questionBox.removeClass('block text').addClass('hide');
        //$testingBox.hide();
        //$endBox.css({'display': 'table'});
        //$startPageBtn.html($startPageBtn.data('text2'));
        //
        //setTimeout(function () {
        //    $endBox.hide();
        //    $startPageBox.show();
        //}, 3000);
        //this.textNum = 0;
        return;
    }

    var time;
    // Если true, значит показываем текст вопроса
    if (this.showQuestionBox) {
        clearInterval(this.intervalHandle)
        that.progressSquare.next();

        var text = '“' + wumvi.storage.pendulumList[this.textNum]['questionText'] + '”';

        this.$questionBox.find('.text').html(text);
        this.$testingBox.addClass(this.QUESTION_CLASS).removeClass(this.ANSWER_CLASS);
        time = wumvi.storage.pendulumList[this.textNum]['timeBegin'];

        setTimeout(function(){
            that.onTimer();
        }, time * 1000);

        this.progressRound.rebuild(time-1);
    } else {
        this.$testingBox.removeClass(this.QUESTION_CLASS).addClass(this.ANSWER_CLASS);

        time = wumvi.storage.pendulumList[this.textNum]['timeEnd'];

        setTimeout(function(){
            that.progressRound.next();
            setTimeout(function(){
                that.onTimer()
            }, 100);
        }, time * 1000);
        this.textNum++;

        this.progressRound.setPosition(0);

        this.intervalHandle = setInterval(function(){
            that.progressRound.next();
        }, 1000);
    }



    this.showQuestionBox = !this.showQuestionBox;
};


/**
* Клик по кнопке старта маятника
* @returns {boolean}
*/
wumvi.Pendulum.prototype.onStartPageBtnClick = function() {
    // Скрываем начальный блок
    this.$startPageBox.addClass('hide');
    this.$testingBox.removeClass('hide').addClass();

    this.showQuestionBox = true;
    this.onTimer();
    return false;
};

new wumvi.Pendulum();
