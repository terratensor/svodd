$("span.link").on("click", function () {
  const newWindow = window.open($(this).text(), "_blank", "noopener,noreferrer",)
  if (newWindow) newWindow.opener = null
})


// Enable tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

var elems = document.getElementsByClassName('bookmarks');
for (var i = 0; i < elems.length; i++) {

  elems[i].addEventListener('click', function (event) {
    event.preventDefault();

    const targetElement = event.target;

    const bookmarkForm = $(this);
    const bookmarkUrl = targetElement.getAttribute('data-href');
    console.log(bookmarkUrl);

    const bookmarkData = bookmarkForm.serialize();

    $.ajax({
      type: "POST",
      url: bookmarkUrl,
      data: bookmarkData,
    }).done(function (response) {
      if (response.error == null) {
        // Handle success
        response = JSON.parse(response);
        const targetClassList = targetElement.classList;
        const isBookmarked = response.bookmark;

        targetElement.setAttribute('data-bs-title', isBookmarked ? 'Убрать из закладок' : 'Добавить в закладки');

        updateTooltips();
        updateBookmarkState(isBookmarked, targetClassList);
      }
    }).fail(function () {
      // Handle error
    });

    return false;

  }, false);
}

/**
 * Updates all tooltips on the page to show the correct text.
 * This function disposes of existing tooltip instances and recreates them to reflect any changes to the tooltip text.
 *
 * @return {void}
 */
function updateTooltips() {

  tooltipTriggerList.forEach(tooltipElement => {
    const tooltipInstance = bootstrap.Tooltip.getInstance(tooltipElement);
    if (tooltipInstance) {
      tooltipInstance.dispose();
    }
    if (tooltipElement) {
      new bootstrap.Tooltip(tooltipElement);
    }
  });
}


/**
 * Updates the bookmark state of an element.
 *
 * @param {boolean} isBookmarked - Indicates whether the element is bookmarked or not.
 * @param {DOMTokenList} classList - The classList object of the element.
 */
function updateBookmarkState(isBookmarked, classList) {

  const bookmarkClass = isBookmarked ? 'bi-bookmark-fill' : 'bi-bookmark';
  const unbookmarkClass = isBookmarked ? 'bi-bookmark' : 'bi-bookmark-fill';

  classList.add(bookmarkClass);
  classList.remove(unbookmarkClass);
}
