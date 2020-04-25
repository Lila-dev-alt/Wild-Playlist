$(window).scroll(function() {
    var scroll = $(window).scrollTop();
    $(".diagonal-bg svg line").attr("stroke-width",  ((30 + scroll/10)  + "%"));});
