(function() {
  'use strict';
  // Adds Bootstrap theme compatible classes for active list items in alpha pager.
  document.addEventListener('DOMContentLoaded', function() {
    // Preserve is-active class in menu when alpha facet is engaged.
    var url = window.location.href;
    // Create extra list item to display pager reset option.
    var resetLi = document.createElement('li');

    if (!document.querySelector(".view-display-id-attachment_1 li a.is-active")) {
      resetLi.classList.add("is-active", "active");
    }
    
    // Add reset option to pager and is-active to "Contributors" if URL includes "/Contributors/".
    if (url.includes("/contributors/")) {
      var contributorsLink = document.querySelector(".region-nav-main .nav-link[title='Contributors']");
      if (contributorsLink) {
        contributorsLink.classList.add('is-active');
      }
      var resetLink = document.createElement('a');
      resetLink.textContent = "All";
      resetLink.href = "/contributors/all";
      resetLi.appendChild(resetLink);
    }

    // Add is-active to "Topics" if URL includes "/Topics/".
    if (url.includes("/topics/")) {
      var topicsLink = document.querySelector(".region-nav-main .nav-link[title='Topics']");
      if (topicsLink) {
        topicsLink.classList.add('is-active');
      }
      var resetLink = document.createElement('a');
      resetLink.textContent = "All";
      resetLink.href = "/topics/all";
      resetLi.appendChild(resetLink);
    }

    // Prepend new reset item.
    var ulElement = document.querySelector(".view-display-id-attachment_1 ul");
    if (ulElement) {
      ulElement.insertBefore(resetLi, ulElement.firstChild);
    }

    // Configure classes to match Bootstrap 5 pagination.
    var itemList = document.querySelector(".view-display-id-attachment_1 .item-list");
    if (itemList) {
      itemList.classList.add("pager");
      itemList.classList.remove("item-list");
    }

    if (ulElement) {
      ulElement.classList.add("pagination", "js-pager__items");
    }

    var listItems = document.querySelectorAll(".view-display-id-attachment_1 li");
    listItems.forEach(function(li) {
      li.classList.add("page-item");
      var anchor = li.querySelector("a");
      if (anchor) {
        anchor.classList.add("page-link");
      }
      if (li.querySelector("a.is-active")) {
        li.classList.add("is-active", "active");
      }
    });
  });
})();