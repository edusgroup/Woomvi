wumvi = window.wumvi || {};

wumvi.Card = function(){
	var that = this;

	this.ITEM_TYPE_SENTENCE = 'sentence';
	this.ITEM_TYPE_TRANSLATE = 'translate';
	this.ITEM_TYPE_WORD = 'word';

	this.$root = jQuery('#wumvi-card-box');

	this.$root.find('.card-item').each(function(num, obj){
		new wumvi.Card.ItemModel(jQuery(obj), false, that);
	})

	this.$playerBox = jQuery("#player-box");

	this.$playerBox.jPlayer({
		swfPath: "/res/plugin/jplayer",
		supplied: "mp3, oga",
		wmode: "window",
		useStateClassSkin: true,
		autoBlur: false,
		smoothPlayBar: true,
		keyEnabled: true,
		remainingDuration: true,
		toggleDuration: true
	});
}


wumvi.Card.ItemModel = function($cardItem, isWordShow, cardCtrl){
	this.isWordShow = isWordShow;
	this.$cardItem = $cardItem;
	this.cardCtrl = cardCtrl;
	this.initEvent();
}


wumvi.Card.ItemModel.prototype.onPlayBtnClick = function(){
	this.cardCtrl.$playerBox.jPlayer("setMedia", {
		mp3: "/res/card/audio/01/threaten.mp3",
		oga: "/res/card/audio/01/threaten.ogg"
	}).jPlayer("play");

	return false;
}

wumvi.Card.ItemModel.prototype.onTranslateBtnMouseDown = function(){
	if (this.isWordShow) {
		return;
	}
	this.$cardItem
			.addClass(this.cardCtrl.ITEM_TYPE_TRANSLATE)
			.removeClass(this.cardCtrl.ITEM_TYPE_SENTENCE + ' ' + this.cardCtrl.ITEM_TYPE_WORD);
	return false;
}

wumvi.Card.ItemModel.prototype.onTranslateBtnMouseUp = function(){
	if (this.isWordShow) {
		return;
	}
	this.$cardItem
			.addClass(this.cardCtrl.ITEM_TYPE_SENTENCE)
			.removeClass(this.cardCtrl.ITEM_TYPE_TRANSLATE + ' ' + this.cardCtrl.ITEM_TYPE_WORD);
}

wumvi.Card.ItemModel.prototype.onShowHideBtnClick = function(){
	if (this.isWordShow) {
		this.$cardItem
				.removeClass(this.cardCtrl.ITEM_TYPE_WORD)
				.addClass(this.cardCtrl.ITEM_TYPE_SENTENCE)
	}else{
		this.$cardItem
				.addClass(this.cardCtrl.ITEM_TYPE_WORD)
				.removeClass(this.cardCtrl.ITEM_TYPE_SENTENCE);
	}
	this.isWordShow = !this.isWordShow;

	return false;
}

wumvi.Card.ItemModel.prototype.initEvent = function(){
	var that = this;
	this.$cardItem.find('.play-btn:first').click( function(){return that.onPlayBtnClick(this) });
	this.$cardItem
			.find('.translate-btn:first')
			.mousedown(function(){ return that.onTranslateBtnMouseDown(this) })
			.mouseup(function(){ return that.onTranslateBtnMouseUp(this) })
			.mouseout(function(){ return that.onTranslateBtnMouseUp(this) });
	this.$cardItem.find('.show-hide-btn:first').click(function(){ return that.onShowHideBtnClick(this) });
}

new wumvi.Card();