CKEDITOR.addTemplates( 'default',
    {
        // The name of the subfolder that contains the preview images of the templates.
        imagesPath : CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + 'templates/images/' ),

        // Template definitions.
        templates :
            [
                {
                    title: 'Глагол',
                    description: 'Описание глаголов',
                    html:
                        '<div class="verb-tpl template"><div class="question">В каком времени стоит глагол?</div>' +
                        '<div class="answer">-</div>'+
                        '</div>'
                },
                {
                    title: 'Артикль',
                    html:
                        '<h3>В разработке</h3>' +
                        '<p>Type your text here.</p>'
                }
            ]
    });