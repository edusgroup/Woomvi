'use strict';
/* global EventDomDispatcher, wumvi.Utils */

var wumvi = wumvi || {};
wumvi.wordspeed = wumvi.wordspeed || {};

/**
 * @depends wumvi.Utils
 *
 * @type {{PageController, init}}
 */
wumvi.wordspeed.controller = (function () {
    var EVENT_WORDSPEED_BOX_NAME = 'wumvi.module.wordspeed';
    var EVENT_WORDSPEED_START = 'wumvi.module.wordspeed.onStart';
    var EVENT_WORDSPEED_ON_STOP = 'wumvi.module.wordspeed.stop';
    var EVENT_WORDSPEED_CHANGE_SPEED = 'wumvi.module.wordspeed.onChangeSpeed';

    /**
     * @constructor
     */
    function PageController() {
        this.$startPageBtn = jQuery('.start-page-btn-js');
        this.$switchWordspeedBeginer = jQuery('#switch-wordspeed-beginer');
        this.$switchWordspeedProfi = jQuery('#switch-wordspeed-profi');

        this.eventDispatcher = new EventDomDispatcher(EVENT_WORDSPEED_BOX_NAME);
        this.utils = new wumvi.Utils();
    }

    PageController.prototype.init = function (params) {
        this.initEvent_();
    };

    PageController.prototype.initEvent_ = function (params) {
        var that = this;

        this.$startPageBtn.click(function () {
            return that.onStartPageBtnClick_();
        });

        this.$switchWordspeedBeginer.change(function () {
            that.eventDispatcher.emit(EVENT_WORDSPEED_CHANGE_SPEED, {'speed': 'beginner'});
        });

        this.$switchWordspeedProfi.change(function () {
            that.eventDispatcher.emit(EVENT_WORDSPEED_CHANGE_SPEED, {'speed': 'profi'});
        });

        this.eventDispatcher.addListener(EVENT_WORDSPEED_ON_STOP, function () {
            that.utils.switchTextOfElement(that.$startPageBtn);
            that.$startPageBtn.show();
        });
    };

    PageController.prototype.onStartPageBtnClick_ = function () {
        this.eventDispatcher.emit(EVENT_WORDSPEED_START);
        this.$startPageBtn.hide();
        return false;
    };

    function init(params) {
        return (new PageController()).init(params);
    }

    return {
        PageController: PageController,
        init: init
    };
})();