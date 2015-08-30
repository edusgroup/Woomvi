"use strict";
/* global */

wumvi = window.wumvi || {};

/**
 *
 * @constructor
 */
wumvi.Card = function () {
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
    this.formList = {};


    this.$playSound = jQuery("#playSound");

    /**
     * Кнопка, показать перевод
     * @type {!jQuery}
     */
    this.$showTranslate = jQuery("#showTranslate");

    /**
     * Показывать ли перевод
     * @type {boolean}
     */
    this.showTraslateMode = false;

    /**
     * Текущий выделенная карточка
     * @type {string}
     */
    this.currentItem = "";

    this.init_();
};

wumvi.Card.prototype = Object.create(wumvi.ContentSlideBox.prototype);

wumvi.Card.prototype.init_ = function(){
    var that = this;


    var firstKey = "";

    this.$root.find(".item-card").each(function (num, obj) {
        var id = /** @type {string} */ (jQuery(obj).data("id"));
        if (firstKey === "") {
            firstKey = id;
        }

        that.formList[id] = new wumvi.Card.Form(jQuery(obj), id);
    });

    this.initSound("#player-box");

    this.showCard(firstKey);

    this.initEvent_();
};


/**
 *
 * @private
 */
wumvi.Card.prototype.initEvent_ = function(){
    var that = this;

    this.$wordList.click(function(){
        that.onWordInListClick_(jQuery(this));
    });

    this.$playSound.click(function () {
        that.playSound_(that.resTplUrl, that.currentItem);
        return false;
    });

    this.$showTranslate.click(function () {
        that.switchAnswerMode();
        return false;
    });
};

//wumvi.Card.EVENT_WORD_SELECT = "word-select";

wumvi.Card.prototype.showCard = function(cardId){
    this.currentItem = cardId;

    this.$root.find(".item-card").removeClass("show");
    jQuery("#card-" + cardId).addClass("show");

    this.$wordList.removeClass("select");
    this.$wordList.filter("[data-id=\"" + cardId + "\"]").addClass("select");
};

wumvi.Card.prototype.onWordInListClick_ = function($obj){
    if (this.showTraslateMode) {
        this.switchLabelBtn_(this.$showTranslate);
    }
    this.showTraslateMode = false;
    this.formList[this.currentItem].switchAnswerMode(false);

    var cardId = /** @type {string} */ ($obj.data("id"));
    this.showCard(cardId);
};

wumvi.Card.prototype.switchAnswerMode = function () {
    this.showTraslateMode = !this.showTraslateMode;
    this.formList[this.currentItem].switchAnswerMode(this.showTraslateMode);
    this.switchLabelBtn_(this.$showTranslate);
};