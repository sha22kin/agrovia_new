$(document).ready(function () {
  /** Sticky Header js **/

  var header = $(".sticky-header");
  // var topheader = $('.top-header');
  //var middle = $('.main-header');
  var win = $(window);
  win.on("scroll", function () {
    var scroll = win.scrollTop();
    if (scroll < 150) {
      header.removeClass("stick");
      //   topheader.removeClass('hidesection');
      //middle.removeClass('hidesection');
    } else {
      header.addClass("stick");
      //   topheader.addClass('hidesection');
      //middle.addClass('hidesection');
    }
  });

  /** Sticky Header js **/

  $(".newArrival-slider").slick({
    dots: true,
    autoplay: true,
    autoplaySpeed: 2500,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,

    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
          dots: true,
        },
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ],
  });
});
