(function($){
    $(function(){

        var video_metabox = $('#pvt_post_video_meta_box');


        var hide_show_metabox = function(){

            var is_block_editor = $('body').hasClass('block-editor-page');

            var selector = 'input[name="post_format"]:checked';

            var post_format = $(selector);
            var post_format_value = post_format.val();

            //handle video metabox

            if(! is_block_editor){
                if(post_format_value === 'video'){
                    video_metabox.show();
                }//if
                else{
                    video_metabox.hide();
                }//else
            }
        };

        hide_show_metabox();

        $('input[name="post_format"]').click(function(){
            hide_show_metabox();
        });
    });
})(jQuery);


