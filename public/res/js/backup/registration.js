function RegistrationLogic(){

    var $registrationBox = null;

    function regBtnClick() {
        var data = {};
        data['name'] = $registrationBox.find('input[name="name"]:first').val();
        data['email'] = $registrationBox.find('input[name="email"]:first').val();

        jQuery.post('/ajax/registration/', data, onRegistrationSuccess, 'json')
            .fail(onRegistrationFail);
        return false;
    }

    this.init = function() {
        jQuery('#regBtn').click(regBtnClick);
        $registrationBox = jQuery('#registrationBox');
    }

    function onRegistrationSuccess(response) {
        if (response.status != 'ok') {
            alert(response.msg);
            return;
        }
        $registrationBox.addClass('reg-ok');
    }

    function onRegistrationFail(pData) {
        console.log('e', pData);
    }
}

var registrationLogic = new RegistrationLogic();
registrationLogic.init();

