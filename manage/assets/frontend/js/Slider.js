class ImageSlider {
    constructor(containerSelector, options = {}) {
        this.container = document.querySelector(containerSelector);
        if (!this.container) {
            return;
        }
        this.sliderItems = this.container.querySelectorAll('.slider-item');
        this.currentSlide = 0;

        this.prevButton = false;
        this.nextButton = false;
        this.bullet = false;


        // Assuming options is an object and autoslide is a default value
        this.autoSlide = options.autoSlide !== undefined ? options.autoSlide : autoslide;

        // If you also want to check the data attribute data-autoslide
        const dataAutoSlide = this.container.getAttribute('data-autoslide');
        if (dataAutoSlide !== null) {
            this.autoSlide = dataAutoSlide === 'true'; // Convert to boolean
        }
        // Options
        //this.autoSlide = options.autoSlide || autoslide;
        this.slideDuration = options.duration || 3000;
        this.showNavigation = options.nav || false;


        // Select the navigation buttons
        if (document.querySelector("#prev-slide")) {
            this.prevButton = document.getElementById('prev-slide');
        }
        if (document.querySelector("#next-slide")) {
            this.nextButton = document.getElementById('next-slide');
        }


        if (this.showNavigation) {
            if (this.prevButton) {
                this.prevButton.addEventListener('click', () => this.prevSlide());
            }
            if (this.nextButton) {
                this.nextButton.addEventListener('click', () => this.nextSlide());
            }
        }

        if (this.autoSlide) {
            this.startAutoSlide();
        }

        // Get the container for bullets and generate them
        if (document.querySelector('.bullets')) {
            this.bulletContainer = this.container.querySelector('.bullets');


            this.sliderItems.forEach((_, index) => {
                const bullet = document.createElement('div');
                bullet.classList.add('bullet');
                bullet.addEventListener('click', () => this.showSlide(index));
                this.bulletContainer.appendChild(bullet);
            });
            this.bullets = this.bulletContainer.querySelectorAll('.bullet');
        } else {
            this.bullets = [];
        }

        // Initially display the first slide without feed-in effect
        this.showSlide(0, false);
    }

    prevSlide() {
        this.showSlide(this.currentSlide - 1);
    }

    nextSlide() {
        this.showSlide(this.currentSlide + 1);
    }

    showSlide(index, withEffect = true) {
        if (index < 0) {
            index = this.sliderItems.length - 1;
        } else if (index >= this.sliderItems.length) {
            index = 0;
        }

        // Fade out the current slide
        if (withEffect) {
            this.fadeOut(this.sliderItems[this.currentSlide]);
        } else {
            this.sliderItems[this.currentSlide].style.opacity = 0;
        }

        // Fade in the next slide
        this.fadeIn(this.sliderItems[index]);

        this.currentSlide = index;

        if (this.autoSlide) {
            this.stopAutoSlide();
            this.startAutoSlide();
        }
        // Update bullet states
        this.bullets.forEach((bullet, bulletIndex) => {
            if (bulletIndex === index) {
                bullet.classList.add('active');
            } else {
                bullet.classList.remove('active');
            }
        });
    }

    // Custom fade-in function
    fadeIn(element) {
        element.classList.add('active');
        element.style.opacity = 0;
        element.style.display = 'flex'; // Show the element
        const fadeEffect = setInterval(() => {
            if (element.style.opacity < 1) {
                element.style.opacity = parseFloat(element.style.opacity) + 0.5;
            } else {
                clearInterval(fadeEffect);
            }
        }, 50);
    }

    // Custom fade-out function
    fadeOut(element) {
        element.classList.remove('active');
        element.style.opacity = 1;
        const fadeEffect = setInterval(() => {
            if (element.style.opacity > 0) {
                element.style.opacity = parseFloat(element.style.opacity) - 0.5;
            } else {
                //element.style.display = 'none'; // Hide the element
                clearInterval(fadeEffect);
            }
        }, 50);
    }

    startAutoSlide() {
        this.autoSlideInterval = setInterval(() => {
            this.showSlide(this.currentSlide + 1);
        }, this.slideDuration);
    }

    stopAutoSlide() {
        clearInterval(this.autoSlideInterval);
    }
}

// Create an instance of the ImageSlider class with options
const slider = new ImageSlider('.slider-wrap', {
    autoSlide: true,
    duration: 6000,
    nav: true,
});

//Hoverable Gallery
let galleries = document.querySelectorAll(".gallery-hoverable");

if (galleries.length > 0) {
    galleries.forEach(function (gallery) {
        let preview = gallery.querySelector('.view-image img');
        let loader = gallery.querySelector('.loader'); // The loader element
        let images = gallery.querySelectorAll(".image-item");

        const isMobile = window.innerWidth < 768; // Adjust the screen width breakpoint as needed

        images.forEach(function (li) {
            // Determine the appropriate event based on the device type
            const event = isMobile ? 'click' : 'mouseover';

            li.addEventListener(event, () => {
                // Show the loader only if the image is not in the cache
                if (!isImageInCache(li.getAttribute('data-src'))) {
                    loader.style.display = "block";
                }
                // Remove the .current class from all siblings
                images.forEach(function (sibling) {
                    sibling.classList.remove('current');
                });
                // Add .current to the current element
                li.classList.add('current');
                // Create an image element to pre-load the image
                var tempImg = new Image();
                tempImg.onload = () => {
                    let link = gallery.querySelector('.view-image a');
                    // Hide the loader if it was shown and update the preview image with data-src and data-title
                    if (loader.style.display === "block") {
                        loader.style.display = "none";
                    }
                    preview.src = li.getAttribute('data-src');
                    preview.alt = li.getAttribute('data-title');
                    if (link) {
                        link.setAttribute('href', li.getAttribute('data-url'));
                    }
                };
                tempImg.src = li.getAttribute('data-src'); // Trigger image loading
            });
        });
    });
}

function isImageInCache(src) {
    // Check if the image source is in the browser's cache
    const image = new Image();
    image.src = src;
    return image.complete;
}
