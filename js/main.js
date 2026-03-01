// Load header and footer on every page load
document.addEventListener("DOMContentLoaded", function () {
    // Load header
    fetch("header.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("header").innerHTML = data;
        });

    // Load footer
    fetch("footer.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("footer").innerHTML = data;
        });
});

// Function to load any tab’s HTML into #container
function loadTab(tabName) {
    fetch(`${tabName}.html`)
        .then(response => response.text())
        .then(html => {
            document.getElementById("container").innerHTML = html;
            // After loading new content, initialize its scripts:
            initializeSection(tabName);
        })
        .catch(err => console.error("Error loading tab:", err));
}

// Dispatcher to run section-specific init after HTML is injected
function initializeSection(section) {
    switch (section) {
        case "noticeboard":
            loadNoticeBoard();
            break;
        case "feeStructure":
            loadFeeStructure();
            break;
        case "faculties":
            loadFacultyData();
            break;
        case "toppers":
            loadToppersData();
            break;
        case "gallery":
            // gallery HTML contains its own lightbox logic
            break;
        // add more cases as needed
    }
}
