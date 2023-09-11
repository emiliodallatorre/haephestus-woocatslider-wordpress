let slideIndex = 0;

function changeSlide(n) {
    slideIndex += n;
    showSlides();
}

function showSlides() {
    const slides = document.querySelectorAll(".slide");

    if (slideIndex < 0) {
        slideIndex = slides.length - 1;
    } else if (slideIndex >= slides.length) {
        slideIndex = 0;
    }

    for (let i = 0; i < slides.length; i++) {
        slides[i].style.transform = `translateX(-${slideIndex * 100 - 12.5 - 5}%)`;
    }
}

showSlides(); // Display the initial slide

// Optional: Auto-advance the slideshow
// setInterval(() => changeSlide(1), 5000); // Change slide every 5 seconds