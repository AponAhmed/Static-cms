// Smooth scrolling to the targeted section when a sidebar link is clicked
let clickTrig = false;
let clickTimer = null;

// Use 'scrollY' instead of 'window.pageYOffset' in your code


$(document).ready(function () {
    const sidebarLinks = $('.wa-sidebar a');
    if (sidebarLinks.length > 0) {
        sidebarLinks.click(function (e) {
            clickTrig = true;
            clearTimeout(clickTimer);
            e.preventDefault();
            const target = $(this).attr('href');
            $(".wa-sidebar li").removeClass('current');
            $(".qa-single").removeClass('blink-once');
            $(this).parent().addClass('current');
            $(target).addClass('blink-once');
            $('html, body').animate({
                scrollTop: ($(target).offset().top - 50)
            }, 500);
            clickTimer = setTimeout(() => {
                clickTrig = false;
            }, 1000);
        });
    }

    $(window).scroll(debounce(function () {
        if (!clickTrig) {
            const headings = $('.wa-content h1, .wa-content h2, .wa-content h3, .wa-content h4, .wa-content h5');
            const windowHeight = window.innerHeight;
            headings.each(function () {
                const rect = this.getBoundingClientRect();
                let id = $(this).find('span').attr('id');
                if (rect.top > 0) {
                    if (parseInt(rect.top) > ((windowHeight / 2) + 25)) {
                        const previousHeading = $(this).prevAll('h1, h2, h3, h4, h5, h6').filter(':first');
                        id = previousHeading.find('span').attr('id');
                    }
                    $('.nav-list-item').removeClass('current');
                    $('#nav-' + id).addClass('current');
                    return false;
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
            const sidebarOffsetTop = sidebarBound.top + window.pageYOffset;

            window.addEventListener('scroll', function () {
                const scrollY = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
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
