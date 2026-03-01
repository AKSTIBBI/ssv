// Function to dynamically load content into the page
function loadContent(section) {
    const contentDiv = document.getElementById('mainContent'); // The div where content will be loaded
    
    // Clear the current content
    contentDiv.innerHTML = '';

    // Define content for each section
    const contentData = {
        schoolProfile: "<h2>School Profile</h2><p>Welcome to our school...</p>",
        visionMission: "<h2>Vision & Mission</h2><p>Our mission is...</p>",
        principalMessage: "<h2>Principal's Message</h2><p>Welcome from the Principal...</p>",
        directorMessage: "<h2>Director's Message</h2><p>Message from the Director...</p>",
        prospectus: "<h2>Prospectus</h2><p>Download our school prospectus...</p>",
        faculties: "<h2>Faculties</h2><p>Meet our faculty members...</p>",
        toppers: "<h2>Toppers</h2><p>Our academic toppers...</p>",
        noticeBoard: "<h2>Notice Board</h2><p>Recent notices...</p>",
        feeStructure: "<h2>Fee Structure</h2><p>The school fee structure...</p>",
        timeTable: "<h2>Time Table</h2><p>View the time table...</p>",
        syllabus: "<h2>Syllabus</h2><p>Download the syllabus...</p>",
        photoGallery: "<h2>Photo Gallery</h2><p>Check out our photo gallery...</p>",
        videoGallery: "<h2>Video Gallery</h2><p>Watch our school videos...</p>",
        rteOverview: "<h2>RTE Overview</h2><p>Overview of the Right to Education...</p>",
        rtePolicy: "<h2>RTE Policy</h2><p>Policy details...</p>",
        applicationProcess: "<h2>Application Process</h2><p>How to apply...</p>",
        rteGuidelines: "<h2>RTE Guidelines</h2><p>Guidelines for applying...</p>",
        rteContact: "<h2>Contact</h2><p>Contact details for RTE...</p>",
        contactUs: "<h2>Contact Us</h2><p>Get in touch with us...</p>",
        admission: "<h2>Admission</h2><p>How to get admission...</p>",
        login: "<h2>Login</h2><p>Login to your account...</p>"
    };

    // Insert the content into the main content area
    contentDiv.innerHTML = contentData[section] || "<h2>Welcome</h2><p>Select a section from the menu above.</p>";
}

// Load default content on page load
document.addEventListener('DOMContentLoaded', function() {
    loadContent('schoolProfile'); // Set a default section to load
});




// *********OLD CONTENT *********************
// Loading Header Footer

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

// Loading Faculties Data
async function loadFacultyData() {
    const response = await fetch('facultyData.json');
    const facultyData = await response.json();
    const container = document.getElementById('facultyContainer');
    facultyData.forEach(faculty => {
        const card = document.createElement('div');
        card.className = 'faculty-card';
        card.innerHTML = `
            <img src="${faculty.image}" alt="Faculty Image">
            <h3>${faculty.name}</h3>
            <p>${faculty.title}</p>
        `;
        container.appendChild(card);
    });
}
loadFacultyData();


// Loading Toppers Data
async function loadToppersData() {
    const response = await fetch('toppersData.json');
    const toppersData = await response.json();
    const container = document.getElementById('toppersContainer');
    toppersData.forEach(toppers => {
        const card = document.createElement('div');
        card.className = 'toppers-card';
        card.innerHTML = `
            <img src="${toppers.image}" alt="toppers Image">
            <h3>${toppers.name}</h3>
            <p>${toppers.class}</p>
            <p>${toppers.rank}</p>
            <p>${toppers.session}</p>
        `;
        container.appendChild(card);
    });
}
loadToppersData();



// Script for Photo Gallery Page Handling
// JavaScript for handling both Image and Video Gallery
let currentIndex = 0;
let mediaType = ''; // 'image' or 'video'

