'use strict';
/**
 * Блок управления прогресс дискретным прогресс баром
 * @param {string} objId ID DOM объета для jQuery
 * @param {Object} options Настройки
 */

var ProgressRound = function(objId, options) {
    this.options = {
        count: 0
    };
    jQuery.extend(this.options, options);

    /**
     * Прогресс бар
     * @type {!jQuery}
     */
    this.$progressBox = jQuery(objId);

    if (this.options.count === 0) {
        this.options.count = parseInt(this.$progressBox.data('count'));
    }

    if (this.$progressBox.length === 0) {
        throw Error('Object ' + objId + ' not found');
    }

    this.current = 0;

    /** @var {string} Класс, когда структура уже пройдена */
    this.CLASS_DONE = 'progress_done';
    /** @var {string} Класс, когда структура уже текущая */
    this.CLASS_CURRENT = 'progress_current';
    /** @var {string} Класс, когда структура еще не пройдена */
    this.CLASS_NONE = 'progress_none';

    this.CLASS_ITEM_BOX = 'item';

    this.ID_ITEM_PROGRESS = 'item-progress-';

    this.init_();
};

ProgressRound.prototype.initEvent_ = function(){

};

ProgressRound.prototype.hide = function(){
    this.$progressBox.addClass('hide');
};

ProgressRound.prototype.show = function(){
    this.$progressBox.removeClass('hide');
};

ProgressRound.prototype.setTypeDone = function(num) {
    this.$roundList.eq(num).removeClass(this.CLASS_CURRENT);
    this.$roundList.eq(num).addClass(this.CLASS_DONE);
};

ProgressRound.prototype.rebuild = function(count) {
    if (count === 0) {
        throw Error('Count of object must be more zero');
    }

    this.$progressBox.html('');

    this.options.count = count;

    for (var i = 0; i < count; i += 1) {
        var div = document.createElement('div');
        div.className = this.CLASS_ITEM_BOX + ' ' + this.CLASS_ITEM_BOX + '-' + i;
        div.setAttribute('data-num', i);
        div.setAttribute('id', this.ID_ITEM_PROGRESS + i);
        this.$progressBox.append(div);
    }

    // Получаем список структур
    this.$roundList = this.$progressBox.find('.' + this.CLASS_ITEM_BOX);
    this.$roundList.first().addClass('begin');
    this.$roundList.last().addClass('end');

    this.$roundList.addClass(this.CLASS_NONE);
    this.setTypeCurrent(0);
};

ProgressRound.prototype.init_ = function() {
    this.initEvent_();
    this.rebuild(this.options.count);
};

ProgressRound.prototype.clear = function() {
    this.$roundList.addClass(this.CLASS_NONE);
    this.$roundList.removeClass(this.CLASS_CURRENT);
    this.$roundList.removeClass(this.CLASS_DONE);
};

ProgressRound.prototype.setTypeCurrent = function(num) {
    this.$roundList.eq(num).removeClass(this.CLASS_NONE);
    this.$roundList.eq(num).addClass(this.CLASS_CURRENT);
    this.current = num;
};

/**
 * Устанавливаем следующую позицию в прогресс баре
 */
ProgressRound.prototype.next = function() {
    this.setTypeDone(this.current);
    this.current += 1;
    this.setTypeCurrent(this.current);
};

/**
 * Устанавливаем позиции в прогресс баре
 *
 * @param {int} num Устанавливаемая поцизия c нуля
 */
ProgressRound.prototype.setPosition = function(num) {
    this.clear();
    for (var i = 0; i < num - 1; i += 1) {
        this.$roundList.eq(i).addClass(this.CLASS_DONE).removeClass(this.CLASS_NONE);
    }

    this.setTypeCurrent(num);
};