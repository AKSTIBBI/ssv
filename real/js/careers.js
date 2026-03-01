// careers.js

function initializeCareersJS() {
    // Toggle opening details
    const openingHeaders = document.querySelectorAll('.opening-header');
    openingHeaders.forEach(header => {
        header.addEventListener('click', function () {
            const details = this.nextElementSibling;
            if (details.style.display === 'block') {
                details.style.display = 'none';
                this.classList.remove('active');
            } else {
                details.style.display = 'block';
                this.classList.add('active');
            }
        });
    });

    // Show/hide "Other Position" field based on selection
    const positionSelect = document.getElementById('position');
    const otherPositionGroup = document.getElementById('other-position-group');

    if (positionSelect && otherPositionGroup) {
        positionSelect.addEventListener('change', function () {
            if (this.value === 'Other') {
                otherPositionGroup.style.display = 'block';
                document.getElementById('other_position').setAttribute('required', 'required');
            } else {
                otherPositionGroup.style.display = 'none';
                document.getElementById('other_position').removeAttribute('required');
            }
        });
    }

    // Accordion functionality
    const accordionHeaders = document.querySelectorAll('.accordion-header');

    accordionHeaders.forEach(header => {
        header.addEventListener('click', function () {
            this.classList.toggle('active');

            const content = this.nextElementSibling;

            if (content.style.maxHeight) {
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });
    });

    // Form validation and submission
    const careerForm = document.getElementById('careerApplicationForm');

    if (careerForm) {
        careerForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const fullName = document.getElementById('full_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const position = document.getElementById('position').value;
            const qualification = document.getElementById('qualification').value.trim();
            const experience = document.getElementById('experience').value.trim();
            const coverLetter = document.getElementById('cover_letter').value.trim();
            const resumeLink = document.getElementById('resume').value.trim();
            const resumeFileInput = document.getElementById('resume_file');
            const resumeFile = resumeFileInput ? resumeFileInput.files[0] : null;
            const terms = document.getElementById('terms').checked;

            // basic required fields
            if (!fullName || !email || !phone || !position || !qualification ||
                !experience || !coverLetter || !terms) {
                alert('Please fill all required fields and accept the terms.');
                return;
            }

            if (position === 'Other') {
                const otherPosition = document.getElementById('other_position').value.trim();
                if (!otherPosition) {
                    alert('Please specify the position you are applying for.');
                    return;
                }
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address.');
                return;
            }

            const phonePattern = /^\d{10}$/;
            if (!phonePattern.test(phone.replace(/[^0-9]/g, ''))) {
                alert('Please enter a valid 10-digit phone number.');
                return;
            }

            // resume validation: at least one of link or file
            if (!resumeLink && !resumeFile) {
                alert('Please provide either a resume link or upload a file.');
                return;
            }

            if (resumeLink) {
                const urlPattern = /^(https?:\/\/)?(www\.)?(drive\.google\.com|dropbox\.com).*$/;
                if (!urlPattern.test(resumeLink)) {
                    alert('Please provide a valid Google Drive or Dropbox link for your resume.');
                    return;
                }
            }

            if (resumeFile) {
                const allowed = ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!allowed.includes(resumeFile.type)) {
                    alert('Resume file must be PDF or Word document.');
                    return;
                }
                const maxSize = 5 * 1024 * 1024;
                if (resumeFile.size > maxSize) {
                    alert('Resume file must be less than 5MB.');
                    return;
                }
            }

            // build form data and submit to server
            const formData = new FormData(this);

            fetch('real/php/career_application.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    careerForm.reset();
                } else {
                    alert('Failed: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error submitting application. Please try again later.');
            });
        });
    }
}
