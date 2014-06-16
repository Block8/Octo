window.basicCkEditor = {
    toolbar: [
        [ 'Source' ],
        [ 'Bold', 'Italic', 'Underline', '-', 'Strike', 'Subscript', 'Superscript' ],
        [ 'octolink' ],
        [ 'Undo', 'Redo', '-', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ],
        [ 'Scayt' ]
    ],
    removePlugins: 'elementspath',
    extraPlugins: 'scayt,undo',
    resizeEnabled: false,
    height: '200px'
};

window.smallCkEditor = {
    toolbar: [
        [ 'Source' ],
        [ 'Bold', 'Italic', 'Underline', '-', 'Strike', 'Subscript', 'Superscript' ],
        [ 'octolink' ],
        [ 'Undo', 'Redo', '-', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ],
        [ 'Scayt' ]
    ],
    removePlugins: 'elementspath',
    extraPlugins: 'scayt,undo',
    resizeEnabled: false,
    height: '100px'
};

window.fullCkEditor = {
    toolbar: [
        [ 'Source' ],
        [ 'Format', 'Styles' ],
        [ 'Bold', 'Italic', 'Underline', '-', 'Strike', 'Subscript', 'Superscript' ],
        [ 'Link', 'Unlink' ],
        [ 'NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote' ],
        [ 'Undo', 'Redo', '-', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ],
        [ 'octolink', 'cmsimage', 'cmsfile', 'Youtube', 'Table' ],
        [ 'Scayt' ]
    ],
    removePlugins: 'elementspath,image',
    resizeEnabled: false,
    extraPlugins: 'octolink,youtube,cmsfile,cmsimage,scayt,undo',
    alllowedContent: true,
    height: '200px',
    stylesSet: 'styles:/assets/js/ckeditor_styles.js',
    contentsCss: '/assets/style.css',
    bodyClass: 'main'
};

CKEDITOR.config.allowedContent = true;

$(document).ready(function() {
	$('textarea.ckeditor.basic.small').ckeditor(window.smallCkEditor);
	$('textarea.ckeditor.basic').ckeditor(window.basicCkEditor);
    $('textarea.ckeditor.advanced').ckeditor(window.fullCkEditor);

    $("table.dnd").tableDnD();

    // Dirty IE fix for CKEditor in Bootstrap 3 Modal Windows.
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};


});
