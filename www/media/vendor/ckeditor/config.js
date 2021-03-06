﻿/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};

CKEDITOR.on( 'instanceReady', function( ev ) {
	ev.editor.dataProcessor.writer.setRules( 'p',
		{
			indent : false,
			breakBeforeOpen : true,
			breakAfterOpen : false,
			breakBeforeClose : false,
			breakAfterClose : true
		});
});
