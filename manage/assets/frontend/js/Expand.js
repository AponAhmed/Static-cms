class Expand {
    constructor() {
        const expandArrows = document.querySelectorAll('.expand-arrow');

        expandArrows.forEach(arrow => {
            arrow.addEventListener('click', () => {
                const wrap = arrow.parentNode;
                wrap.classList.toggle('active'); // Toggle the 'active' class
            });
        });
    }
}

// Initialize the Expand class
new Expand();
