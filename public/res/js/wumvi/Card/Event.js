"use strict";

/**
 *
 * @constructor
 */
wumvi.Card.Event = function(){
    this.id_ = "";
};

wumvi.Card.Event.prototype.setId = function (id) {
    this.id_ = id;
};

wumvi.Card.Event.prototype.getId = function () {
    return this.id_;
};