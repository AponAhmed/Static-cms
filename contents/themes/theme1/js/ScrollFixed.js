class FixedOnScroll {
    constructor() {
        // Find all elements with class .scrolling-fixed-wrapper and store them in an array
        this.scrollWrapperElements = Array.from(document.querySelectorAll('.scrolling-fixed-wrapper'));

        if (this.scrollWrapperElements.length > 0 && window.innerWidth > 768) {
            // Add a scroll event listener to handle the scrolling behavior
            window.addEventListener('scroll', () => this.handleScroll());
            this.posData = [];
        }
    }

    handleScroll() {
        this.scrollWrapperElements.forEach((wrapper, i) => {
            const rect = wrapper.getBoundingClientRect();
            const fxel = wrapper.querySelector('.scroll-fixed');

            if (!this.posData[i]) {
                this.posData[i] = fxel.getBoundingClientRect();
            }

            const scrolled = rect.height - Math.abs(rect.y);

            if (rect.y < 0 && scrolled > 0) {
                const fxelRect = this.posData[i];
                fxel.style.cssText = `
                    left: ${fxelRect.x}px;
                    height: ${fxelRect.height}px;
                    width: ${fxelRect.width}px;
                `;

                if (scrolled - (fxelRect.height + 40) < 0) {
                    fxel.style.top = `${scrolled - (fxelRect.height + 40)}px`;
                } else {
                    fxel.style.removeProperty('top');
                }

                this.addFixed(fxel);
            } else {
                this.removeFixed(fxel);
            }
        });
    }

    addFixed(elm) {
        elm.classList.add('fixed-enabled');
    }

    removeFixed(elm) {
        if (elm.classList.contains('fixed-enabled')) {
            elm.classList.remove('fixed-enabled');
        }
        elm.style.cssText = `
            left: auto;
            height: auto;
            width: auto;
        `;
        elm.style.removeProperty('top');
    }
}

// Create an instance of the FixedOnScroll class to activate the behavior
const fixedOnScroll = new FixedOnScroll();
