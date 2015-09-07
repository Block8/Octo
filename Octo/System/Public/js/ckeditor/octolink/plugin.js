CKEDITOR.plugins.add( 'octolink', {
    icons: 'octolink',
    init: function( editor ) {
        editor.addCommand('octoLinkDialog', new CKEDITOR.dialogCommand('octoLinkDialog', {
            allowedContent: 'a{href,target}'
        } ) );

        editor.ui.addButton( 'octolink', {
            label: 'Page Link',
            command: 'octoLinkDialog',
            toolbar: 'insert',
            icon : '/asset/img/System/octolink.png'
        });

        CKEDITOR.dialog.add('octoLinkDialog', function (editor) {
            return {
                title: 'Insert Page Link',
                minWidth: 400,
                minHeight: 100,
                contents: [
                    {
                        id: 'fields',
                        elements: [
                            {
                                type: 'html',
                                id: 'link-page',
                                html: '<input type="text" name="link-page">',
                                onShow: function() {
                                    window.activeLinkSelector = $('#' + this.domId);

                                    window.activeLinkSelector.select2({
                                        placeholder: "Search for a page",
                                        minimumInputLength: 1,
                                        width: '560px',
                                        dropdownCss: {'z-index': 99999},
                                        ajax: {
                                            url: window.adminUri + '/page/autocomplete/uri',
                                            dataType: 'json',
                                            data: function(term) {
                                                return {
                                                    q: term
                                                };
                                            },
                                            results: function(data) {
                                                return data;
                                            }
                                        }
                                    });
                                }
                            }
                        ]
                    }
                ],
                onOk: function(e) {
                    editor.insertHtml('<a href="'+window.activeLinkSelector.val()+'">'+editor.getSelection().getSelectedText()+'</a>');
                }
            }
        });
    }
});
