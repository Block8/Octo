window.basicCkEditor = {
    toolbar: [
        [ 'Source' ],
        [ 'Styles' ],
        [ 'Bold', 'Italic' ],
        [ 'Link', 'Unlink', 'octo.link', 'octo.image' ],
        [ 'Undo', 'Redo', '-', 'PasteText', 'PasteFromWord' ],
        [ 'Scayt' ]
    ],
    removePlugins: 'elementspath',
    resizeEnabled: false,
    extraPlugins: 'octo.link,octo.image,scayt,undo',
    stylesSet: 'styles:/asset/js/site/ckeditor',
    contentsCss: '/asset/css/site/ckeditor',
    height: '200px'
};

window.smallCkEditor = {
    toolbar: [
        [ 'Source' ],
        [ 'Styles' ],
        [ 'Bold', 'Italic' ],
        [ 'Link', 'Unlink', 'octo.link', 'octo.image' ],
        [ 'Undo', 'Redo', '-', 'PasteText', 'PasteFromWord' ],
        [ 'Scayt' ]
    ],
    removePlugins: 'elementspath',
    resizeEnabled: false,
    extraPlugins: 'octo.link,octo.image,scayt,undo',
    stylesSet: 'styles:/asset/js/site/ckeditor',
    contentsCss: '/asset/css/site/ckeditor',
    height: '100px'
};

window.fullCkEditor = {
    toolbar: [
        [ 'Source' ],
        [ 'Styles' ],
        [ 'Bold', 'Italic', 'Underline' ],
        [ 'Link', 'Unlink' ],
        [ 'NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', 'Table' ],
        [ 'Undo', 'Redo', '-', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ],
        [ 'octo.link', 'octo.image', 'cmsfile' ],
        [ 'Scayt' ]
    ],
    removePlugins: 'elementspath,image',
    resizeEnabled: false,
    extraPlugins: 'octo.link,octo.image,scayt,undo',
    alllowedContent: true,
    height: '300px',
    stylesSet: 'styles:/asset/js/site/ckeditor',
    contentsCss: '/asset/css/site/ckeditor',
    bodyClass: 'main'
};

CKEDITOR.config.allowedContent = true;

CKEDITOR.on('dialogDefinition', function( ev ) {
    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;

    if(dialogName === 'table' || dialogName == 'tableProperties' ) {
        var infoTab = dialogDefinition.getContents('info');

        //remove fields
        var cellSpacing = infoTab.remove('txtCellSpace');
        var cellPadding = infoTab.remove('txtCellPad');
        var border = infoTab.remove('txtBorder');
        var width = infoTab.remove('txtWidth');
        var height = infoTab.remove('txtHeight');
        var align = infoTab.remove('cmbAlign');

        var advTab = dialogDefinition.getContents('advanced');
        advTab.get('advCSSClasses')['default'] = 'table table-bordered'; //bootstrap table
        advTab.get('advStyles')['default'] = ''; //bootstrap table

    }
    if(dialogName === 'image') {
        var infoTab = dialogDefinition.getContents('info');
        dialogDefinition.removeContents( 'Link' );
        dialogDefinition.removeContents( 'advanced' );
        infoTab.remove('txtWidth');
        infoTab.remove('txtHeight');
        infoTab.remove('txtBorder');
        infoTab.remove('txtHSpace');
        infoTab.remove('txtVSpace');
        infoTab.remove('ratioLock');
        infoTab.remove('cmbAlign');

    }
});

$(document).ready(function() {
	$('textarea.html-editor.small').each(function () {
        var $ed = $(this);
        var spec = window.smallCkEditor;

        if ($ed.attr('height')) {
            spec.height = $ed.attr('height') + 'px';
        }

        $ed.ckeditor(spec);
    });

    $('textarea.html-editor.basic').each(function () {
        var $ed = $(this);
        var spec = window.smallCkEditor;

        if ($ed.attr('height')) {
            spec.height = $ed.attr('height') + 'px';
        }

        $ed.ckeditor(spec);
    });

    $('textarea.html-editor.advanced').each(function () {
        var $ed = $(this);
        var spec = window.smallCkEditor;

        if ($ed.attr('height')) {
            spec.height = $ed.attr('height') + 'px';
        }

        $ed.ckeditor(spec);
    });

    // Dirty IE fix for CKEditor in Bootstrap 3 Modal Windows.
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};


});
