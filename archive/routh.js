// *********Index.html Page content starts here *********************
function loadContent(contentId) {
    var contentContainer = document.getElementById('content-container');

    var contentData = {
        home: `content`,
        schoolProfile: ``,
        visionMission: `content`,
        principalMessage: `content`,
        directorMessage: `content`,
        management: `content`,
        financials:`content`,
        faculties: `content`,
        toppers: `content`,
        timeTable: `content`,
        syllabus: `content`,
        photoGallery: `content`,
        videoGallery: `content`,
        rteOverview: `content`,
        rtePolicy: `content`,
        timeFrame: `content`,
        rteFAQs: `content`,
        rteHelpline: `content`,
        careers: `content`,
        contactUs: `content`,
        admission: `content`,
        adminLogin:``
    };


 if (contentId === 'noticeBoard') {
    // Fetch notice data dynamically
    fetch('notices.json')
        .then(response => response.json())
        .then(notices => {
            let noticeHTML = `
<div class="mid">
    <div class="noticeboardmid">
        <div class="indexmid1">
            <div class="notice-container">
                <h2>News & Notice</h2>
                <div class="notice-container">
                    <div class="notice-scroller">
            `;

            notices.forEach(notice => {
                noticeHTML += `
<div class="notice-item">
    <div class="notice-content">
        <div class="notice-date">
            <h5 class="date-number">${notice.date}</h5>
            <div class="date-divider"></div>
            <span class="date-month">${notice.month}</span>
        </div>
        <div class="notice-details">
            <p class="notice-title">${notice.title}</p>
            <p>${notice.description}</p>
            <ul class="notice-meta">
                <li><span class="meta-icon">&#128100;</span> ${notice.author}</li>
                <li><span class="meta-icon">&#128197;</span> ${notice.publish_date}</li>
            </ul>
        </div>
    </div>
</div>
                `;
            });

            noticeHTML += `
                    </div> <!-- notice-scroller -->
                </div> <!-- notice-container -->
            </div> <!-- notice-container -->
        </div> <!-- indexmid1 -->
        <div id="footer"></div> <!-- Footer will load here -->
    </div> <!-- noticeboardmid -->
</div> <!-- mid -->
            `;

            contentContainer.innerHTML = noticeHTML;
        })
        .catch(error => {
            console.error('Error loading notices:', error);
            contentContainer.innerHTML = '<h2>Error loading notices.</h2>';
        });

} 
// Code for Fee Structure Page
    else if (contentId === 'feeStructure') {
        // show a quick loading message
        contentContainer.innerHTML = '<p>Loading fee structure…</p>';

        fetch('fees.json')
            .then(res => res.json())
            .then(data => {
                let html = `
<div class="mid">
  <div class="fees-section">
    <h2>Fee Structure</h2>
    
    <!-- Instructions Section -->
    <div class="container">
    <div class="instructions">
    <p><strong>Instructions:</strong></p>
    <p>1. Payment Deadlines - All fees must be paid within due date as applicable to avoid late penalties.</p>
    <p>2. Late Payment Penalty - A late fee of ₹100 per day will be applied for payments received after the due date.</p>
    <p>3. Refund Policy - Fees once paid are non-refundable, except in cases of relocation outside the city (submission of relevant proof required).</p>
    <p>4. Installment Plan - You may opt to pay tuition fees in up to three equal installments—April, August, and December—without additional interest.</p>
    <p>5. Form Submission - The fee payment receipt must be submitted to the class teacher within three days of payment.</p>
    <p>6. Fee Structure Review - The school reserves the right to revise fees annually; parents will be notified at least one month in advance of any changes.</p>
    <p>6. Contact for Queries - For any fee-related queries please contact the Accounts Office.</p>
    </div>
    </div>
       
    <p class="fees-subtitle">${data.subtitle}</p>
    <div class="table-responsive">
      <table>
        <thead><tr>`;

                data.columns.forEach(col => {
                    html += `<th>${col}</th>`;
                });
                html += `</tr></thead><tbody>`;

                data.rows.forEach(row => {
                    html += `<tr>`;
                    row.forEach(cell => {
                        html += (typeof cell === 'number')
                            ? `<td>₹${cell.toLocaleString()}</td>`
                            : `<td>${cell}</td>`;
                    });
                    html += `</tr>`;
                });

                html += `</tbody></table>
    </div>
  </div>
</div>`;

                contentContainer.innerHTML = html;
            })
            .catch(err => {
                console.error('Error loading fees:', err);
                contentContainer.innerHTML = '<h2>Error loading fee structure.</h2>';
            });
    }

// timetable dynamic html page load start
else if (contentId === 'timeTable') {
    // Show loading message
    contentContainer.innerHTML = '<p>Loading timetable...</p>';

    fetch('time_table.html')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            contentContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading time_table.html:', error);
            contentContainer.innerHTML = '<h2>Error loading timetable.</h2>';
        });
}

