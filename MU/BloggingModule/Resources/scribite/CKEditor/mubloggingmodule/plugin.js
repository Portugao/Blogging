CKEDITOR.plugins.add('mubloggingmodule', {
    requires: 'popup',
    lang: 'en,nl,de',
    init: function (editor) {
        editor.addCommand('insertMUBloggingModule', {
            exec: function (editor) {
                var url = Routing.generate('mubloggingmodule_external_finder', { objectType: 'post', editor: 'ckeditor' });
                // call method in MUBloggingModule.Finder.js and provide current editor
                MUBloggingModuleFinderCKEditor(editor, url);
            }
        });
        editor.ui.addButton('mubloggingmodule', {
            label: editor.lang.mubloggingmodule.title,
            command: 'insertMUBloggingModule',
            icon: this.path.replace('scribite/CKEditor/mubloggingmodule', 'public/images') + 'admin.png'
        });
    }
});
