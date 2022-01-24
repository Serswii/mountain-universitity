$(document).ready(function() {

    // Check for click events on the navbar burger icon
    $(".navbar-burger").click(function() {

        // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
        $(".navbar-burger").toggleClass("is-active");
        $(".navbar-menu").toggleClass("is-active");

    });
});
lightGallery(document.getElementById('animated-thumbnails-gallery'), {
    plugins: [lgZoom, lgThumbnail],
    speed: 500,
    animateThumb: false,
    zoomFromOrigin: true,
    allowMediaOverlap: true,
    toggleThumb: true,
    closeOnTap: true,
});