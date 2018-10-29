'use strict';

var currentMUBloggingModuleEditor = null;
var currentMUBloggingModuleInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getMUBloggingModulePopupAttributes() {
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes';
}

/**
 * Open a popup window with the finder triggered by an editor button.
 */
function MUBloggingModuleFinderOpenPopup(editor, editorName) {
    var popupUrl;

    // Save editor for access in selector window
    currentMUBloggingModuleEditor = editor;

    popupUrl = Routing.generate('mubloggingmodule_external_finder', { objectType: 'post', editor: editorName });

    if (editorName == 'ckeditor') {
        editor.popup(popupUrl, /*width*/ '80%', /*height*/ '70%', getMUBloggingModulePopupAttributes());
    } else {
        window.open(popupUrl, '_blank', getMUBloggingModulePopupAttributes());
    }
}


var mUBloggingModule = {};

mUBloggingModule.finder = {};

mUBloggingModule.finder.onLoad = function (baseId, selectedId) {
    var imageModeEnabled;

    if (jQuery('#mUBloggingModuleSelectorForm').length < 1) {
        return;
    }

    imageModeEnabled = jQuery("[id$='onlyImages']").prop('checked');
    if (!imageModeEnabled) {
        jQuery('#imageFieldRow').addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=6]").addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=7]").addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=8]").addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=9]").addClass('hidden');
    } else {
        jQuery('#searchTermRow').addClass('hidden');
    }

    jQuery('input[type="checkbox"]').click(mUBloggingModule.finder.onParamChanged);
    jQuery('select').not("[id$='pasteAs']").change(mUBloggingModule.finder.onParamChanged);
    
    jQuery('.btn-default').click(mUBloggingModule.finder.handleCancel);

    var selectedItems = jQuery('#mubloggingmoduleItemContainer a');
    selectedItems.bind('click keypress', function (event) {
        event.preventDefault();
        mUBloggingModule.finder.selectItem(jQuery(this).data('itemid'));
    });
};

mUBloggingModule.finder.onParamChanged = function () {
    jQuery('#mUBloggingModuleSelectorForm').submit();
};

mUBloggingModule.finder.handleCancel = function (event) {
    var editor;

    event.preventDefault();
    editor = jQuery("[id$='editor']").first().val();
    if ('ckeditor' === editor) {
        mUBloggingClosePopup();
    } else if ('quill' === editor) {
        mUBloggingClosePopup();
    } else if ('summernote' === editor) {
        mUBloggingClosePopup();
    } else if ('tinymce' === editor) {
        mUBloggingClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function mUBloggingGetPasteSnippet(mode, itemId) {
    var quoteFinder;
    var itemPath;
    var itemUrl;
    var itemTitle;
    var itemDescription;
    var imagePath;
    var pasteMode;

    quoteFinder = new RegExp('"', 'g');
    itemPath = jQuery('#path' + itemId).val().replace(quoteFinder, '');
    itemUrl = jQuery('#url' + itemId).val().replace(quoteFinder, '');
    itemTitle = jQuery('#title' + itemId).val().replace(quoteFinder, '').trim();
    itemDescription = jQuery('#desc' + itemId).val().replace(quoteFinder, '').trim();
    if (!itemDescription) {
        itemDescription = itemTitle;
    }
    imagePath = jQuery('#imagePath' + itemId).length > 0 ? jQuery('#imagePath' + itemId).val().replace(quoteFinder, '') : '';
    pasteMode = jQuery("[id$='pasteAs']").first().val();

    // item ID
    if (pasteMode === '3') {
        return '' + itemId;
    }

    // relative link to detail page
    if (pasteMode === '1') {
        return mode === 'url' ? itemPath : '<a href="' + itemPath + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
    // absolute url to detail page
    if (pasteMode === '2') {
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }

    if (pasteMode === '6') {
        // relative link to image file
        return mode === 'url' ? imagePath : '<a href="' + imagePath + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
    if (pasteMode === '7') {
        // image tag
        return '<img src="' + imagePath + '" alt="' + itemTitle + '" width="300" />';
    }
    if (pasteMode === '8') {
        // image tag with relative link to detail page
        return mode === 'url' ? itemPath : '<a href="' + itemPath + '" title="' + itemTitle + '"><img src="' + imagePath + '" alt="' + itemTitle + '" width="300" /></a>';
    }
    if (pasteMode === '9') {
        // image tag with absolute url to detail page
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemTitle + '"><img src="' + imagePath + '" alt="' + itemTitle + '" width="300" /></a>';
    }


    return '';
}


// User clicks on "select item" button
mUBloggingModule.finder.selectItem = function (itemId) {
    var editor, html;

    html = mUBloggingGetPasteSnippet('html', itemId);
    editor = jQuery("[id$='editor']").first().val();
    if ('ckeditor' === editor) {
        if (null !== window.opener.currentMUBloggingModuleEditor) {
            window.opener.currentMUBloggingModuleEditor.insertHtml(html);
        }
    } else if ('quill' === editor) {
        if (null !== window.opener.currentMUBloggingModuleEditor) {
            window.opener.currentMUBloggingModuleEditor.clipboard.dangerouslyPasteHTML(window.opener.currentMUBloggingModuleEditor.getLength(), html);
        }
    } else if ('summernote' === editor) {
        if (null !== window.opener.currentMUBloggingModuleEditor) {
            html = jQuery(html).get(0);
            window.opener.currentMUBloggingModuleEditor.invoke('insertNode', html);
        }
    } else if ('tinymce' === editor) {
        window.opener.currentMUBloggingModuleEditor.insertContent(html);
    } else {
        alert('Insert into Editor: ' + editor);
    }
    mUBloggingClosePopup();
};

function mUBloggingClosePopup() {
    window.opener.focus();
    window.close();
}

jQuery(document).ready(function () {
    mUBloggingModule.finder.onLoad();
});
