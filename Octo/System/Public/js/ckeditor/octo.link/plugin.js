CKEDITOR.plugins.add( 'octo.link', {
    icons: 'octo.link',
    init: function( editor ) {
        editor.addCommand('octoLink', {
            exec: function (e) {

                var form = $('<form class="form">');
                var group1 = $('<div class="form-group">');
                var link = $('<select class="form-control octo-page-picker"></select>');


                form.append(group1);

                group1.append('<label>Select a page</label>');
                group1.append(link);

                var insertButton = $('<button class="btn btn-primary">Insert &raquo;</button>');

                var linkDialog = createDialog(e.name + '_image', {
                    show: true,
                    allowClose: true,
                    title: 'Insert page link',
                    body: form,
                    button: insertButton
                });

                Octo.Forms.createPagePicker(link);

                insertButton.on('click', function (e) {
                    e.preventDefault();

                    var linkText = link.find('option:selected').text();
                    var linkHref = 'page:' + link.val();

                    editor.insertHtml('<a href="'+linkHref+'">'+linkText+'</a>');

                    linkDialog.modal('hide');
                    linkDialog.on('hidden.bs.modal', function () {
                        linkDialog.remove();
                    })
                });
            }
        });

        editor.ui.addButton('octo.link', {
            label: 'Insert Page Link',
            command: 'octoLink',
            toolbar: 'insert',
            icon : '/asset/img/System/octo.link.png'
        });
    }
});
