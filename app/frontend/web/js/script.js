$("span.link").on("click", function () {
  const newWindow = window.open($(this).text(), "_blank", "noopener,noreferrer",)
  if (newWindow) newWindow.opener = null
})

$(document).ready(function () {

  const win = $(window),
      doc = $(document);

  (function () {
    let nav = $('.container-pagination');
    if (!nav.length) return;
    let detachable = nav.find('.detachable');
    win.scroll(scrolled);
    win.resize(scrolled);

    function scrolled() {
      if (win.scrollTop() > doc.height() - win.height() - 60) {
        $('.detachable').removeClass('fixed-bottom');
      } else {
        $('.detachable').addClass('fixed-bottom');
      }
    }
    scrolled();
  })();
});
