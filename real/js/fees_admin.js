function loadFeeStructureEditor() {
    fetch('real/php/fees_list.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('feeTableContainer');
            let html = `<table border="1" class="fee-table"><thead><tr>`;
            
            data.columns.forEach(col => {
                html += `<th>${col}</th>`;
            });
            html += `</tr></thead><tbody>`;

            data.rows.forEach((row, rowIndex) => {
                html += `<tr>`;
                row.forEach((cell, colIndex) => {
                    html += `<td><input type="text" value="${cell}" data-row="${rowIndex}" data-col="${colIndex}"></td>`;
                });
                html += `</tr>`;
            });

            html += `</tbody></table>`;
            container.innerHTML = html;

            // Save button logic
            document.getElementById('saveFeesBtn').addEventListener('click', function () {
                const inputs = container.querySelectorAll('input');
                const updatedRows = Array(data.rows.length).fill().map(() => []);
                
                inputs.forEach(input => {
                    const r = parseInt(input.dataset.row);
                    const c = parseInt(input.dataset.col);
                    updatedRows[r][c] = isNaN(input.value) ? input.value : Number(input.value);
                });

                const updatedData = {
                    ...data,
                    rows: updatedRows
                };

                fetch('real/php/update_fees.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(updatedData)
                })
                .then(res => res.json())
                .then(response => {
                    document.getElementById('feeUpdateStatus').innerText = response.message;
                    // Clear message after 10 seconds
                    setTimeout(() => {
                        document.getElementById('feeUpdateStatus').innerText = '';
                    }, 10000);
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('feeUpdateStatus').innerText = "Update failed!";
                });
            });
        });
}
