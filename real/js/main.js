// JavaScript for frontend functionality

// Handle navigation menu for mobile
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const menuToggle = document.getElementById('menu-toggle');
    const navigation = document.querySelector('.navigation');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navigation.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
    }

    // Marquee for notices
    const noticesMarquee = document.getElementById('noticesMarquee');
    if (noticesMarquee) {
        fetch('/api/notices')
            .then(response => response.json())
            .then(notices => {
                if (notices && notices.length > 0) {
                    let marqueeContent = '';
                    notices.forEach(notice => {
                        marqueeContent += `<span class="red">NEW</span> ${notice.title} | `;
                    });
                    noticesMarquee.innerHTML = marqueeContent;
                } else {
                    noticesMarquee.innerHTML = "Welcome to SHRI SIDDHI VINAYAK EDUCATIONAL TRUST TIBBI";
                }
            })
            .catch(error => {
                console.error('Error loading notices for marquee:', error);
                noticesMarquee.innerHTML = "Welcome to SHRI SIDDHI VINAYAK EDUCATIONAL TRUST TIBBI";
            });
    }

    // Lightbox for Gallery
    const galleryItems = document.querySelectorAll('.gallery-item');
    if (galleryItems.length > 0) {
        galleryItems.forEach((item, index) => {
            item.addEventListener('click', function() {
                openLightbox(index, this.dataset.type || 'image');
            });
        });
    }

    // Session buttons for Toppers page
    const sessionButtons = document.querySelectorAll('.session-button');
    if (sessionButtons.length > 0) {
        sessionButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                sessionButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                // Get the year from data attribute
                const year = this.dataset.year;
                
                // Show toppers for the selected year
                showToppersForYear(year);
            });
        });

        // Activate the first button by default
        sessionButtons[0].click();
    }
});

// Lightbox functionality for gallery
let currentIndex = 0;
let galleryItems = [];
let currentType = 'image';

function openLightbox(index, type) {
    currentIndex = index;
    currentType = type || 'image';
    
    // Get all gallery items of the same type
    galleryItems = Array.from(document.querySelectorAll(`.gallery-item[data-type="${currentType}"]`));
    
    const lightbox = document.querySelector('.lightbox') || createLightbox();
    const lightboxContent = lightbox.querySelector('.lightbox-content');
    
    // Clear previous content
    lightboxContent.innerHTML = '';
    
    const item = galleryItems[currentIndex];
    const src = item.querySelector('img').src;
    const title = item.querySelector('.image-title')?.textContent || '';
    
    if (currentType === 'video') {
        // If it's a video, create a video element
        const video = document.createElement('video');
        video.controls = true;
        video.autoplay = true;
        
        const source = document.createElement('source');
        source.src = src;
        source.type = 'video/mp4';
        
        video.appendChild(source);
        lightboxContent.appendChild(video);
    } else {
        // If it's an image, create an img element
        const img = document.createElement('img');
        img.src = src;
        img.alt = title;
        lightboxContent.appendChild(img);
    }
    
    // Add title if available
    if (title) {
        const titleElement = document.createElement('p');
        titleElement.className = 'lightbox-title';
        titleElement.textContent = title;
        lightboxContent.appendChild(titleElement);
    }
    
    // Show the lightbox
    lightbox.classList.add('active');
    
    // Prevent scrolling
    document.body.style.overflow = 'hidden';
}

function createLightbox() {
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    
    const content = document.createElement('div');
    content.className = 'lightbox-content';
    lightbox.appendChild(content);
    
    // Close button
    const closeBtn = document.createElement('span');
    closeBtn.className = 'lightbox-close';
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', closeLightbox);
    lightbox.appendChild(closeBtn);
    
    // Navigation buttons (only if multiple items)
    if (galleryItems.length > 1) {
        const nav = document.createElement('div');
        nav.className = 'lightbox-nav';
        
        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '&#10094;';
        prevBtn.addEventListener('click', () => navigateLightbox(-1));
        
        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '&#10095;';
        nextBtn.addEventListener('click', () => navigateLightbox(1));
        
        nav.appendChild(prevBtn);
        nav.appendChild(nextBtn);
        lightbox.appendChild(nav);
    }
    
    // Close lightbox when clicking outside content
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });
    
    // Add keyboard navigation
    document.addEventListener('keydown', handleLightboxKeydown);
    
    document.body.appendChild(lightbox);
    return lightbox;
}

function closeLightbox() {
    const lightbox = document.querySelector('.lightbox');
    if (lightbox) {
        lightbox.classList.remove('active');
        
        // Re-enable scrolling
        document.body.style.overflow = '';
        
        // Remove keyboard event listener
        document.removeEventListener('keydown', handleLightboxKeydown);
    }
}

function navigateLightbox(direction) {
    currentIndex = (currentIndex + direction + galleryItems.length) % galleryItems.length;
    openLightbox(currentIndex, currentType);
}

function handleLightboxKeydown(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    } else if (e.key === 'ArrowLeft') {
        navigateLightbox(-1);
    } else if (e.key === 'ArrowRight') {
        navigateLightbox(1);
    }
}

// Function to show toppers for selected year
function showToppersForYear(year) {
    const toppersContainer = document.getElementById('toppersContainer');
    if (!toppersContainer) return;
    
    // Show loading indicator
    toppersContainer.innerHTML = '<p>Loading...</p>';
    
    // Fetch toppers data for the selected year
    fetch(`/api/toppers/${year}`)
        .then(response => response.json())
        .then(toppers => {
            if (toppers && toppers.length > 0) {
                let toppersHTML = '';
                toppers.forEach(topper => {
                    toppersHTML += `
                        <div class="toppers-card">
                            <img src="${topper.image_path}" 
                                 onerror="this.src='/static/images/default_profile.png'" 
                                 alt="${topper.name}"
                                 class="toppers-image">
                            <h4>${topper.name}</h4>
                            <p>Class: ${topper.class_name}</p>
                            <p>Rank: ${topper.rank}</p>
                        </div>
                    `;
                });
                toppersContainer.innerHTML = toppersHTML;
            } else {
                toppersContainer.innerHTML = '<p>No toppers found for this year.</p>';
            }
        })
        .catch(error => {
            console.error('Error loading toppers:', error);
            toppersContainer.innerHTML = '<p>Error loading toppers. Please try again later.</p>';
        });
}

// Form submission for contact form
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(contactForm);
            
            // Submit the form data via AJAX
            fetch('/api/contact', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    contactForm.reset();
                    showAlert('Your message has been sent successfully!', 'success');
                } else {
                    showAlert('Error sending message. Please try again.', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error sending message. Please try again.', 'danger');
            });
        });
    }
});

// Function to show alert messages
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.container') || document.body;
    container.insertBefore(alertDiv, container.firstChild);
    
    // Remove the alert after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
