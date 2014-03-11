CKEDITOR.plugins.add( 'cmsimage', {
    icons: 'cmsimage',
    init: function( editor ) {
        editor.addCommand( 'cmsimageDialog', new CKEDITOR.dialogCommand( 'cmsimageDialog', {
            allowedContent: 'img{id,alt,width,height,src}'
        } ) );
        editor.ui.addButton( 'cmsimage', {
            label: 'Insert Image',
            command: 'cmsimageDialog',
            toolbar: 'insert'
        });

        var html = '';
        var scope = 'images';

        getImages(scope).done(function(response) {
            var data = JSON.parse(response);
            var images = [];
            for(var i = 0; i < data.length; i++) {
                images.push([data[i].title, data[i].id]);
            }

            CKEDITOR.dialog.add( 'cmsimageDialog', function ( editor ) {
                return {
                    title: 'Insert Image',
                    minWidth: 400,
                    minHeight: 200,
                    contents: [
                        {
                            id: 'fields',
                            elements: [
                                {
                                    type: 'select',
                                    id: 'image',
                                    label: 'Choose Image:<br /><br />',
                                    items: images,
                                    onChange: function(e) {
                                        var src = '<img src="/media/render/' + e.data.value + '/200/auto">';
                                        if($('#image_container').length < 1) {
                                            $('#' + e.sender.domId).append('<br /><div style="display: block; min-height: 100px; min-width: 100px;" id="image_container">' + src + '</div>');
                                        } else {
                                            $('#image_container').html(src);
                                        }
                                    }
                                },
                                {
                                    type: 'text',
                                    id: 'width',
                                    label: 'Width (pixels) (optional):<br /><br />'
                                },
                                {
                                    type: 'text',
                                    id: 'height',
                                    label: 'Height (pixels) (optional):<br /><br />'
                                },
                                {
                                    type: 'text',
                                    id: 'alt',
                                    label: 'Alt/title text (optional):<br /><br />'
                                }
                            ]
                        }
                    ],
                    onOk: function(e) {
                        var dialog = this;
                        var image = editor.document.createElement('img');
                        var id = dialog.getValueOf( 'fields', 'image' );

                        var width = dialog.getValueOf( 'fields', 'width' );
                        var height = dialog.getValueOf( 'fields', 'height' );
                        var alt = dialog.getValueOf( 'fields', 'alt' );

                        if (width != '') {
                            image.setAttribute( 'width', width );
                        }
                        if (height != '') {
                            image.setAttribute( 'height', height );
                        }
                        if (alt != '') {
                            image.setAttribute( 'alt', alt );
                        }

                        image.setAttribute( 'src', '/media/render/' + id + '/' );
                        editor.insertElement( image );
                    }
                }
            });
        });

    }
});



function getImages(scope)
{
    // TODO: Eventually, this needs to check for scopes - for now, just images
    // TODO: Maybe a search function?
    return $.ajax({
        type: "GET",
        url: "/media/ajax/" + scope,
        data: {}
    });
}
