'use strict';
/* global EventDomDispatcher, ScheduleItem */

var wumvi = wumvi || {};
wumvi.schedule = wumvi.schedule || {};
wumvi.schedule.ctrl = (function () {
    var EVENT_BOX_NAME = 'wumvi.module.schedule';

    /**
     * @constructor
     */
    function Schedule() {
        this.$scheduleListBox = jQuery('#schedule-list-box');
        this.isOverdue = this.$scheduleListBox.data('is-overdue');

        this.$firstItemElement = this.$scheduleListBox.find('.schedule-item-js:first');

        this.eventDomDispatcher = new EventDomDispatcher(EVENT_BOX_NAME);

        this.scheduleItem = new ScheduleItem(this.$firstItemElement, this);

        this.TASK_DONE_TYPE = 'task-done-type';
        this.OVERDUE_TYPE = 'overdue-type';
    }

    /**
     * @public
     */
    Schedule.prototype.init = function (params) {
        if (this.isOverdue) {
            this.$firstItemElement.addClass(this.OVERDUE_TYPE);
        }

        this.checkAllTaskDone();

        this.initEvent_();
    };

    /**
     * @private
     */
    Schedule.prototype.initEvent_ = function (params) {

    };

    Schedule.prototype.checkAllTaskDone = function () {
        var count = this.$firstItemElement.find('.checkbox-js:checkbox:not(:checked)').length;
        if (count === 0) {
            this.$firstItemElement.addClass(this.TASK_DONE_TYPE);
        } else {
            this.$firstItemElement.removeClass(this.TASK_DONE_TYPE);
        }
    };

    function init(params) {
        return (new Schedule()).init(params);
    }

    return {
        Schedule: Schedule,
        init: init
    };
})();