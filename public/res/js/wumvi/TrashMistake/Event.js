"use strict";

wumvi.TrashMistake.Event = (function(){
    /**
     * @constructor
     */
    function Event() {
        this.id_ = 0;
    }

    Event.prototype.setId = function (id) {
        this.id_ = id;
    };

    Event.prototype.getId = function () {
        return this.id_;
    };

    return Event;
})();