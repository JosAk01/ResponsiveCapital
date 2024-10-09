
document.addEventListener("DOMContentLoaded", function() {
  const searchPopup = document.getElementById("searchPopup");
  searchPopup.style.display = "none"; // Ensure it's hidden initially
});

function toggleSearch() {
  const searchPopup = document.getElementById("searchPopup");
  // Toggle the display property between 'none' and 'flex'
  if (searchPopup.style.display === "flex") {
      searchPopup.style.display = "none";
  } else {
      searchPopup.style.display = "flex";
  }
}

document.getElementById("search-icon").addEventListener("click", function() {
  const searchInput = document.getElementById("search-input");
  searchInput.classList.toggle("active");
});

document.getElementById("mobile-menu").addEventListener("click", function() {
  const navbarItems = document.getElementById("navbar-items");
  navbarItems.classList.toggle("active");
});
// Add click event for the dropdown on mobile screens
const dropdown = document.querySelector('.dropdown');

dropdown.addEventListener('click', function(e) {
  e.preventDefault();
  // Toggle the "active" class on click to show/hide dropdown in mobile
  this.classList.toggle('active');
});

// Get elements
const popup = document.getElementById('popup');
const closePopupBtn = document.getElementById('closePopup');

// Show the popup automatically when the page loads
window.addEventListener('load', function() {
    popup.style.display = 'flex'; // Make the popup visible (flexbox for centering)
});

// Close the popup when clicking the cancel (close) button
closePopupBtn.addEventListener('click', function() {
    popup.style.display = 'none'; // Hide the popup
});

// Close the search popup when clicking outside of it
window.onclick = function(event) {
  const searchPopup = document.getElementById("searchPopup");
  if (!event.target.matches('.search-icon') && !searchPopup.contains(event.target)) {
      searchPopup.style.display = "none";
  }
}
