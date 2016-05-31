CKEDITOR.plugins.add( 'cmsimage', {
    icons: 'cmsimage',
    init: function( editor ) {

        editor.addCommand('octoImage', {
            exec: function (e) {

                var form = $('<form class="form">');
                var group1 = $('<div class="form-group">');
                var group2 = $('<div class="form-group">');
                var image = $('<select class="form-control octo-image-picker"></select>');
                var width = $('<input class="form-control" value="auto">');
                var height = $('<input class="form-control" value="auto">');
                var imageClass = $('<input class="form-control" value="img-responsive">');

                form.append(group1);
                form.append(group2);

                group1.append('<label>Select an image</label>');
                group1.append(image);
                group2.append('<label>Image width</label>');
                group2.append(width);
                group2.append('<label>Image height</label>');
                group2.append(height);
                group2.append('<label>Image class</label>');
                group2.append(imageClass);

                var insertButton = $('<button class="btn btn-primary">Insert &raquo;</button>');

                var imageDialog = createDialog(e.name + '_image', {
                    show: true,
                    allowClose: true,
                    title: 'Insert image',
                    body: form,
                    button: insertButton
                });

                convertSelectToImagePicker(image);

                insertButton.on('click', function (e) {
                    e.preventDefault();

                    var createdImage = editor.document.createElement('img');
                    var imageWidth = width.val() != '' ? width.val() : 'auto';
                    var imageHeight = height.val() != '' ? height.val() : 'auto';

                    if (imageClass.val() != '') {
                        createdImage.setAttribute('class', imageClass.val());
                    }

                    var imageUrl = '/media/render/' + image.val();

                    if (imageWidth != 'auto' || imageHeight != 'auto') {
                        imageUrl += '/' + imageWidth + '/' + imageHeight;
                    }

                    createdImage.setAttribute( 'src', imageUrl );
                    editor.insertElement( createdImage );

                    imageDialog.modal('hide');
                    imageDialog.on('hidden.bs.modal', function () {
                        imageDialog.remove();
                    })
                });
            }
        });

        editor.ui.addButton( 'cmsimage', {
            label: 'Insert Image',
            command: 'octoImage',
            toolbar: 'insert',
            icon : '/asset/img/System/cmsimage.png'
        });
    }
});
