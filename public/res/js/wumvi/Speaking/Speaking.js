"use strict";
/* global */

wumvi = window.wumvi || {};

/**
 * @typedef {wumvi.ContentSlideBox} wumvi.Speaking
 * @constructor
 */
wumvi.Speaking = function () {
    /**
     * @type {!jQuery}
     */
    this.$root = jQuery("#wumvi-card-box");
    this.$wordList = this.$root.find(".word-list-box li");

    /**
     * Шаблон URL с ресурсами mp3 и oga
     * @type {string}
     */
    this.resTplUrl = /** @type {string} */ (this.$root.data("resurl"));

    /**
     * Объекты с заданиями
     * @type {Object}
     */
    //this.formList = {};



    /**
     * Кнопка Проиграть запись
     * @type {!jQuery}
     */
    this.$playSound = jQuery("#playSound");

    /**
     * Текущий выделенная карточка
     * @type {string}
     */
    this.currentItem = "";

    this.init_();
};

wumvi.Speaking.prototype = Object.create(wumvi.ContentSlideBox.prototype);

wumvi.Speaking.prototype.init_ = function(){

    this.initSound("#player-box");

    var firstKey = /** @type {string} */ (this.$root.find(".item-card").eq(0).data('id'));
    this.showCard(firstKey);

    this.initEvent_();
};

/**
 *
 * @private
 */
wumvi.Speaking.prototype.initEvent_ = function(){
    var that = this;

    this.$playSound.click(function () {
        that.playSound_(that.resTplUrl, that.currentItem);
        return false;
    });

    this.$wordList.click(function(){
        that.onWordInListClick_(jQuery(this));
    });
};

wumvi.Speaking.prototype.onWordInListClick_ = function($obj){
    var cardId = /** @type {string} */ ($obj.data("id"));
    this.showCard(cardId);
};

wumvi.Speaking.prototype.showCard = function(cardId){
    this.currentItem = cardId;

    this.$root.find(".item-card").removeClass("show");
    jQuery("#speaking-" + cardId).addClass("show");

    this.$wordList.removeClass("select");
    this.$wordList.filter("[data-id=\"" + cardId + "\"]").addClass("select");
};


