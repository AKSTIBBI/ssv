// JavaScript for admin dashboard functionality

document.addEventListener('DOMContentLoaded', function() {
    // Initialize any charts on the dashboard
    initializeCharts();
    
    // Setup form submission for various admin forms
    setupFormSubmissions();
    
    // Handle file uploads
    setupFileUploads();
    
    // Handle delete confirmations
    setupDeleteConfirmations();
    
    // Handle dynamic form fields
    setupDynamicFormFields();
});

// Initialize charts for the admin dashboard
function initializeCharts() {
    const statsChart = document.getElementById('statsChart');
    if (statsChart) {
        // Fetch statistics data
        fetch('/api/admin/stats')
            .then(response => response.json())
            .then(data => {
                new Chart(statsChart, {
                    type: 'bar',
                    data: {
                        labels: ['Notices', 'Faculties', 'Toppers', 'Gallery Items'],
                        datasets: [{
                            label: 'Count',
                            data: [
                                data.notice_count || 0,
                                data.faculty_count || 0,
                                data.topper_count || 0,
                                data.gallery_count || 0
                            ],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error loading stats:', error));
    }
}

// Setup form submissions for admin forms
function setupFormSubmissions() {
    // Find all forms with class 'admin-form'
    const forms = document.querySelectorAll('.admin-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const url = form.getAttribute('action');
            const method = form.getAttribute('method');
            
            fetch(url, {
                method: method,
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message || 'Operation successful!', 'success');
                    
                    // Redirect or refresh if needed
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1000);
                    }
                } else {
                    showAlert(data.message || 'Operation failed!', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred. Please try again.', 'danger');
            });
        });
    });
}

// Setup file uploads for admin forms
function setupFileUploads() {
    const fileInputs = document.querySelectorAll('.custom-file-input');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Update file name display
            const fileName = this.files[0].name;
            const label = this.nextElementSibling;
            label.textContent = fileName;
            
            // Show preview if it's an image
            if (this.files[0].type.match('image.*')) {
                const preview = document.getElementById(this.dataset.preview);
                if (preview) {
                    preview.src = URL.createObjectURL(this.files[0]);
                    preview.style.display = 'block';
                }
            }
        });
    });
}

// Setup delete confirmation dialogs
function setupDeleteConfirmations() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const confirmDelete = confirm('Are you sure you want to delete this item? This action cannot be undone.');
            
            if (confirmDelete) {
                const url = this.getAttribute('href');
                
                fetch(url, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message || 'Item deleted successfully!', 'success');
                        
                        // Remove the item from the DOM or refresh the page
                        const tableRow = this.closest('tr');
                        if (tableRow) {
                            tableRow.remove();
                        } else {
                            location.reload();
                        }
                    } else {
                        showAlert(data.message || 'Failed to delete the item.', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('An error occurred. Please try again.', 'danger');
                });
            }
        });
    });
}

// Setup dynamic form fields (add/remove fields)
function setupDynamicFormFields() {
    // Add field button
    const addButtons = document.querySelectorAll('.add-field-btn');
    
    addButtons.forEach(button => {
        button.addEventListener('click', function() {
            const fieldsContainer = document.getElementById(this.dataset.container);
            const fieldTemplate = document.getElementById(this.dataset.template);
            
            if (fieldsContainer && fieldTemplate) {
                const newField = fieldTemplate.content.cloneNode(true);
                fieldsContainer.appendChild(newField);
                
                // Re-bind events for the new field
                setupFileUploads();
                
                // Setup remove button for the new field
                const removeButton = fieldsContainer.lastElementChild.querySelector('.remove-field-btn');
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        this.closest('.dynamic-field').remove();
                    });
                }
            }
        });
    });
    
    // Setup existing remove buttons
    const removeButtons = document.querySelectorAll('.remove-field-btn');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.dynamic-field').remove();
        });
    });
}

// Function to show alert messages in the admin panel
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const alertContainer = document.querySelector('.alert-container');
    if (alertContainer) {
        alertContainer.innerHTML = '';
        alertContainer.appendChild(alertDiv);
        
        // Remove the alert after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
}

// Function to handle date formatting
function formatDate(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

// Function to generate slug from a string
function generateSlug(text) {
    return text
        .toLowerCase()
        .replace(/[^\w ]+/g, '')
        .replace(/ +/g, '-');
}

// Initialize WYSIWYG editors for rich text areas
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('.rich-text-editor');
    
    textareas.forEach(textarea => {
        // Initialize a rich text editor (implementation depends on your chosen library)
        // This is just a placeholder - you'll need to replace with actual code for your chosen editor
        // Example: CKEDITOR.replace(textarea.id);
    });
});
