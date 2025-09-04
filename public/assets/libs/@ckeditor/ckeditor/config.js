/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function(config) {
    // Define changes to default configuration here. For example:

    // config.language = 'fr';
    config.uiColor = '#7a7fdc';
    // config.skin = 'atlas';
    // config.skin = 'bootstrapck';
    // config.skin = 'flat';
    //config.skin = 'icy_orange';
    // config.skin = 'kama';
    //config.skin = 'minimalist';
    //config.skin = 'moono';
    //config.skin = 'moonocolor';
    // config.skin = 'moono-dark';
    //config.skin = 'moono-lisa';
    //config.skin = 'n1theme';
    config.skin = 'office2013';
    //config.skin = 'prestige';

    // %REMOVE_START%
    config.plugins =
        'about,' +
        'a11yhelp,' +
        'basicstyles,' +
        'bidi,' +
        'blockquote,' +
        'clipboard,' +
        'colorbutton,' +
        'colordialog,' +
        'copyformatting,' +
        'contextmenu,' +
        'dialogadvtab,' +
        'div,' +
        'elementspath,' +
        'enterkey,' +
        'entities,' +
        'filebrowser,' +
        'find,' +
        'floatingspace,' +
        'font,' +
        'format,' +
        'forms,' +
        'horizontalrule,' +
        'htmlwriter,' +
        'image,' +
        'iframe,' +
        'indentlist,' +
        'indentblock,' +
        'justify,' +
        'language,' +
        'link,' +
        'list,' +
        'liststyle,' +
        'magicline,' +
        'maximize,' +
        'newpage,' +
        'pagebreak,' +
        'pastefromgdocs,' +
        'pastefromlibreoffice,' +
        'pastefromword,' +
        'pastetext,' +
        'editorplaceholder,' +
        'preview,' +
        'print,' +
        'removeformat,' +
        'resize,' +
        'save,' +
        'selectall,' +
        'showblocks,' +
        'showborders,' +
        'smiley,' +
        'sourcearea,' +
        'specialchar,' +
        'stylescombo,' +
        'tab,' +
        'table,' +
        'tableselection,' +
        'tabletools,' +
        'templates,' +
        'toolbar,' +
        'undo,' +
        'uploadimage,' +
        'youtube,' +
        'wysiwygarea';
    // %REMOVE_END%


    config.toolbar = [
        { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'ExportPdf', 'Preview', 'Print', '-', 'Templates'] },
        { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
        { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] },
        { name: 'forms', items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'] },
        '/',
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat'] },
        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'] },
        { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
        { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
        '/',
        { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
        { name: 'colors', items: ['TextColor', 'BGColor'] },
        { name: 'tools', items: ['Maximize', 'ShowBlocks'] },
        { name: 'about', items: ['About'] }
    ];

    // Remove some buttons provided by the standard plugins, which are
    // not needed in the Standard(s) toolbar.
    config.removeButtons = 'Underline,Subscript,Superscript';

    // Set the most common block elements.
    config.format_tags = 'p;h1;h2;h3;pre';

    // Simplify the dialog windows.
    //config.removeDialogTabs = 'image:advanced;link:advanced';
    config.contentsCss = [APP_URL + '/assets/css/bootstrap.min.css', APP_URL + '/assets/frontcss/style.css'];
    config.extraPlugins = 'youtube';
};

CKEDITOR.config.allowedContent = true;
CKEDITOR.dtd.$removeEmpty.span = 0;
CKEDITOR.dtd.$removeEmpty.i = 0;
CKEDITOR.dtd.$removeEmpty.p = 0;
CKEDITOR.config.fillEmptyBlocks = false;

// %LEAVE_UNMINIFIED% %REMOVE_LINE%