const images = [
    'images/Event1.jpg', 'images/Event2.jpg', 'images/Event3.jpg', 'images/Event4.jpg',
    'images/Event5.jpg', 'images/Event6.jpg', 'images/Event7.jpg', 'images/Event8.jpg',
    'images/Event9.jpg', 'images/Event10.jpg'
];

const videos = [
    'c5I199uvV2E', 'bdbEGwtyxWA', '8AW2zrBYjBw', 'xRdStHvg5-o', '0smgsj8wGkQ',
    'oGYtYt8BKWg', '5meDiZ7PfhY', 'ol6cd37mKxk', 'E_IwgcF34Ik', 'yTFnkTTWHT4'
];

// Function to open the lightbox
function openLightbox(index, type) {
    currentIndex = index;
    mediaType = type;
    const lightboxContent = document.getElementById('lightbox-content');
    const lightbox = document.getElementById('lightbox');

    if (mediaType === 'image') {
        lightboxContent.innerHTML = `<img src="${images[currentIndex]}" alt="Image" style="width:100%;">`;
    } else {
        lightboxContent.innerHTML = `<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/${videos[currentIndex]}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
    }

    lightbox.style.display = 'flex';
}

// Function to close the lightbox
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
}

// Function to change media (image/video)
function changeMedia(direction) {
    if (mediaType === 'image') {
        currentIndex = (currentIndex + direction + images.length) % images.length;
        openLightbox(currentIndex, 'image');
    } else {
        currentIndex = (currentIndex + direction + videos.length) % videos.length;
        openLightbox(currentIndex, 'video');
    }
}

// Sample Data for Notices
const notices = [
    { title: "School Reopens After Holidays", description: "School will reopen on 15th November. Please be on time.", category: "announcements", date: "2024-11-01" },
    { title: "Annual Sports Day 2024", description: "Sports Day will be held on 25th November. All students must participate.", category: "events", date: "2024-11-10" },
    { title: "Christmas Holidays", description: "School will be closed for Christmas from 24th December to 2nd January.", category: "holidays", date: "2024-11-12" },
    { title: "Midterm Exams Schedule", description: "Midterm exams start on 5th December. Make sure to revise your syllabus.", category: "announcements", date: "2024-11-05" },
    { title: "Inter-School Debate", description: "Inter-school debate competition on 28th November. Sign up by 20th November.", category: "events", date: "2024-11-14" },
    { title: "Republic Day Holiday", description: "School will remain closed on 26th January for Republic Day.", category: "holidays", date: "2024-11-18" },
];

// Function to Display Notices
function displayNotices() {
    const noticeList = document.getElementById("notice-list");
    noticeList.innerHTML = ""; // Clear existing notices

    const categoryFilter = document.getElementById("category-filter").value;
    const searchQuery = document.getElementById("search-input").value.toLowerCase();

    // Filter notices based on category and search input
    const filteredNotices = notices.filter(notice => {
        const matchesCategory = categoryFilter === "all" || notice.category === categoryFilter;
        const matchesSearch = notice.title.toLowerCase().includes(searchQuery) || notice.description.toLowerCase().includes(searchQuery);
        return matchesCategory && matchesSearch;
    });

    // Loop through filtered notices and display them
    filteredNotices.forEach(notice => {
        const noticeItem = document.createElement("div");
        noticeItem.classList.add("notice-item");

        noticeItem.innerHTML = `
            <h3>${notice.title}</h3>
            <p>${notice.description}</p>
            <span>Posted on: ${new Date(notice.date).toLocaleDateString()}</span>
        `;

        noticeList.appendChild(noticeItem);
    });
}

// Event listeners for the filters
document.getElementById("category-filter").addEventListener("change", displayNotices);
document.getElementById("search-input").addEventListener("input", displayNotices);

// Initial loading of notices
window.onload = displayNotices;


// Start of 2nd Notice Board


// End of 2nd Notice Board


  