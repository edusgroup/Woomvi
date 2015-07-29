wumvi = window.wumvi || {};

wumvi.Card = function () {
    var that = this;

    this.ITEM_TYPE_SENTENCE = 'sentence';
    this.ITEM_TYPE_TRANSLATE = 'translate';
    this.ITEM_TYPE_WORD = 'word';

    this.$root = jQuery('.item-list:first');

    this.$root.find('.item').each( function (num, obj) {
        new wumvi.Card.ItemModel(jQuery(obj), false, that);
    });

    this.$playerBox = jQuery("#player-box");

    this.$root.find('.box').perfectScrollbar();

    this.$playerBox.jPlayer({
        swfPath: "/res/bower_package/jplayer/dist/jplayer",
        //solution: "flash, html",
        supplied: "mp3, oga",
        wmode: "window",
        useStateClassSkin: true,
        autoBlur: false,
        smoothPlayBar: true,
        keyEnabled: true,
        remainingDuration: true,
        toggleDuration: true
    });

    /*this.$playerBox.bind(jQuery.jPlayer.event.progress, function(event){
     jQuery('.item-btn.play').html(event.jPlayer.status.seekPercent);
     });*/
};


wumvi.Card.ItemModel = function ($cardItem, isWordShow, cardCtrl) {
    this.isWordShow = isWordShow;
    this.$cardItem = $cardItem;
    this.cardCtrl = cardCtrl;

    this.$btnPlay = this.$cardItem.find('.item-btn.play:first');
    this.$btnTranslate = this.$cardItem.find('.item-btn.translate:first');
    this.$btnHide = this.$cardItem.find('.item-btn.hide:first');

    this.SHOW_TRANSLATE_CLASS = 'show-block-1';
    this.SHOW_HIDE_CLASS = 'show-hide';

    this.STATUS_ENGLISH = 'english';
    this.STATUS_TRANSLATE = 'translate';

    this.STATUS_SHOW_HIDE = false;
    this.STATUS_SHOW_VISIBLE = true;

    this.statusTranslate = this.STATUS_ENGLISH;

    this.statusShow = this.STATUS_SHOW_HIDE;

    this.initEvent();
};


wumvi.Card.ItemModel.prototype.onPlayBtnClick = function () {
    this.cardCtrl.$playerBox.jPlayer("setMedia", {
        //mp3: "https://psv4.vk.me/c611423/u228368481/audios/2c4cf31c1d07.mp3?d=1",
        mp3: "https://psv4.vk.me/c422529/u67616085/audios/a06b7eeae38b.mp3?d=1",
        //oga: "/res/card/audio/01/threaten.mp3",
        oga: "/res/card/audio/01/threaten.ogg"
    }).jPlayer("play");

    return false;
};

wumvi.Card.ItemModel.prototype.onTranslateBtnMouseDown = function () {
    if (this.isWordShow) {
        return;
    }
    this.$cardItem
        .addClass(this.cardCtrl.ITEM_TYPE_TRANSLATE)
        .removeClass(this.cardCtrl.ITEM_TYPE_SENTENCE + ' ' + this.cardCtrl.ITEM_TYPE_WORD);
    return false;
};

wumvi.Card.ItemModel.prototype.onTranslateBtnMouseUp = function () {
    if (this.isWordShow) {
        return;
    }
    this.$cardItem
        .addClass(this.cardCtrl.ITEM_TYPE_SENTENCE)
        .removeClass(this.cardCtrl.ITEM_TYPE_TRANSLATE + ' ' + this.cardCtrl.ITEM_TYPE_WORD);
};

wumvi.Card.ItemModel.prototype.onShowHideBtnClick = function () {
    if (this.isWordShow) {
        this.$cardItem
            .removeClass(this.cardCtrl.ITEM_TYPE_WORD)
            .addClass(this.cardCtrl.ITEM_TYPE_SENTENCE)
    } else {
        this.$cardItem
            .addClass(this.cardCtrl.ITEM_TYPE_WORD)
            .removeClass(this.cardCtrl.ITEM_TYPE_SENTENCE);
    }
    this.isWordShow = !this.isWordShow;

    return false;
};

wumvi.Card.ItemModel.prototype.onTranslateBtnClick = function (){
    if (this.statusTranslate == this.STATUS_TRANSLATE) {
        this.closeTranslateBtnClick();
        return false;
    }

    this.statusTranslate = this.STATUS_TRANSLATE;
    this.$cardItem.addClass(this.SHOW_TRANSLATE_CLASS);
    this.switchTranslateBtnText(this.$btnTranslate);
    return false;
};

wumvi.Card.ItemModel.prototype.onHideBtnClick = function () {
    if (this.statusShow == this.STATUS_SHOW_HIDE) {
        this.$cardItem.removeClass(this.SHOW_HIDE_CLASS);
        this.statusShow = this.STATUS_SHOW_VISIBLE;
        this.$btnTranslate.show();
    } else {
        this.statusShow = this.STATUS_SHOW_HIDE;
        this.$cardItem.addClass(this.SHOW_HIDE_CLASS);
        this.$btnTranslate.hide();
    }

    this.switchTranslateBtnText(this.$btnHide);
    return false;
};

wumvi.Card.ItemModel.prototype.closeTranslateBtnClick = function () {
    this.statusTranslate = this.STATUS_ENGLISH;
    this.$cardItem.removeClass(this.SHOW_TRANSLATE_CLASS);
    this.switchTranslateBtnText(this.$btnTranslate);
    return false;
};

wumvi.Card.ItemModel.prototype.initEvent = function () {
    var that = this;
    this.$btnPlay.click(function () {
        return that.onPlayBtnClick(this)
    });

    this.$btnTranslate.click( function(){
        return that.onTranslateBtnClick(this)
    });

    this.$btnHide.click( function(){
        return that.onHideBtnClick(this)
    });
};

wumvi.Card.ItemModel.prototype.switchTranslateBtnText = function($obj) {
    var text = $obj.data('text');
    var oldtext = $obj.html();
    $obj.data('text', oldtext);
    $obj.html(text);
};

new wumvi.Card();