/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};

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