"use strict";
/* global */

window.wumvi = window.wumvi || {};

/**
 *
 * @constructor
 */
wumvi.AbstractBook = function(){
    /** @type {jQuery} */
    this.$root = jQuery("#absBox");
    /** @type {wumvi.ModalWindow} */
    this.windowModal = new wumvi.ModalWindow(jQuery("#modal"), "main");

    this.attachEvent();
};

wumvi.AbstractBook.prototype.attachEvent = function() {
    var that = this;
    this.$root.find(".phrase").each(function(num, obj){
        var model = new wumvi.AbstractBook.Phrase.Model(obj);
        var phrase = new wumvi.AbstractBook.Phrase(obj, model);
    });
};

/* ------------------------------ PHRASE ---------------------------------- */
wumvi.AbstractBook.Phrase = function(obj, model){
    this.obj = obj;
    /** @type {wumvi.AbstractBook.Phrase.Model}*/
    this.model = model;

    this.attachEvent();

};

wumvi.AbstractBook.Phrase.prototype.attachEvent = function(){
    var that = this;
    jQuery(this.obj).click(function(){
        var html = jQuery("#" + that.model.id).html();
        jQuery(document).trigger(wumvi.ModalWindow.SHOW, ["main", html]);
    });
};

/* ------------------------------- MODEL ----------------------------------- */
wumvi.AbstractBook.Phrase.Model = function(obj){
    /** @type {string} ID фразы*/
    this.id = jQuery(obj).data("id");
};

wumvi.ModalWindow = function($obj, name) {
    /** @type {jQuery} */
    this.$obj = $obj;
    /** @type {string} */
    this.name = name;

    this.attachEvent();
};



wumvi.ModalWindow.prototype.attachEvent = function(){
    var that = this;
    this.$obj.find(".close:first").click(function(){
        that.hide();
    });

    jQuery(document).on(wumvi.ModalWindow.SHOW, function(event, name, html) {
        if (that.name !== name) {
            return;
        }

        if (html) {
            that.$obj.find(".text:first").html(html);
        }

        that.show();
    });
};

wumvi.ModalWindow.prototype.show = function() {

    this.$obj.addClass("show");
    jQuery("#modal").addClass("show");
};

wumvi.ModalWindow.prototype.hide = function() {
    this.$obj.removeClass("show");
};

wumvi.ModalWindow.SHOW = "wumvi.modal.show";
