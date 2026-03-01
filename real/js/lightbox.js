// real/js/lightbox.js

document.addEventListener('DOMContentLoaded', function () {
  const lightbox = document.querySelector('.lightbox');
  const lightboxContent = document.querySelector('.lightbox-content');
  const lightboxClose = document.querySelector('.lightbox-close');
  const prevBtn = document.querySelector('.lightbox-nav .prev');
  const nextBtn = document.querySelector('.lightbox-nav .next');

  let currentItems = [];
  let currentIndex = 0;
    window.bindLightbox = function () {
      const items = document.querySelectorAll('.gallery-item');

      items.forEach((item, index) => {
        const type = item.dataset.type;
        item.addEventListener('click', () => {
          openLightbox(index, type);
        });
      });
    };


  window.openLightbox = function (index, type) {
    currentItems = Array.from(document.querySelectorAll(`.gallery-item[data-type='${type}']`));
    currentIndex = index;
    showLightboxContent(currentItems[currentIndex], type);
    lightbox.style.display = 'block';
  };

  function showLightboxContent(item, type) {
    lightboxContent.innerHTML = '';

    const videoSrc = item.dataset.src;
    const isYouTube = item.dataset.youtube === 'true';

    if (type === 'image') {
      const img = document.createElement('img');
      img.src = item.querySelector('img').src;
      img.alt = item.querySelector('img').alt || 'Gallery Image';
      lightboxContent.appendChild(img);
    } else if (type === 'video') {
      if (isYouTube) {
        const iframe = document.createElement('iframe');
        iframe.src = videoSrc;
        iframe.width = '100%';
        iframe.height = '480';
        iframe.allowFullscreen = true;
        iframe.frameBorder = '0';
        lightboxContent.appendChild(iframe);
      } else {
        const video = document.createElement('video');
        video.controls = true;
        video.autoplay = true;

        const source = document.createElement('source');
        source.src = videoSrc;
        source.type = 'video/mp4';

        video.appendChild(source);
        lightboxContent.appendChild(video);
      }
    }
  }

  function closeLightbox() {
    lightbox.style.display = 'none';
    lightboxContent.innerHTML = '';
  }

  function showPrev() {
    currentIndex = (currentIndex - 1 + currentItems.length) % currentItems.length;
    showLightboxContent(currentItems[currentIndex], currentItems[currentIndex].dataset.type);
  }

  function showNext() {
    currentIndex = (currentIndex + 1) % currentItems.length;
    showLightboxContent(currentItems[currentIndex], currentItems[currentIndex].dataset.type);
  }

if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
if (prevBtn) prevBtn.addEventListener('click', showPrev);
if (nextBtn) nextBtn.addEventListener('click', showNext);


  lightbox.addEventListener('click', function (e) {
    if (e.target === lightbox) {
      closeLightbox();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (lightbox.style.display === 'block') {
      if (e.key === 'ArrowLeft') showPrev();
      else if (e.key === 'ArrowRight') showNext();
      else if (e.key === 'Escape') closeLightbox();
    }
  });
});
