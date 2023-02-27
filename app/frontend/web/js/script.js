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
  let mode = (window.opera) ? ((document.compatMode == "CSS1Compat") ? $('html') : $('body')) : $('html,body');
  $('#toTop').click(function () {
    mode.animate({ scrollTop: 0 }, { duration: 400, queue: false });
    return false;
  });
});
