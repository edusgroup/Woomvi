'use strict';
/* global */

/**
 *
 * @constructor
 */
function EventDomDispatcher(name, debug) {

    this.debug = debug;

    // Заменяем все не символы, на тире, чтобы не было проблем с селектором у jQuery
    // Если, к примеру, придёт точка, то он начнётся искать класс, а нам это не надо
    var nameClear = name.replace(/\W/g, '-');
    this.fullname = 'event-' + nameClear;
    this.$eventObj = jQuery('#' + this.fullname);
    if (this.$eventObj.length === 0) {
        if (this.debug) {
            console.warn('Object #' + this.fullname + ' not found');
        }
        var elem = document.createElement('div');
        this.$eventObj = jQuery(elem);
        this.$eventObj.attr('id', this.fullname);
        jQuery('body:first').append(elem);
    }
}

EventDomDispatcher.prototype.emit = function (msgName, data) {
    data = data !== undefined ? data : {};
    if (this.debug) {
        console.log('EventDomDispatcher(' + this.fullname + ') emit(' + msgName + ')', data);
    }
    this.$eventObj.trigger(msgName, data);
};

EventDomDispatcher.prototype.addListener = function (msgName, callback) {
    var that = this;
    this.$eventObj.on(msgName, function (event, data) {
        if (that.debug) {
            console.log('EventDomDispatcher(' + that.fullname + ') listen(' + msgName + ')', data);
        }
        callback(event, data);
    });
};
