$("span.link").on("click", function () {
  window.open($(this).text(), "_blank")
})

$(document).ready(function () {
  //scroll on top
  $(window).scroll(function () {
    if ($(this).scrollTop() >= 160) {
      $("#toTop").fadeIn();
    } else {
      $("#toTop").fadeOut();
    }
  });
  let mode = (window.opera) ? ((document.compatMode === "CSS1Compat") ? $('html') : $('body')) : $('html,body');
  $('#toTop').click(function () {
    mode.animate({ scrollTop: 0 }, { duration: 400, queue: false });
    return false;
  });
});

$(document).ready(function () {

  const win = $(window),
      doc = $(document);

  (function () {
    let nav = $('.container-pagination');
    console.log(nav.length)
    if (!nav.length) return;
    let detachable = nav.find('.detachable');
    console.log(detachable)
    win.scroll(scrolled);
    win.resize(scrolled);

    function scrolled() {
      console.log(nav.offset().top)
      if (win.scrollTop() > doc.height() - win.height() - 60) {
        $('.detachable').removeClass('fixed-bottom');
      } else {
        $('.detachable').addClass('fixed-bottom');
      }
    }
    scrolled();
  })();
});
