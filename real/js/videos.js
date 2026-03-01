function initVideoGallery() {
  fetch('real/json/videos.json')
    .then(response => response.json())
    .then(videos => {
      const container = document.getElementById('video-gallery');
      if (!container || !Array.isArray(videos)) return;

      let html = '<div class="gallery-grid">';
      videos.forEach((video, index) => {
        const youtubeAttr = video.youtube ? `data-youtube="true" data-src="${video.video_path}"` : `data-src="${video.video_path}"`;
        html += `
          <div class="gallery-item" data-type="video" data-category="${video.category}" ${youtubeAttr}>
            <div class="video-thumbnail">
              <img src="${video.image_path}" alt="${video.title}" onclick="openLightbox(${index}, 'video')">
              <div class="play-icon"><i class="fas fa-play"></i></div>
            </div>
            <p class="image-title">${video.title}</p>
          </div>`;
      });
      html += '</div>';

      container.innerHTML = html;
      initVideoGalleryFilters();
      bindLightbox();
    })
    .catch(err => console.error('Error loading videos:', err));
}

function initVideoGalleryFilters() {
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
