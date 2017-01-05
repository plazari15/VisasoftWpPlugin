(function($) {
    $(document).ready(function() {
        $('.fancybox-media').fancybox({
            openEffect  : 'none',
            closeEffect : 'none',
            type : "iframe",
            iframe : {
                preload : false
            },
            helpers : {
                media : {}
            }
        });
    });

    $(window).load(function() {
      // The slider being synced must be initialized first
      $('#carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 210,
        itemMargin: 5,
        asNavFor: '#slider'
      });

      $('#slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: "#carousel"
      });
    });
})( jQuery );