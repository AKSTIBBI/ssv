// Immediately execute script since page is injected dynamically
(function() {
    // Accordion functionality (if used anywhere)
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            this.classList.toggle('active');
            const content = this.nextElementSibling;
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });
    });

    function attachFormHandler() {
        const admissionForm = document.getElementById('admissionEnquiryForm');
        if (!admissionForm) return;

        admissionForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // client-side validation (same as before)
            const studentName = document.getElementById('student_name').value;
            const dob = document.getElementById('date_of_birth').value;
            const gender = document.getElementById('gender').value;
            const applyingClass = document.getElementById('applying_class').value;
            const parentName = document.getElementById('parent_name').value;
            const relationship = document.getElementById('relationship').value;
            const contact = document.getElementById('contact_number').value;
            const email = document.getElementById('email').value;
            const address = document.getElementById('address').value;
            const terms = document.getElementById('terms').checked;

            if (!studentName || !dob || !gender || !applyingClass || !parentName ||
                !relationship || !contact || !email || !address || !terms) {
                alert('Please fill all required fields and accept the terms.');
                return;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address.');
                return;
            }

            const phonePattern = /^\d{10}$/;
            if (!phonePattern.test(contact.replace(/[^0-9]/g, ''))) {
                alert('Please enter a valid 10-digit contact number.');
                return;
            }

            const today = new Date();
            const birthDate = new Date(dob);
            const age = today.getFullYear() - birthDate.getFullYear();

            if (applyingClass === 'Nursery' && (age < 2.5 || age > 3.5)) {
                alert('For Nursery admission, child should be between 2.5 and 3.5 years old.');
                return;
            }
            if (applyingClass === 'KG' && (age < 3.5 || age > 4.5)) {
                alert('For KG admission, child should be between 3.5 and 4.5 years old.');
                return;
            }
            if (applyingClass === 'Class 1' && (age < 5 || age > 6)) {
                alert('For Class 1 admission, child should be between 5 and 6 years old.');
                return;
            }

            // Send to backend via AJAX
            const formData = new FormData(admissionForm);
            const messageDiv = document.getElementById('admissionMessage');
            if (messageDiv) {
                messageDiv.style.display = 'none';
            }

            const endpoint = (window.location.origin || '') + '/Project_SSV_Website/real/php/admission_enquiry.php';
            console.log('[admission] submitting to', endpoint);
            fetch(endpoint, {
                method: 'POST',
                body: formData
            })
            .then(res => res.text().then(text => ({ ok: res.ok, status: res.status, text })))
            .then(({ ok, status, text }) => {
                console.log('[admission] raw response status', status, 'text:', text);
                let data = null;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    // show raw response for easier debugging
                    if (messageDiv) {
                        messageDiv.style.display = 'block';
                        messageDiv.style.backgroundColor = '#dc2626';
                        messageDiv.style.color = 'white';
                        messageDiv.textContent = 'Server returned unexpected response (status ' + status + '): ' + text;
                    } else {
                        console.error('[admission] submission failed, non-JSON response:', text);
                        alert('Server error - check console for details.');
                    }
                    return;
                }

                console.log('[admission] parsed response', data);

                if (data.success) {
                    // ✅ ALWAYS show alert on success to prevent duplicate submissions
                    alert(data.message || 'Enquiry submitted successfully');
                    admissionForm.reset();
                    console.log('[admission] submission success, reset form');
                    
                    // Also update message div if it exists
                    if (messageDiv) {
                        messageDiv.style.display = 'block';
                        messageDiv.style.backgroundColor = '#10b981';
                        messageDiv.style.color = 'white';
                        messageDiv.textContent = data.message || 'Enquiry submitted successfully';
                    }
                } else {
                    // ❌ Show error alert
                    alert('Error: ' + (data.message || 'Submission failed'));
                    console.log('[admission] submission error:', data.message);
                    
                    // Also update message div if it exists
                    if (messageDiv) {
                        messageDiv.style.display = 'block';
                        messageDiv.style.backgroundColor = '#dc2626';
                        messageDiv.style.color = 'white';
                        messageDiv.textContent = data.message || 'Submission failed';
                    }
                }
            })
            .catch(err => {
                if (messageDiv) {
                    messageDiv.style.display = 'block';
                    messageDiv.style.backgroundColor = '#dc2626';
                    messageDiv.style.color = 'white';
                    messageDiv.textContent = 'Network error: ' + err.message;
                } else {
                    alert('Network error: ' + err.message);
                }
            });
        });
    }

    attachFormHandler();
})();