// timetable dynamic html page load end 
else if (contentData[contentId]) {
    contentContainer.innerHTML = contentData[contentId];

// Call data loading function for faculties or toppers
    if (contentId === 'faculties') {
        loadFacultyData();
    } else if (contentId === 'toppers') {
        loadToppersData();
    }

} else {
    contentContainer.innerHTML = '<h2>Content not found</h2>';
}
// *********Index.html Page content ends here *********************

//************************************** * Loading Header Footer

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


// TOPPERS AND FACULTY DATA
document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM fully loaded");
});

// ****** ✅ Load Faculty Data ONLY when "Faculties" menu is clicked
async function loadFacultyData() {
    const contentContainer = document.getElementById("content-container");

    // Clear previous content before loading new data
    contentContainer.innerHTML = `<h2>Our Faculties</h2><div id="facultyContainer"></div>`;

    try {
        const response = await fetch("facultyData.json");
        if (!response.ok) throw new Error("Failed to fetch faculty data.");

        const facultyData = await response.json();
        const container = document.getElementById("facultyContainer");

        facultyData.forEach((faculty) => {
            const card = document.createElement("div");
            card.className = "faculty-card";
            card.innerHTML = `
                <img src="${faculty.image}" alt="Faculty Image">
                <h3>${faculty.name}</h3>
                <p>${faculty.title}</p>
            `;
            container.appendChild(card);
        });

        console.log("✅ Faculty data loaded successfully.");
    } catch (error) {
        console.error("Error loading faculty data:", error);
    }
}

// ************************************** 
//// ✅ Load Toppers Data ONLY when "Toppers" menu is clicked
async function loadToppersData() {
    const contentContainer = document.getElementById("content-container");
    const sessionsContainer = document.getElementById("sessionsContainer");
    const toppersContainer = document.getElementById("toppersContainer");

    // Clear previous content before loading new data
    sessionsContainer.innerHTML = '';
    toppersContainer.innerHTML = '';

    try {
        const response = await fetch("toppersData.json");
        if (!response.ok) throw new Error("Failed to fetch toppers data.");

        const toppersData = await response.json();

        // Dynamically create session buttons
        for (const year in toppersData) {
            if (!toppersData.hasOwnProperty(year)) continue;

            const button = document.createElement('button');
            button.textContent = `Session ${year}`;
            button.className = 'session-button';
            button.addEventListener('click', () => showToppersForSession(year, toppersData[year]));

            sessionsContainer.appendChild(button);
        }

        console.log("✅ Toppers data loaded successfully.");
    } catch (error) {
        console.error("Error loading toppers data:", error);
    }
}

// Function to show toppers for the selected session
function showToppersForSession(year, toppers) {
    const toppersContainer = document.getElementById("toppersContainer");

    // Clear previous toppers
    toppersContainer.innerHTML = '';

    const sessionHeader = document.createElement('h2');
    sessionHeader.textContent = `Toppers of ${year}`;
    toppersContainer.appendChild(sessionHeader);

    // Display toppers for the selected session
    toppers.forEach(topper => {
        const card = document.createElement("div");
        card.className = "toppers-card";
        card.innerHTML = `
            <img src="${topper.image}" 
                 onerror="this.src='images/default_profile.png'" 
                 alt="${topper.name}"
                 class="toppers-image">
            <h4>${topper.name}</h4>
            <p>Class: ${topper.class}</p>
            <p>Rank: ${topper.rank}</p>
        `;
        toppersContainer.appendChild(card);
    });
}



