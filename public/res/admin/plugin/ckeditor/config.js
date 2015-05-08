/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

    config.removePlugins ='liststyle,tabletools,scayt,contextmenu';
    //config.height = '360px'; //Высота редактора.
    config.language = 'ru';
    config.uiColor = '#9AB8F3';
    config.skin = 'office2013';
    config.stylesSet = 'englishStyle';

    config.allowedContent = true;

    config.toolbar = [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Templates' ] },
        { name: 'clipboard', groups: [ 'undo' ], items: ['Undo', 'Redo' ] },


        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
        '/',
        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'tools', items: [ 'Maximize'] },
        {name: 'spellcheck', items: [ 'jQuerySpellChecker' ]},
        { name: 'others', items: [ '-', 'SetArticleLink', 'SetShortCode' ] }
    ];

    config.contentsCss = '/res/admin/plugin/ckeditor/plugins/jqueryspellchecker/jquery.spellchecker.min.css';
};
