/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
  config.extraPlugins = 'autosave';
  config.language = 'sk';
  config.enterMode = CKEDITOR.ENTER_BR;
  config.shiftEnterMode = CKEDITOR.ENTER_P;
  config.skin = 'bootstrapck';
  config.contentsCss = 'http://desart.sk/css/editorstyl.css';
  config.disableNativeSpellChecker = false;
  config.browserContextMenuOnCtrl = true;
  config.allowedContent = true; 
  config.height = '500px';
  config.entities_greek = false;
  config.entities_latin = false;
};