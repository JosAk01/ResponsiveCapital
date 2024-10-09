<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capital International Group</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
<nav class="navbar">
        <div class="navbar-container">
            <div class="menu-toggle" id="mobile-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
            <img src="../img/Logo.svg" alt="Capital Logo" class="navbar-logo">
            
            <div class="cta-buttons">
                <a href="../user/logout.php" class="login">Logout</a>
                <a href="../user/profile.php" class="get-started">Profile</a>
            </div>
      
            <span class="search-icon" id="search-icon" onclick="toggleSearch()">&#128269;</span>
            <!-- Search Bar Popup -->
            <div class="search-popup" id="searchPopup">
                <span class="icon">&#128269;</span>
                <input type="text" placeholder="Type your search">
                <button>Search</button>
            </div>
        </div>
    </nav>

    <!-- Random Joke Section -->
<section class="joke-section">
    <h2>Random Joke</h2>
    <div class="joke-container" id="joke-container">
        <!-- Dynamic content from the API will be inserted here -->
    </div>
    <button onclick="fetchRandomJoke()">Load New Joke</button>
</section>
<script src="../script/script.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Ensure popup is hidden initially
    const searchPopup = document.getElementById("searchPopup");
    searchPopup.style.display = "none";

    // Load a random joke when the page loads
    fetchRandomJoke();
});

// Function to fetch a random joke from the Joke API
function fetchRandomJoke() {
    fetch('https://official-joke-api.appspot.com/random_joke')
        .then(response => response.json())
        .then(data => {
            const jokeContainer = document.getElementById('joke-container');

            // Build the joke dynamically
            jokeContainer.innerHTML = `
                <p><strong>Setup:</strong> ${data.setup}</p>
                <p><strong>Punchline:</strong> ${data.punchline}</p>
            `;
        })
        .catch(error => {
            console.error('Error fetching the joke:', error);
            const jokeContainer = document.getElementById('joke-container');
            jokeContainer.innerHTML = `<p>Sorry, couldn't fetch a joke at the moment.</p>`;
        });
}

// Search toggle function
function toggleSearch() {
    const searchPopup = document.getElementById("searchPopup");
    searchPopup.style.display = searchPopup.style.display === "flex" ? "none" : "flex";
}

</script>
</body>
</html>
