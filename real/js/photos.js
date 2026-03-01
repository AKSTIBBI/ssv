function initPhotoGallery() {
  fetch('real/php/photos_list.php')
    .then(response => response.json())
    .then(photos => {
      const container = document.getElementById('photo-gallery');
      if (!container || !Array.isArray(photos)) return;

      let html = '<div class="gallery-grid">';
      photos.forEach((photo, index) => {
        html += `
          <div class="gallery-item" data-type="image" data-category="${photo.category}">
            <img src="${photo.image_path}" alt="${photo.title}">
            <p class="image-title">${photo.title}</p>
          </div>`;
      });
      html += '</div>';

      container.innerHTML = html;
      initPhotoGalleryFilters();
      bindLightbox();
    })
    .catch(err => console.error('Error loading photos:', err));
}

function initPhotoGalleryFilters() {
  const filterButtons = document.querySelectorAll('.filter-btn');
  const galleryItems = document.querySelectorAll('.gallery-item');

  filterButtons.forEach(button => {
    button.addEventListener('click', function () {
      filterButtons.forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');

      const filter = this.dataset.filter;
      galleryItems.forEach(item => {
        item.style.display = (filter === 'all' || item.dataset.category === filter) ? 'block' : 'none';
      });
    });
  });
}
