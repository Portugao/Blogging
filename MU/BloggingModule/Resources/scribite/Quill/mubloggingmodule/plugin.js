var mubloggingmodule = function(quill, options) {
    setTimeout(function() {
        var button;

        button = jQuery('button[value=mubloggingmodule]');

        button
            .css('background', 'url(' + Zikula.Config.baseURL + Zikula.Config.baseURI + '/web/modules/mublogging/images/admin.png) no-repeat center center transparent')
            .css('background-size', '16px 16px')
            .attr('title', 'Blogging')
        ;

        button.click(function() {
            MUBloggingModuleFinderOpenPopup(quill, 'quill');
        });
    }, 1000);
};
