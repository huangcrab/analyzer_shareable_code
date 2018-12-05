$(document).ready(function() {
  if (
    typeof document.getElementById("search_bar") != "undefined" &&
    document.getElementById("search_bar") != null
  ) {
    let searchBar = document.getElementById("search_bar");
    searchBar.addEventListener("keyup", filterResult);
  }

  if (
    typeof document.getElementById("sort_select") != "undefined" &&
    document.getElementById("sort_select") != null
  ) {
    let sortSelect = document.getElementById("sort_select");
    sortSelect.addEventListener("change", sortResult);
  }
  if (
    typeof document.getElementById("number_of_blocks") != "undefined" &&
    document.getElementById("number_of_blocks") != null
  ) {
    updateNumber();
    showError();
  }
});

function sortElements(elements, callback) {
  let elems = [];
  for (let i = 0; i < elements.length; ++i) {
    let el = elements[i];
    elems.push(el);
  }
  let sorted = elems.sort(callback);

  return sorted;
}

function sortDate(a, b) {
  let aValue = a.getElementsByClassName("card-text")[1].innerHTML;
  let bValue = b.getElementsByClassName("card-text")[1].innerHTML;
  return new Date(aValue) - new Date(bValue);
}
function sortAscending(a, b) {
  let aValue = a.getElementsByClassName("card-title")[0].innerHTML;
  let bValue = b.getElementsByClassName("card-title")[0].innerHTML;
  if (aValue < bValue) return -1;
  if (aValue > bValue) return 1;
  return 0;
}

function sortResult() {
  let selection = document.getElementById("sort_select");
  let zone = document.getElementById("sort_zone");
  let elements = document.getElementsByClassName("card");
  if (selection.options[selection.selectedIndex].text == "Name") {
    let sorted = sortElements(elements, sortAscending);
    let html = "";
    for (let i = 0; i < sorted.length; i++) {
      html += sorted[i].outerHTML;
    }
    zone.innerHTML = html;
  }
  if (selection.options[selection.selectedIndex].text == "Modified Date") {
    let sorted = sortElements(elements, sortDate);
    let html = "";
    for (let i = 0; i < sorted.length; i++) {
      html += sorted[i].outerHTML;
    }
    zone.innerHTML = html;
  }
}

function filterResult() {
  let searchValue = document.getElementById("search_bar").value.toUpperCase();
  let cards = document.getElementsByClassName("card");
  let number = 0;

  for (let i = 0; i < cards.length; i++) {
    let name = cards[i].getElementsByClassName("card-title")[0];
    if (name.innerHTML.toUpperCase().indexOf(searchValue) > -1) {
      cards[i].style.display = "";
      number++;
    } else {
      cards[i].style.display = "none";
    }
  }
  if (
    typeof document.getElementById("number_of_blocks") != "undefined" &&
    document.getElementById("number_of_blocks") != null
  ) {
    let result = document.getElementById("number_of_blocks");
    result.innerHTML = number + " blocks";
  }
}
function toggleCheck(e) {
  checkError(e.childNodes[1]);
}
function updateNumber(classname, target, name) {
  let number = $(`.${classname}:visible`).length;
  let result = document.getElementById(target);
  console.log(number);
  result.innerHTML = number + name;
}

function getClassNameFromId(id) {
  switch (id) {
    case "check-eg":
      return "empty-gateway";

    case "check-mm":
      return "miss-mapping";

    case "check-ls":
      return "long-singal";

    case "check-me":
      return "miss-endlink";

    case "check-hd":
      return "has-default";

    default:
      return false;
    //
  }
}

function showError() {
  $(".card").hide();
  $(".sub-check:checked").each(function() {
    $("." + getClassNameFromId(this.id)).show();
  });

  if ($(".sub-check:not(:checked)").length == $(".sub-check").length) {
    $(".card").show();
  }
  if ($(".sub-check:checked").length == $(".sub-check").length) {
    document.getElementById("check-all").checked = true;
  }
}

function checkError(e) {
  let result = document.getElementById("number_of_blocks");
  if (e.id == "check-all") {
    if (!e.checked) {
      $(".check-box").each(function() {
        this.checked = true;
      });
      $(".card").hide();
      $(".error").show();
    } else {
      $(".check-box").each(function() {
        this.checked = false;
      });
      $(".card").show();
    }
  } else {
    let number = document.getElementsByClassName("error").length;
    document.getElementById("check-all").checked = false;
    let checkbox = document.getElementById(e.id);
    checkbox.checked = !checkbox.checked;
    showError();
  }
  updateNumber();
}
