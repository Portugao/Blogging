CKEDITOR.plugins.add('mubloggingmodule', {
    requires: 'popup',
    init: function (editor) {
        editor.addCommand('insertMUBloggingModule', {
            exec: function (editor) {
                MUBloggingModuleFinderOpenPopup(editor, 'ckeditor');
            }
        });
        editor.ui.addButton('mubloggingmodule', {
            label: 'Blogging',
            command: 'insertMUBloggingModule',
            icon: this.path.replace('scribite/CKEditor/mubloggingmodule', 'images') + 'admin.png'
        });
    }
});
