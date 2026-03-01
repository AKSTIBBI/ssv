// TOPPERS PAGE LOGIC
async function loadToppersData() {
    const sessionsContainer = document.getElementById("sessionsContainer");
    const toppersContainer = document.getElementById("toppersContainer");
    sessionsContainer.innerHTML = "";
    toppersContainer.innerHTML = "";

    try {
        const res = await fetch("toppersData.json");
        const toppersData = await res.json();

        Object.keys(toppersData).forEach(year => {
            const btn = document.createElement("button");
            btn.textContent = `Session ${year}`;
            btn.className = "session-button";
            btn.addEventListener("click", () => showToppersForSession(year, toppersData[year]));
            sessionsContainer.appendChild(btn);
        });
    } catch (err) {
        console.error("Error loading toppers data:", err);
    }
}

function showToppersForSession(year, list) {
    const toppersContainer = document.getElementById("toppersContainer");
    toppersContainer.innerHTML = `<h2>Toppers of ${year}</h2>`;
    list.forEach(t => {
        const card = document.createElement("div");
        card.className = "toppers-card";
        card.innerHTML = `<img src="${t.image}" alt="${t.name}"><h4>${t.name}</h4><p>Class: ${t.class}</p><p>Rank: ${t.rank}</p>`;
        toppersContainer.appendChild(card);
    });
}
