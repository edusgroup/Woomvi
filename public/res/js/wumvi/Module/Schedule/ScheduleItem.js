'use strict';

/**
 *
 * @param {!jQuery} $elem
 * @param {Schedule} controller
 * @constructor
 */
function ScheduleItem($elem, controller) {

    this.EVENT_ITEM_CHANGE_STATUS = 'schedule.item.change-status';

    this.$item = $elem;
    this.controller = controller;

    this.init_();
}

ScheduleItem.prototype.init_ = function () {
    this.initEvent_();
};

ScheduleItem.prototype.initEvent_ = function () {
    var that = this;
    this.$item.find('.checkbox-js').change(function () {
        that.onCheckboxChange_(jQuery(this));
    });
};

ScheduleItem.prototype.onCheckboxChange_ = function ($checkbox) {
    var checked = $checkbox.prop('checked');
    var id = $checkbox.val();
    this.controller.eventDomDispatcher.emit(this.EVENT_ITEM_CHANGE_STATUS, {'status': checked, 'id': id});
    this.controller.checkAllTaskDone();
};