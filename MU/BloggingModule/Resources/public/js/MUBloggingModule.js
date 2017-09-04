'use strict';

function mUBloggingCapitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.substring(1);
}

/**
 * Initialise the quick navigation form in list views.
 */
function mUBloggingInitQuickNavigation()
{
    var quickNavForm;
    var objectType;

    if (jQuery('.mubloggingmodule-quicknav').length < 1) {
        return;
    }

    quickNavForm = jQuery('.mubloggingmodule-quicknav').first();
    objectType = quickNavForm.attr('id').replace('mUBloggingModule', '').replace('QuickNavForm', '');

    quickNavForm.find('select').change(function (event) {
        quickNavForm.submit();
    });

    var fieldPrefix = 'mubloggingmodule_' + objectType.toLowerCase() + 'quicknav_';
    // we can hide the submit button if we have no visible quick search field
    if (jQuery('#' + fieldPrefix + 'q').length < 1 || jQuery('#' + fieldPrefix + 'q').parent().parent().hasClass('hidden')) {
        jQuery('#' + fieldPrefix + 'updateview').addClass('hidden');
    }
}

/**
 * Simulates a simple alert using bootstrap.
 */
function mUBloggingSimpleAlert(anchorElement, title, content, alertId, cssClass)
{
    var alertBox;

    alertBox = ' \
        <div id="' + alertId + '" class="alert alert-' + cssClass + ' fade"> \
          <button type="button" class="close" data-dismiss="alert">&times;</button> \
          <h4>' + title + '</h4> \
          <p>' + content + '</p> \
        </div>';

    // insert alert before the given anchor element
    anchorElement.before(alertBox);

    jQuery('#' + alertId).delay(200).addClass('in').fadeOut(4000, function () {
        jQuery(this).remove();
    });
}

/**
 * Initialises the mass toggle functionality for admin view pages.
 */
function mUBloggingInitMassToggle()
{
    if (jQuery('.mublogging-mass-toggle').length > 0) {
        jQuery('.mublogging-mass-toggle').unbind('click').click(function (event) {
            if (jQuery('.table.fixed-columns').length > 0) {
                jQuery('.mublogging-toggle-checkbox').prop('checked', false);
                jQuery('.table.fixed-columns .mublogging-toggle-checkbox').prop('checked', jQuery(this).prop('checked'));
            } else {
                jQuery('.mublogging-toggle-checkbox').prop('checked', jQuery(this).prop('checked'));
            }
        });
    }
}

/**
 * Initialises fixed table columns.
 */
function mUBloggingInitFixedColumns()
{
    jQuery('.table.fixed-columns').remove();
    jQuery('.table').each(function() {
        var originalTable, fixedColumnsTable, fixedTableWidth;

        originalTable = jQuery(this);
        fixedTableWidth = 0;
        if (originalTable.find('.fixed-column').length > 0) {
            fixedColumnsTable = originalTable.clone().insertBefore(originalTable).addClass('fixed-columns').removeAttr('id');
            originalTable.find('.dropdown').addClass('hidden');
            fixedColumnsTable.find('.dropdown').removeClass('hidden');
            fixedColumnsTable.css('left', originalTable.parent().position().left);

            fixedColumnsTable.find('th, td').not('.fixed-column').remove();
            fixedColumnsTable.find('th').each(function (i, elem) {
                jQuery(this).css('width', originalTable.find('th').eq(i).css('width'));
                fixedTableWidth += originalTable.find('th').eq(i).width();
            });
            fixedColumnsTable.css('width', fixedTableWidth + 'px');

            fixedColumnsTable.find('tr').each(function (i, elem) {
                jQuery(this).height(originalTable.find('tr:eq(' + i + ')').height());
            });
        }
    });
    mUBloggingInitMassToggle();
}

/**
 * Creates a dropdown menu for the item actions.
 */
function mUBloggingInitItemActions(context)
{
    var containerSelector;
    var containers;
    var listClasses;

    containerSelector = '';
    if (context == 'view') {
        containerSelector = '.mubloggingmodule-view';
        listClasses = 'list-unstyled dropdown-menu';
    } else if (context == 'display') {
        containerSelector = 'h2, h3';
        listClasses = 'list-unstyled dropdown-menu';
    }

    if (containerSelector == '') {
        return;
    }

    containers = jQuery(containerSelector);
    if (containers.length < 1) {
        return;
    }

    containers.find('.dropdown > ul').removeClass('list-inline').addClass(listClasses);
    containers.find('.dropdown > ul a').each(function (index) {
        var title;

        title = jQuery(this).find('i').first().attr('title');
        if (title == '') {
            title = jQuery(this).find('i').first().data('original-title');
        }
        jQuery(this).html(jQuery(this).html() + title);
    });
    containers.find('.dropdown > ul a i').addClass('fa-fw');
    containers.find('.dropdown-toggle').removeClass('hidden').dropdown();
}

/**
 * Initialises image viewing behaviour.
 */
function mUBloggingInitImageViewer()
{
    jQuery('a.image-link').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        image: {
            titleSrc: 'title',
            verticalFit: true
        },
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
            tPrev: Translator.__('Previous (Left arrow key)'),
            tNext: Translator.__('Next (Right arrow key)'),
            tCounter: '<span class="mfp-counter">%curr% ' + Translator.__('of') + ' %total%</span>'
        },
        zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out'
        }
    });
}

jQuery(document).ready(function() {
    var isViewPage;
    var isDisplayPage;

    isViewPage = jQuery('.mubloggingmodule-view').length > 0;
    isDisplayPage = jQuery('.mubloggingmodule-display').length > 0;

    mUBloggingInitImageViewer();

    if (isViewPage) {
        mUBloggingInitQuickNavigation();
        mUBloggingInitMassToggle();
        jQuery(window).resize(mUBloggingInitFixedColumns);
        mUBloggingInitFixedColumns();
        window.setTimeout(mUBloggingInitFixedColumns, 1000);
        mUBloggingInitItemActions('view');
    } else if (isDisplayPage) {
        mUBloggingInitItemActions('display');
    }
});
