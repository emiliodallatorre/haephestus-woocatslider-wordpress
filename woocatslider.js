let slideByCategoryIndex = {}

function changeSlide(n, category) {
    if (!slideByCategoryIndex[category]) {
        slideByCategoryIndex[category] = 0;
    }

    slideByCategoryIndex[category] += n;
    showSlides(category);
}

function showSlides(category) {
    if (!slideByCategoryIndex[category]) {
        slideByCategoryIndex[category] = 0;
    }

    let slides;
    if (category != null) {
        slides = document.querySelectorAll(".slide.category-" + category);
    } else {
        slides = document.querySelectorAll(".slide");
    }


    if (slideByCategoryIndex[category] < 0) {
        slideByCategoryIndex[category] = slides.length - 1;
    } else if (slideByCategoryIndex[category] >= slides.length) {
        slideByCategoryIndex[category] = 0;
    }

    for (let i = 0; i < slides.length; i++) {
        slides[i].style.transform = `translateX(-${slideByCategoryIndex[category] * 100 - 12.5 - 5}%)`;
    }
}

showSlides(); // Display the initial slide

// Optional: Auto-advance the slideshow
// setInterval(() => changeSlide(1), 5000); // Change slide every 5 seconds