//************************************** * Script for Photo Gallery Page Handling
//************************************** * JavaScript for handling both Image and Video Gallery
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

//***************************** NoticeBoard Page

// Function to Display Noticeboard
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




// ************************************** *RTE PAGE
// ************************************** *RTE Application Process

// Inject the HTML into the page dynamically
document.getElementById('applicationSection').innerHTML = applicationProcess;

// Add event listener for the "Click here" link
document.getElementById('viewPdf').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent default link behavior

    const pdfUrl = "/Documents/rte_time_frame.pdf"; // Path to your PDF
    const pdfViewer = document.getElementById('pdfViewer');
    const pdfContainer = document.getElementById('pdfContainer');

    pdfViewer.src = pdfUrl; // Set the iframe source
    pdfContainer.classList.remove('hidden'); // Remove the hidden class to show the container
});


// ***********************************************Admission Page
// Complete list of states and their districts
// Define states and their respective districts
// State and district data
const stateDistricts = {
    "Andhra Pradesh": ["Anantapur", "Chittoor", "East Godavari"],
    "Delhi": ["Central Delhi", "New Delhi", "North Delhi"],
    "Goa": ["North Goa", "South Goa"],
    "Rajasthan": ["Ajmer", "Alwar", "Barmer", "Jaipur"],
    "Uttar Pradesh": ["Agra", "Aligarh", "Lucknow", "Varanasi"],
};

// DOM elements
const stateSelect = document.getElementById("state");
const districtSelect = document.getElementById("district");

// Function to populate state dropdown
function populateStates() {
    for (let state in stateDistricts) {
        const option = document.createElement("option");
        option.value = state;
        option.textContent = state;
        stateSelect.appendChild(option);
    }
}

// Function to populate district dropdown based on state
function populateDistricts() {
    const selectedState = stateSelect.value;
    districtSelect.innerHTML = '<option value="">Select District</option>'; // Reset districts

    if (selectedState && stateDistricts[selectedState]) {
        stateDistricts[selectedState].forEach((district) => {
            const option = document.createElement("option");
            option.value = district;
            option.textContent = district;
            districtSelect.appendChild(option);
        });
    }
}

// Add event listeners
document.addEventListener("DOMContentLoaded", populateStates); // Load states on page load
stateSelect.addEventListener("change", populateDistricts);


// School Pro login page;
document.addEventListener("DOMContentLoaded", function () {
    let generatedOTP = null; // Declare globally for proper scope handling

    document.getElementById("sendOtpBtn").addEventListener("click", sendOTP);
    document.getElementById("loginForm").addEventListener("submit", validateOTP);

    async function sendOTP() {
        const mobileNumber = document.getElementById('mobno').value.trim();

        if (mobileNumber.length !== 10 || isNaN(mobileNumber)) {
            alert("Please enter a valid 10-digit mobile number.");
            return;
        }

        // Generate 6-digit OTP
        generatedOTP = Math.floor(100000 + Math.random() * 900000);
        console.log("Generated OTP:", generatedOTP);
        alert("Your OTP is: " + generatedOTP);

        document.getElementById('hideotp').style.display = 'block'; // Show OTP section

        // SMS API details
        const baseUrl = "";
        const apiKey = "";
        const senderID = "";
        const message = encodeURIComponent(`Dear user, Your OTP to login SchoolPro is ${generatedOTP}. Shri Siddhi Vinayak Shikshan Sansthan`);
        const apiUrl = `${baseUrl}?api_key=${apiKey}&method=sms&message=${message}&to=91${mobileNumber}&sender=${senderID}`;

        try {
            const response = await fetch(apiUrl, { method: "POST" });
            const textResponse = await response.text();
            console.log("API Response:", textResponse);

            if (textResponse.toLowerCase().includes("success")) {
                alert("OTP sent successfully!");
            } else {
                alert("Failed to send OTP. Response: " + textResponse);
            }
        } catch (error) {
            console.error("Error sending OTP:", error);
            alert("Error sending OTP: " + error.message);
        }
    }

    function validateOTP(event) {
        const enteredOTP = document.getElementById('otp').value;

        if (!generatedOTP) {
            alert("Please request an OTP first.");
            event.preventDefault();
            return;
        }

        if (enteredOTP != generatedOTP) {
            alert("Invalid OTP! Please try again.");
            event.preventDefault();
        }
    }
});
}


