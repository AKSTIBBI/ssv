// ADMIN LOGIN HANDLER
document.addEventListener("submit", function (event) {
    if (event.target.closest(".adminloginsection form")) {
        event.preventDefault();
        const email = event.target.querySelector('input[type="email"]').value;
        const pwd = event.target.querySelector('input[type="password"]').value;
        if (email === "aks01240@gmail.com" && pwd === "1") {
            loadTab("adminDashboard");
        } else {
            alert("Invalid credentials. Please try again.");
        }
    }
});

// ADMIN DASHBOARD SIDEBAR
function loadAdminDashboard() {
    document.getElementById("admin-section-content").innerHTML = `
        <h2>Welcome Admin</h2>
        <p>Select a section from the left to manage it.</p>
    `;
}

function adminLogout() {
    loadTab("login");
}

// Sidebar buttons
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".sidebar button").forEach(btn => {
        btn.addEventListener("click", function () {
            const sec = this.getAttribute("onclick").match(/'(.+)'/)[1];
            loadTab(sec);
        });
    });
});
