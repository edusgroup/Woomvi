

var questionList = {};

questionList['be'] = [];
questionList['be'].push('Все мутации be');
questionList['be'].push('Мутация be в будущем времени с привязкой к местоимениям');
questionList['be'].push('Мутации be по временам');
questionList['be'].push('Мутации have в прошедшем времени с привязкой к местоимениям');
questionList['be'].push('Что мы выражаем с помощью be');
questionList['be'].push('Что мы выражаем с помощью have');
questionList['be'].push('Мутации be в настоящем времени с привязкой к местоимениям');
questionList['be'].push('Мутации have по временам');
questionList['be'].push('Мутации be в прошедшем времени с привязкой к местоимениям');
questionList['be'].push('Все мутации have');
questionList['be'].push('Мутация be в будущем времени с привязкой к местоимениям');
questionList['be'].push('Мутации have в настоящем времени с привязкой к местоимениям');
questionList['be'].push('Конец');

questionList['past'] = [];
questionList['past'].push('Past Simple. Когда используем');
questionList['past'].push('Past Simple. Как образуется');
questionList['past'].push('Past continuous. Когда используем');
questionList['past'].push('Past continuous. Как образуется');
questionList['past'].push('Past continuous. Мутации относительно объекта, который выполняет действие.');
questionList['past'].push('Past perfect. Когда используем');
questionList['past'].push('Past perfect. Как образуется');
questionList['past'].push('Past perfect. Как образуется');
questionList['past'].push('Конец');


var pendulumLogic = (function(){
    var intervalHandle = null;
    var questionNum = 0;
    var questionId = 'be';
    var $pendulumBox = null;
    var flag = true;

    var START_BTN_ON = 'Начать';
    var START_BTN_RE = 'Сначала';
    var INTERVAL_TIME = 4000;

    function intervalFunc(){
        var text = questionList[questionId][questionNum];
        $pendulumBox.toggleClass('hide').html(text);

        flag = !flag;
        if ( flag ) {
            questionNum++;
        }

        if ( questionNum == questionList[questionId].length ){
            clearInterval(intervalHandle);
            intervalHandle = null;
            $pendulumBox.addClass('hide');
        } // if
        // func. intervalFunc
    }

    function pendulumStartClick(){
        if ( intervalHandle != null ){
            clearInterval(intervalHandle);
            questionNum = 0;
            $pendulumBox.removeClass('hide');
        } // if

        intervalHandle = setInterval(intervalFunc, INTERVAL_TIME);
        jQuery('#pendulumStart').html(START_BTN_RE);
        questionNum = 0;
        $pendulumBox.addClass('hide').html(questionList[questionId][questionNum]);

        flag = false;
        return false;
        // func. pendulumStartClick
    }

    function questionIdChange(pEvent){
        questionId = jQuery(pEvent.target).val();
        if ( intervalHandle != null ){
            clearInterval(intervalHandle);
            questionNum = 0;
            $pendulumBox.addClass('hide').html('Нажмите "Начать"');
        }
        // func. questionIdChange
    }

    function init(){
        $pendulumBox = jQuery('#pendulum .round:first');
        jQuery('#pendulumStart').click(pendulumStartClick).html(START_BTN_ON);
        jQuery('#questionId').change(questionIdChange);

        // func. init
    }

    init();
})();