//******* */ Admin Login Handler
document.addEventListener('submit', function(event) {
    if (event.target.closest('.adminloginsection form')) {
        event.preventDefault(); // Prevent default form submit

        const email = event.target.querySelector('input[type="email"]').value;
        const password = event.target.querySelector('input[type="password"]').value;

        // Dummy check – Replace with server-side check via fetch() or AJAX
        if (email === "aks01240@gmail.com" && password === "1") {
            loadAdminDashboard(); // Function you define to load dashboard
        } else {
            alert("Invalid credentials. Please try again.");
        }
    }
});

// Loading Admin Dashboard
function loadAdminDashboard() {
    const contentContainer = document.getElementById('content-container');
    contentContainer.innerHTML = `
        <div class="admin-dashboard">

        <div class="sidebar">
        <h4>Menu</h4>
        <button onclick="adminLogout()">Logout</button>
        <button onclick="loadAdminSection('addNotice')">Add Notice</button>
        <button onclick="loadAdminSection('manageToppers')">Manage Toppers</button>
        <button onclick="loadAdminSection('gallery')">Gallery</button>
        <button onclick="loadAdminSection('faculty')">Faculty</button>
        <button onclick="loadAdminSection('feeStructure')">Fee Structure</button>
    </div>

    <div class="main-content">
        <h2>Welcome Admin</h2>
        <div id="admin-section-content">
            <p>Select a section from the left to manage it.</p>
        </div>
    </div>

    `;
}


// After Login to Admin Dashboard
function loadAdminSection(sectionId) {
    const adminSection = document.getElementById('admin-section-content');

    if (sectionId === 'addNotice') {
        adminSection.innerHTML = `
            <h3>Add New Notice</h3>
            <form id="noticeForm">
                <input type="text" id="title" placeholder="Title" required><br><br>
                <textarea id="description" placeholder="Description" required></textarea><br><br>
                <input type="text" id="author" placeholder="Author" required><br><br>
                <button type="submit">Submit</button>
            </form>
            <div id="notice-status"></div>
        `;
    
        document.getElementById('noticeForm').addEventListener('submit', function (e) {
            e.preventDefault();
    
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            const author = document.getElementById('author').value;
    
            const today = new Date();
            const date = String(today.getDate()).padStart(2, '0');
            const month = today.toLocaleString('default', { month: 'short' });
            const publish_date = `${date}-${month}-${today.getFullYear()}`;
    
            const noticeData = {
                date,
                month,
                title,
                description,
                author,
                publish_date
            };
    
            fetch('save_notice.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(noticeData)
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('notice-status').innerText = data.message;
            })
            .catch(err => {
                document.getElementById('notice-status').innerText = "Failed to add notice.";
                console.error(err);
            });
        });
    }
    

    else if (sectionId === 'manageToppers') {
        adminSection.innerHTML = `
            <h3>Manage Toppers</h3>
            <p>Topper management form will go here...</p>
        `;
    }

    else if (sectionId === 'gallery') {
        adminSection.innerHTML = `
            <h3>Gallery</h3>
            <p>Image upload and gallery tools will go here...</p>
        `;
    }

    else if (sectionId === 'faculty') {
        adminSection.innerHTML = `
            <h3>Faculty</h3>
            <p>Faculty management section under development.</p>
        `;
    }

    else if (sectionId === 'feeStructure') {
        adminSection.innerHTML = `
            <h3>Fee Structure</h3>
            <p>Fee structure editing tools will be added here.</p>
        `;
    }
}


