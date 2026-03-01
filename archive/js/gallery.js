// GALLERY (IMAGES + VIDEOS) LOGIC
let currentIndex = 0, mediaType = '';
const images = [ /* same array from your script */ ];
const videos = [ /* same array from your script */ ];

function openLightbox(idx, type) {
    currentIndex = idx; mediaType = type;
    const lb = document.getElementById("lightbox");
    const content = document.getElementById("lightbox-content");
    if (type === "image") content.innerHTML = `<img src="${images[idx]}" style="width:100%">`;
    else content.innerHTML = `<iframe src="https://www.youtube-nocookie.com/embed/${videos[idx]}" allowfullscreen></iframe>`;
    lb.style.display = "flex";
}
function closeLightbox() {
    document.getElementById("lightbox").style.display = "none";
}
function changeMedia(dir) {
    const arr = mediaType==="image" ? images : videos;
    currentIndex = (currentIndex + dir + arr.length) % arr.length;
    openLightbox(currentIndex, mediaType);
}
