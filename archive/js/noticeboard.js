// NOTICEBOARD PAGE LOGIC
function loadNoticeBoard() {
    const contentContainer = document.getElementById("content-container");
    contentContainer.innerHTML = '<p>Loading notices…</p>';

    fetch("json/notices.json")
        .then(res => res.json())
        .then(notices => {
            let html = `
            <div class="notice-scroller">`;
            notices.forEach(n => {
                html += `
                <div class="notice-item">
                  <div class="notice-date">
                    <h5>${n.date}</h5><span>${n.month}</span>
                  </div>
                  <div class="notice-details">
                    <p class="notice-title">${n.title}</p>
                    <p>${n.description}</p>
                    <ul class="notice-meta">
                      <li>👤 ${n.author}</li>
                      <li>📅 ${n.publish_date}</li>
                    </ul>
                  </div>
                </div>`;
            });
            html += `</div>`;
            contentContainer.innerHTML = html;
        })
        .catch(err => {
            console.error("Error loading notices:", err);
            contentContainer.innerHTML = "<h2>Error loading notices.</h2>";
        });
}
