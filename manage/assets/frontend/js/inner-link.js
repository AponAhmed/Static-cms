// Smooth scrolling to the targeted section when a sidebar link is clicked
let clickTrig = false;
let clickTimer = null;

document.addEventListener('DOMContentLoaded', function () {
    const sidebarLinks = document.querySelectorAll('.wa-sidebar a');
    if (sidebarLinks.length > 0) {
        sidebarLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                clickTrig = true;
                clearTimeout(clickTimer);
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    document.querySelectorAll(".wa-sidebar li").forEach(function (li) {
                        li.classList.remove('current');
                    });
                    document.querySelectorAll(".qa-single").forEach(function (single) {
                        single.classList.remove('blink-once');
                    });
                    link.parentElement.classList.add('current');
                    targetElement.classList.add('blink-once');
                    const offsetTop = targetElement.offsetTop - 50;
                    window.scroll({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                    clickTimer = setTimeout(() => {
                        clickTrig = false;
                    }, 1000);
                }
            });
        });
    }

    window.addEventListener('scroll', debounce(function () {
        if (!clickTrig) {
            const headings = document.querySelectorAll('.wa-content h1, .wa-content h2, .wa-content h3, .wa-content h4, .wa-content h5');
            const windowHeight = window.innerHeight;
            let found = false;
            headings.forEach(function (heading) {
                const rect = heading.getBoundingClientRect();
                let id = heading.querySelector('[id]').getAttribute('id');
                if (rect.top >= 0 && !found) {
                    document.querySelectorAll('.nav-list-item').forEach(function (item) {
                        item.classList.remove('current');
                    });
                    document.getElementById('nav-' + id).classList.add('current');
                    found = true;
                }
            });
        }
    }, 100)); // Debounce the scroll event

    const screenWidth = window.innerWidth;
    if (screenWidth > 768) {
        const sidebar = document.querySelector('.wa-sidebar ul, .wa-sidebar ol');
        if (sidebar) {
            const mainWrap = document.querySelector('.qa-wrap');
            const sidebarBound = sidebar.getBoundingClientRect();
            const wrapBound = mainWrap.getBoundingClientRect();
            const sidebarOffsetTop = sidebarBound.top + window.scrollY;

            window.addEventListener('scroll', function () {
                const scrollY = window.scrollY || document.documentElement.scrollTop || document.body.scrollTop || 0;
                const wrapTop = scrollY - document.querySelector('.wa-sidebar').offsetTop;

                if ((wrapBound.height - wrapTop) <= sidebarBound.height) {
                    mainWrap.classList.add('max-scrolled');
                } else {
                    mainWrap.classList.remove('max-scrolled');
                    if (scrollY > sidebarOffsetTop) {
                        sidebar.style.position = 'fixed';
                        sidebar.style.top = '48px';
                        sidebar.style.width = sidebarBound.width + "px";
                    } else {
                        sidebar.style.position = 'static';
                        sidebar.style.top = 'auto';
                        sidebar.style.width = 'auto';
                    }
                }
            });
        }
    }
});

// Debounce function to limit scroll event firing
function debounce(func, wait) {
    let timeout;
    return function () {
        const context = this;
        const args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            func.apply(context, args);
        }, wait);
    };
}
