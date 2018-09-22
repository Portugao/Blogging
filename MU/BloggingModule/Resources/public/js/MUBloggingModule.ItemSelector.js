'use strict';

var mUBloggingModule = {};

mUBloggingModule.itemSelector = {};
mUBloggingModule.itemSelector.items = {};
mUBloggingModule.itemSelector.baseId = 0;
mUBloggingModule.itemSelector.selectedId = 0;

mUBloggingModule.itemSelector.onLoad = function (baseId, selectedId) {
    mUBloggingModule.itemSelector.baseId = baseId;
    mUBloggingModule.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    jQuery('#mUBloggingModuleObjectType').change(mUBloggingModule.itemSelector.onParamChanged);

    jQuery('#' + baseId + '_catidMain').change(mUBloggingModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + '_catidsMain').change(mUBloggingModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'Id').change(mUBloggingModule.itemSelector.onItemChanged);
    jQuery('#' + baseId + 'Sort').change(mUBloggingModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'SortDir').change(mUBloggingModule.itemSelector.onParamChanged);
    jQuery('#mUBloggingModuleSearchGo').click(mUBloggingModule.itemSelector.onParamChanged);
    jQuery('#mUBloggingModuleSearchGo').keypress(mUBloggingModule.itemSelector.onParamChanged);

    mUBloggingModule.itemSelector.getItemList();
};

mUBloggingModule.itemSelector.onParamChanged = function () {
    jQuery('#ajaxIndicator').removeClass('hidden');

    mUBloggingModule.itemSelector.getItemList();
};

mUBloggingModule.itemSelector.getItemList = function () {
    var baseId;
    var params;

    baseId = mUBloggingModule.itemSelector.baseId;
    params = {
        ot: baseId,
        sort: jQuery('#' + baseId + 'Sort').val(),
        sortdir: jQuery('#' + baseId + 'SortDir').val(),
        q: jQuery('#' + baseId + 'SearchTerm').val()
    }
    if (jQuery('#' + baseId + '_catidMain').length > 0) {
        params[catidMain] = jQuery('#' + baseId + '_catidMain').val();
    } else if (jQuery('#' + baseId + '_catidsMain').length > 0) {
        params[catidsMain] = jQuery('#' + baseId + '_catidsMain').val();
    }

    jQuery.getJSON(Routing.generate('mubloggingmodule_ajax_getitemlistfinder'), params, function (data) {
        var baseId;

        baseId = mUBloggingModule.itemSelector.baseId;
        mUBloggingModule.itemSelector.items[baseId] = data;
        jQuery('#ajaxIndicator').addClass('hidden');
        mUBloggingModule.itemSelector.updateItemDropdownEntries();
        mUBloggingModule.itemSelector.updatePreview();
    });
};

mUBloggingModule.itemSelector.updateItemDropdownEntries = function () {
    var baseId, itemSelector, items, i, item;

    baseId = mUBloggingModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    itemSelector.length = 0;

    items = mUBloggingModule.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.get(0).options[i] = new Option(item.title, item.id, false);
    }

    if (mUBloggingModule.itemSelector.selectedId > 0) {
        jQuery('#' + baseId + 'Id').val(mUBloggingModule.itemSelector.selectedId);
    }
};

mUBloggingModule.itemSelector.updatePreview = function () {
    var baseId, items, selectedElement, i;

    baseId = mUBloggingModule.itemSelector.baseId;
    items = mUBloggingModule.itemSelector.items[baseId];

    jQuery('#' + baseId + 'PreviewContainer').addClass('hidden');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (mUBloggingModule.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id == mUBloggingModule.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (null !== selectedElement) {
        jQuery('#' + baseId + 'PreviewContainer')
            .html(window.atob(selectedElement.previewInfo))
            .removeClass('hidden');
        mUBloggingInitImageViewer();
    }
};

mUBloggingModule.itemSelector.onItemChanged = function () {
    var baseId, itemSelector, preview;

    baseId = mUBloggingModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id').get(0);
    preview = window.atob(mUBloggingModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    jQuery('#' + baseId + 'PreviewContainer').html(preview);
    mUBloggingModule.itemSelector.selectedId = jQuery('#' + baseId + 'Id').val();
    mUBloggingInitImageViewer();
};

jQuery(document).ready(function () {
    var infoElem;

    infoElem = jQuery('#itemSelectorInfo');
    if (infoElem.length == 0) {
        return;
    }

    mUBloggingModule.itemSelector.onLoad(infoElem.data('base-id'), infoElem.data('selected-id'));
});
