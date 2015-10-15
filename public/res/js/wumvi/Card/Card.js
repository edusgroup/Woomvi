'use strict';
/* global EventDomDispatcher */

var wumvi = wumvi || {};
wumvi.card = (function () {
    var EVENT_BOX_NAME = 'wumvi.card';
    var EVENT_CARD_ITEM_PLAYED_VOICE = 'wumvi.card.item.played.voice';
    var EVENT_CARD_ITEM_SWITCH_TRANSLATE = 'wumvi.card.item.switch.translate';

    /**
     *
     * @constructor
     */
    function SentenseList() {
        var that = this;

        this.$root = jQuery('#sent-item-list');

        this.eventDispatcher = new EventDomDispatcher(EVENT_BOX_NAME);

        this.sentItemBoxList = [];
        this.$root.find('.sent-item-box-js').each(function () {
            that.sentItemBoxList.push(new SentenseList.Item(jQuery(this), that.eventDispatcher, that));
        });

        /**
         * Шаблон URL с ресурсами mp3 и oga
         * @type {string}
         */
        this.resTplUrl = /** @type {string} */ (this.$root.data('resurl'));
    }

    SentenseList.prototype = Object.create(wumvi.ContentSlideBox.prototype);

    SentenseList.prototype.init = function (params) {
        if (this.sentItemBoxList.length === 0) {
            return;
        }

        this.initSound('#player-box');

        //this.eventDispatcher.addListener(EVENT_CARD_ITEM_PLAYED_VOICE, function () {
        //
        //});

        this.initGrid();
        this.initEvent();
    };

    //SentenseList.prototype.

    SentenseList.prototype.initEvent = function () {
        var that = this;

        /*this.$playSound.click(function () {
         that.playSound_(that.resTplUrl, that.currentItem);
         return false;
         }); */
    };

    SentenseList.prototype.playSentense = function (currentItem) {
        this.playSound_(this.resTplUrl, currentItem);
    };

    SentenseList.prototype.initGrid = function () {
        var containerWidth = this.$root.width();
        var itemWidth = this.sentItemBoxList[0].getWidth();
        var count = Math.round(containerWidth / itemWidth - 0.5, 0);

        jQuery.each(this.sentItemBoxList, function (index, sentItemBox) {
            if (index % count === 0) {
                sentItemBox.setEndPointOfGrid();
            }
        });
    };

    /**
     * @constructor
     */
    SentenseList.Item = function ($root, eventDispatcher, parentCtrl) {
        this.$root = $root;
        this.eventDispatcher = eventDispatcher;
        this.parentCtrl = parentCtrl;

        this.id = this.$root.data('id');
        this.isShowTranslate = true;

        this.init();
    };

    SentenseList.Item.prototype.init = function () {
        this.initEvent();
    };

    SentenseList.Item.prototype.initEvent = function () {
        var that = this;

        this.$root.find('.play-sound-js').click(function () {
            that.eventDispatcher.emit(EVENT_CARD_ITEM_PLAYED_VOICE, {
                name: that.id
            });
            that.parentCtrl.playSentense(that.id);
        });

        this.$root.find('.translate-word-js').click(function () {
            that.switchTranslateMode();
        });
    };

    SentenseList.Item.prototype.switchTranslateMode = function () {
        this.isShowTranslate = !this.isShowTranslate;
        this.eventDispatcher.emit(EVENT_CARD_ITEM_SWITCH_TRANSLATE, {
            name: this.id,
            mode: this.isShowTranslate
        });

        this.$root.toggleClass('foreign-show-mode');
    };

    SentenseList.Item.prototype.setEndPointOfGrid = function () {
        this.$root.addClass('end-point-of-grid');
    };

    SentenseList.Item.prototype.getWidth = function () {
        return this.$root.width();
    };

    function init(params) {
        return (new SentenseList()).init(params);
    }

    return {
        init: init
    };
})();
