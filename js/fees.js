// FEE STRUCTURE PAGE LOGIC
function loadFeeStructure() {
    const contentContainer = document.getElementById("content-container");
    contentContainer.innerHTML = "<p>Loading fee structure…</p>";

    fetch("fees.json")
        .then(res => res.json())
        .then(data => {
            let table = `<table><thead><tr>`;
            data.columns.forEach(c => table += `<th>${c}</th>`);
            table += `</tr></thead><tbody>`;
            data.rows.forEach(row => {
                table += "<tr>";
                row.forEach(cell => {
                    table += `<td>${typeof cell==="number" ? "₹"+cell.toLocaleString() : cell}</td>`;
                });
                table += "</tr>";
            });
            table += "</tbody></table>";
            contentContainer.innerHTML = table;
        })
        .catch(err => {
            console.error("Error loading fees:", err);
            contentContainer.innerHTML = "<h2>Error loading fee structure.</h2>";
        });
}
