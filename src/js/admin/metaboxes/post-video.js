(function($){

    $(function(){

        var frame = null;
        var text_box = $('input[name="pvt_post_video_url"]');

        $('#pvt-post-video-button').click(function(event){

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (frame) {
                frame.open();
                return;
            }

            // Create a new media frame
            frame = wp.media({
                title: 'Select or Upload Video',
                button: {
                    text: 'Use this video'
                },
                multiple: false  // Set to true to allow multiple files to be selected
            });

            // When an image is selected in the media frame...
            frame.on( 'select', function() {

                // Get media attachment details from the frame state
                var attachment = frame.state().get('selection').first().toJSON();
                text_box.val(attachment.url);
            });

            // Finally, open the modal on click
            frame.open();
        });
    });

})(jQuery);