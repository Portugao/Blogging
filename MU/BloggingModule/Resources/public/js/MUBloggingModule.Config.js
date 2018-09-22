'use strict';

function bloggingToggleShrinkSettings(fieldName) {
    var idSuffix;

    idSuffix = fieldName.replace('mubloggingmodule_config_', '');
    jQuery('#shrinkDetails' + idSuffix).toggleClass('hidden', !jQuery('#mubloggingmodule_config_enableShrinkingFor' + idSuffix).prop('checked'));
}

jQuery(document).ready(function () {
    jQuery('.shrink-enabler').each(function (index) {
        jQuery(this).bind('click keyup', function (event) {
            bloggingToggleShrinkSettings(jQuery(this).attr('id').replace('enableShrinkingFor', ''));
        });
        bloggingToggleShrinkSettings(jQuery(this).attr('id').replace('enableShrinkingFor', ''));
    });
});
