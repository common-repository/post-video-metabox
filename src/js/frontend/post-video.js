(function($) {
    $(function() {
        var video_post_thumbnail = $("article.post .pvt-video-thumbnail");

        video_post_thumbnail.parent("a").click(function(e) {
            e.preventDefault();
        });

        video_post_thumbnail.click(function() {
            var src = $(this)
                .find(".pvt-video-thumbnail-src")
                .text();

            console.log("video-src", src);
            var img = $(this).find("img");
            var overlay = $(this).find(".pvt-video-thumbnail-overlay");

            overlay.hide();
            img.hide();
            var iframe = $(this).find("iframe");
            iframe.removeAttr("width");
            iframe.removeAttr("height");
            iframe.attr("src", src);
            iframe.attr("autoplay", "1");
            iframe.show();
        });
    });
})(jQuery);
