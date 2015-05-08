

var shortCodePlugin = (function(){

    function openSetArticleLinkWindow(editor) {
        window.open('/admin12/view/articlelink/?name='+editor.name, 'Выбор', 'width=800,height=700,scrollbars=no,scrolling=no,location=no,toolbar=no');
        //editor.insertHtml( '%pps-1' );

       // console.log(editor);

       // var selectedText = editor.getSelection().getSelectedText(); // Get Text
        //var newElement = new CKEDITOR.dom.element("p");              // Make Paragraff
        //newElement.setAttributes({style: 'myclass'})                 // Set Attributes
        //newElement.setText(selected_text);                           // Set text to element
        //editor.insertElement(newElement);

       // editor.insertHtml( '('+selectedText+')%pps-2' );
        // func. openSetArticleLinkWindow
    }


    function openSetShortWindow(editor) {
        window.open('/admin12/view/shortcode/?name='+editor.name, 'Выбор', 'width=800,height=700,scrollbars=no,scrolling=no,location=no,toolbar=no');
        // func. openSetShortWindow
    }

    function setArticleLink(pEditorName, pValue){
        var editor = CKEDITOR.instances[pEditorName];

        var selectedText = editor.getSelection().getSelectedText();
        if ( selectedText == '' ){
            editor.insertHtml( selectedText+'%'+pValue );
        }else{
            editor.insertHtml( '('+selectedText+')%'+pValue );
        }
        // func. setArticleLink
    }

    function setShortCode(pEditorName, pText, pName){
        var editor = CKEDITOR.instances[pEditorName];

        var text = '<div class="shortcode" data-name="'+pName+'">'+pText+'</div><p>&nbsp;</p>';

        var selectedText = editor.getSelection().getSelectedText();
        if ( selectedText == '' ){
            editor.insertHtml(text );
        }else{
            editor.insertHtml( text );
        }
        // func. setArticleLink
    }


    function init(){
        var pluginName = 'shortcode';
        CKEDITOR.plugins.add( pluginName, {
            init: function (editor) {
                editor.ui.addButton('SetArticleLink', // see. config.js
                    {
                        label: 'Установить ссылку на статью',
                        command: 'OpenSetArticleLinkWindow',
                        icon: CKEDITOR.plugins.getPath(pluginName) + 'link.png'
                    });

                editor.ui.addButton('SetShortCode', // see. config.js
                    {
                        label: 'Установить Short Code',
                        command: 'OpenSetShortCodeWindow',
                        icon: CKEDITOR.plugins.getPath(pluginName) + 'code.png'
                    });

                editor.addCommand('OpenSetArticleLinkWindow', { exec: shortCodePlugin.openSetArticleLinkWindow });
                editor.addCommand('OpenSetShortCodeWindow', { exec: shortCodePlugin.openSetShortWindow });
            }
        });

        // func. init
    }

    init();

    return{
        openSetArticleLinkWindow: openSetArticleLinkWindow,
        openSetShortWindow: openSetShortWindow,
        setArticleLink: setArticleLink,
        setShortCode: setShortCode

    }
})();
