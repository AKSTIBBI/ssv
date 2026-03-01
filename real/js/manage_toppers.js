document.addEventListener('DOMContentLoaded', function () {
    const yearSelect = document.getElementById('toppers-year');
    const form = document.getElementById('toppersForm');
    const status = document.getElementById('toppers-status');
    const container = document.getElementById('toppers-list');

    let toppersData = {};
    let selectedYear = yearSelect.value;

    function showStatus(message, color = 'green') {
        status.textContent = message;
        status.style.color = color;
        setTimeout(() => status.textContent = '', 10000);
    }

    function loadToppers() {
        fetch('real/php/toppers_list.php')
            .then(response => response.json())
            .then(data => {
                toppersData = data;
                updateList();
            })
            .catch(err => showStatus("Error loading toppers data.", 'red'));
    }

    function updateList() {
        container.innerHTML = '';
        const toppers = toppersData[selectedYear] || [];
        toppers.forEach((topper, index) => {
        const div = document.createElement('div');
        div.className = 'topper-entry';
        if (topper.deleted) div.style.opacity = '0.5';

        div.innerHTML = `
            <img src="${topper.image}" alt="${topper.name}">
            <p><strong>${topper.name}</strong><br>${topper.class}<br>${topper.rank}</p>
            <button data-index="${index}" class="toggle-delete-btn">
                ${topper.deleted ? 'Restore' : 'Delete'}
            </button>
            `;
            container.appendChild(div);
        });
    }

    yearSelect.addEventListener('change', () => {
        selectedYear = yearSelect.value;
        updateList();
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('toggle-delete-btn')) {
            const index = e.target.getAttribute('data-index');
            const current = toppersData[selectedYear][index];
            toppersData[selectedYear][index].deleted = !current.deleted;
            saveToppers();
        }
    });

form.addEventListener('submit', function (e) {
    e.preventDefault();
    const imageFile = document.getElementById('imageInput').files[0];

    if (!imageFile) {
        showStatus("Please select an image.", "red");
        return;
    }

    const imageData = new FormData();
    imageData.append("topperImage", imageFile);

    fetch("real/php/upload_topper_image.php", {
        method: "POST",
        body: imageData
    })
    .then(res => res.json())
    .then(imgRes => {
        if (imgRes.success) {
            const newTopper = {
                name: form.name.value,
                class: form.class.value,
                rank: form.rank.value,
                image: imgRes.imagePath,
                deleted: false
            };
            if (!toppersData[selectedYear]) toppersData[selectedYear] = [];
            toppersData[selectedYear].push(newTopper);
            saveToppers();
            form.reset();
        } else {
            showStatus("Image upload failed: " + imgRes.message, "red");
        }
    })
    .catch(() => showStatus("Image upload error.", "red"));
});


    function saveToppers() {
        fetch('real/php/save_toppers.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(toppersData)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showStatus('Toppers updated successfully!');
                    updateList();
                } else {
                    showStatus('Failed to save.', 'red');
                }
            })
            .catch(() => showStatus('Error saving data.', 'red'));
    }

    loadToppers();
});

function displayAllToppers() {
    const container = document.getElementById('toppersList');
    container.innerHTML = ""; // Clear existing content

    const years = Object.keys(toppersData).sort((a, b) => b - a); // Newest year first

    years.forEach(year => {
        const yearDiv = document.createElement('div');
        yearDiv.classList.add('year-section');

        const yearTitle = document.createElement('h4');
        yearTitle.textContent = year;
        yearDiv.appendChild(yearTitle);

        toppersData[year].forEach(topper => {
            const topperDiv = document.createElement('div');
            topperDiv.classList.add('topper-entry');
            topperDiv.innerHTML = `
                <img src="${topper.image}" alt="${topper.name}" width="80" height="80" style="object-fit: cover; border-radius: 8px; margin-right: 10px;">
                <strong>${topper.name}</strong> - ${topper.class} - <em>${topper.rank}</em>
            `;
            yearDiv.appendChild(topperDiv);
        });

        container.appendChild(yearDiv);
    });
}
