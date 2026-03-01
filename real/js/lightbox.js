// real/js/lightbox.js

document.addEventListener('DOMContentLoaded', function () {
  let currentItems = [];
  let currentIndex = 0;
  let currentType = 'image';

  function getLightboxParts() {
    const lightbox = document.querySelector('.lightbox');
    if (!lightbox) return null;

    return {
      lightbox,
      content: lightbox.querySelector('.lightbox-content'),
      close: lightbox.querySelector('.lightbox-close'),
      prev: lightbox.querySelector('.lightbox-nav .prev'),
      next: lightbox.querySelector('.lightbox-nav .next')
    };
  }

  function isLightboxOpen() {
    const parts = getLightboxParts();
    return !!(parts && parts.lightbox.style.display === 'flex');
  }

  function setLightboxOpenState(isOpen) {
    const parts = getLightboxParts();
    if (!parts || !parts.content) return;

    if (isOpen) {
      parts.lightbox.style.display = 'flex';
      requestAnimationFrame(() => parts.lightbox.classList.add('active'));
      document.body.style.overflow = 'hidden';
    } else {
      parts.lightbox.classList.remove('active');
      setTimeout(() => {
        const latest = getLightboxParts();
        if (!latest || !latest.content) return;
        latest.lightbox.style.display = 'none';
        latest.content.innerHTML = '';
      }, 180);
      document.body.style.overflow = '';
    }
  }

  function fullscreenTarget(element, fallbackUrl) {
    if (!element) {
      if (fallbackUrl) window.open(fallbackUrl, '_blank');
      return;
    }

    const requestFs = element.requestFullscreen
      || element.webkitRequestFullscreen
      || element.msRequestFullscreen;

    if (requestFs) {
      requestFs.call(element);
    } else if (fallbackUrl) {
      window.open(fallbackUrl, '_blank');
    }
  }

  function addFullscreenButton(host, targetElement, fallbackUrl) {
    const fsBtn = document.createElement('button');
    fsBtn.type = 'button';
    fsBtn.className = 'lightbox-fullscreen-btn';
    fsBtn.textContent = 'Fullscreen';
    fsBtn.addEventListener('click', () => fullscreenTarget(targetElement, fallbackUrl));
    host.appendChild(fsBtn);
  }

  function showLightboxContent(item, type) {
    const parts = getLightboxParts();
    if (!parts || !parts.content) return;

    parts.content.innerHTML = '';

    const videoSrc = item.dataset.src;
    const isYouTube = item.dataset.youtube === 'true';

    if (type === 'image') {
      const img = document.createElement('img');
      img.src = item.querySelector('img').src;
      img.alt = item.querySelector('img').alt || 'Gallery Image';
      parts.content.appendChild(img);
      return;
    }

    if (type === 'video') {
      if (isYouTube) {
        const frameWrap = document.createElement('div');
        frameWrap.className = 'lightbox-video-wrap';

        const iframe = document.createElement('iframe');
        iframe.src = videoSrc;
        iframe.width = '100%';
        iframe.height = '480';
        iframe.allow = 'autoplay; fullscreen; picture-in-picture';
        iframe.allowFullscreen = true;
        iframe.frameBorder = '0';

        frameWrap.appendChild(iframe);
        parts.content.appendChild(frameWrap);
        addFullscreenButton(parts.content, iframe, videoSrc);
      } else {
        const videoWrap = document.createElement('div');
        videoWrap.className = 'lightbox-video-wrap';

        const video = document.createElement('video');
        video.controls = true;
        video.autoplay = true;
        video.playsInline = true;

        const source = document.createElement('source');
        source.src = videoSrc;
        source.type = 'video/mp4';

        video.appendChild(source);
        videoWrap.appendChild(video);
        parts.content.appendChild(videoWrap);
        addFullscreenButton(parts.content, video, videoSrc);
      }
    }
  }

  function closeLightbox() {
    setLightboxOpenState(false);
  }

  function showPrev() {
    if (!currentItems.length) return;
    currentIndex = (currentIndex - 1 + currentItems.length) % currentItems.length;
    showLightboxContent(currentItems[currentIndex], currentType);
  }

  function showNext() {
    if (!currentItems.length) return;
    currentIndex = (currentIndex + 1) % currentItems.length;
    showLightboxContent(currentItems[currentIndex], currentType);
  }

  function bindLightboxControls() {
    const parts = getLightboxParts();
    if (!parts) return;

    if (parts.close && !parts.close.dataset.lbBound) {
      parts.close.addEventListener('click', closeLightbox);
      parts.close.dataset.lbBound = '1';
    }

    if (parts.prev && !parts.prev.dataset.lbBound) {
      parts.prev.addEventListener('click', showPrev);
      parts.prev.dataset.lbBound = '1';
    }

    if (parts.next && !parts.next.dataset.lbBound) {
      parts.next.addEventListener('click', showNext);
      parts.next.dataset.lbBound = '1';
    }

    if (!parts.lightbox.dataset.lbBound) {
      parts.lightbox.addEventListener('click', function (e) {
        if (e.target === parts.lightbox) {
          closeLightbox();
        }
      });
      parts.lightbox.dataset.lbBound = '1';
    }
  }

  window.openLightbox = function (index, type) {
    bindLightboxControls();

    currentType = type || 'image';
    currentItems = Array.from(document.querySelectorAll(`.gallery-item[data-type='${currentType}']`));
    if (!currentItems.length) return;

    if (index < 0 || index >= currentItems.length) {
      currentIndex = 0;
    } else {
      currentIndex = index;
    }

    showLightboxContent(currentItems[currentIndex], currentType);
    setLightboxOpenState(true);
  };

  window.bindLightbox = function () {
    bindLightboxControls();

    const items = document.querySelectorAll('.gallery-item');
    items.forEach((item) => {
      if (item.dataset.lbBound) return;

      item.addEventListener('click', () => {
        const type = item.dataset.type || 'image';
        const typedItems = Array.from(document.querySelectorAll(`.gallery-item[data-type='${type}']`));
        const index = Math.max(0, typedItems.indexOf(item));
        window.openLightbox(index, type);
      });

      item.dataset.lbBound = '1';
    });
  };

  document.addEventListener('keydown', function (e) {
    if (!isLightboxOpen()) return;
    if (e.key === 'ArrowLeft') showPrev();
    else if (e.key === 'ArrowRight') showNext();
    else if (e.key === 'Escape') closeLightbox();
  });
});
