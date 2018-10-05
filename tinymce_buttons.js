(function() {
    tinymce.PluginManager.add('asciiplayerbutton', function( editor, url ) {
        editor.addButton( 'asciiplayerbutton', {
            text: tinyMCE_object.button_name,
            icon: false,
            onclick: function() {
                editor.windowManager.open( {
                    title: tinyMCE_object.button_title,
                    body: [
                        {
                            type: 'textbox',
                            name: 'cast_url',
                            label: tinyMCE_object.image_title,
                            value: '',
                            classes: 'cast_url',
                        },
                        {
                            type: 'button',
                            name: 'cast_upload_button',
                            label: '',
                            text: tinyMCE_object.image_button_title,
                            classes: 'cast_upload_button',
                        },//new stuff!
                        {
                            type   : 'checkbox',
                            name   : 'autoplay',
                            label  : 'Autoplay',
                            text   : 'Autoplay',
                            checked : false
                        },
                        {
                            type   : 'checkbox',
                            name   : 'preload',
                            label  : 'Preload',
                            text   : 'Preload',
                            checked : true
                        },
                        {
                            type   : 'listbox',
                            name   : 'playersize',
                            label  : 'Size',
                            values : [
                                { text: 'Small', value: 'small' },
                                { text: 'Medium', value: 'medium' },
                                { text: 'Big', value: 'big' }
                            ],
                            value : 'big' // Sets the default
                        },
                    ],
                    onsubmit: function( e ) {
                        editor.insertContent( '[asciiplayer cast_url="' + e.data.cast_url + '" playersize="' + e.data.playersize + '" autoplay="' + e.data.autoplay + '" preload="' + e.data.preload + '"]');
                    }
                });
            },
        });
    });

})();

jQuery(document).ready(function($){
    $(document).on('click', '.mce-cast_upload_button', upload_image_tinymce);

    function upload_image_tinymce(e) {
        e.preventDefault();
        var $input_field = $('.mce-cast_url');
        var custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Add Cast',
            button: {
                text: 'Add Cast'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $input_field.val(attachment.url);
        });
        custom_uploader.open();
    }
});
