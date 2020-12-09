function on_checkbox_change(url) {
  const checkbox = document.getElementById("published");
  const id = getId(url);
  let published;
  if (checkbox.checked) {
    published = 1;
  } else {
    published = 0;
  }
  jQuery.ajax({
    url: "http://localhost/omedia/jobs-listing/web/job/" + id + "/" + published,
    type: "GET",
    async: false,
  });
  location.reload();
}

function getId(url) {
  url = url.split("/");
  return (url[url.length - 1]);
}
