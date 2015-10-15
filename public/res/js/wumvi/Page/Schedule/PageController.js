'use strict';
/* global EventDomDispatcher, wumvi.Utils */

var wumvi = wumvi || {};
wumvi.wordspeed = wumvi.wordspeed || {};

/**
 * @depends wumvi.Utils
 *
 * @type {{PageController, init}}
 */
wumvi.schedule.controller = (function () {
    var EVENT_SCHEDULE_BOX_NAME = 'wumvi.module.schedule';
    var EVENT_ON_SCHEDULE_ITEM_CHANGE_STATUS = 'schedule.item.change-status';

    /**
     * @constructor
     */
    function PageController() {
        this.eventDispatcher = new EventDomDispatcher(EVENT_SCHEDULE_BOX_NAME);

        this.urlList = jQuery('#urlList').data('list');
    }

    PageController.prototype.init = function (params) {
        this.initEvent_();
    };

    PageController.prototype.initEvent_ = function (params) {
        var that = this;

        this.eventDispatcher.addListener(EVENT_ON_SCHEDULE_ITEM_CHANGE_STATUS, function (event, data) {
            that.taskDone_(data.id, data.status);
        });
    };

    PageController.prototype.taskDone_ = function (id, status) {
        var url = this.urlList['task-status'];
        jQuery.post(url, {
            'id': id,
            'status': status ? 1 : 0
        });
    };

    function init(params) {
        return (new PageController()).init(params);
    }

    return {
        PageController: PageController,
        init: init
    };
})();