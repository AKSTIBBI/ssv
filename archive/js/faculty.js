// FACULTY PAGE LOGIC
async function loadFacultyData() {
    const contentContainer = document.getElementById("content-container");
    contentContainer.innerHTML = `<h2>Our Faculties</h2><div id="facultyContainer"></div>`;

    try {
        const res = await fetch("facultyData.json");
        const facultyData = await res.json();
        const container = document.getElementById("facultyContainer");
        facultyData.forEach(f => {
            const card = document.createElement("div");
            card.className = "faculty-card";
            card.innerHTML = `<img src="${f.image}" alt="${f.name}"><h3>${f.name}</h3><p>${f.title}</p>`;
            container.appendChild(card);
        });
    } catch (err) {
        console.error("Error loading faculty data:", err);
        contentContainer.innerHTML = "<h2>Error loading faculty list.</h2>";
    }
}
