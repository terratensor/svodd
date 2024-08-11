$("span.link").on("click", function () {
  const newWindow = window.open($(this).text(), "_blank", "noopener,noreferrer",)
  if (newWindow) newWindow.opener = null
})
