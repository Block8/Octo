CKEDITOR.plugins.add( 'cmsfile', {
    icons: 'cmsfile',
    init: function( editor ) {
        editor.addCommand( 'cmsfileDialog', new CKEDITOR.dialogCommand( 'cmsfileDialog', {
            allowedContent: 'img{id,src}'
        } ) );
        editor.ui.addButton( 'cmsfile', {
            label: 'Insert File',
            command: 'cmsfileDialog',
            toolbar: 'insert'
        });

        var html = '';
        var scope = 'files';

        getFiles(scope).done(function(response) {
            var data = JSON.parse(response);
            var files = [];
            for(var i = 0; i < data.length; i++) {
                files.push([data[i].title, data[i].id]);
            }

            CKEDITOR.dialog.add( 'cmsfileDialog', function ( editor ) {
                return {
                    title: 'Insert File',
                    minWidth: 400,
                    minHeight: 200,
                    contents: [
                        {
                            id: 'fields',
                            elements: [
                                {
                                    type: 'select',
                                    id: 'file',
                                    label: 'Choose File:<br /><br />',
                                    items: files,
                                    onChange: function(e) {
                                        var selected = e.data.value;
                                    }
                                }
                            ]
                        }
                    ],
                    onOk: function(e) {
                        var dialog = this;
                        var id = dialog.getValueOf( 'fields', 'file' );
                        var image = editor.document.createElement('img');
                        image.setAttribute( 'id', id );
                        image.setAttribute( 'src', '/assets/backoffice/img/file.gif' );
                        editor.insertElement( image );
                    }
                }
            });
        });

    }
});

function getFiles(scope)
{
    return $.ajax({
        type: "GET",
        url: "/media/ajax/" + scope,
        data: {}
    });
}
