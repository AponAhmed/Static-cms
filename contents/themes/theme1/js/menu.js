
import { Dombuilder as el } from '@aponahmed/dombuilder'

class MenuHandler {
    constructor(menus) {
        this.menus = menus;
        this.body = document.body;
        this.process();
    }

    process() {
        this.menus.forEach((menu) => {
            this.menu = menu;
            this.navWrap = menu.querySelector('.nav-wrapper');
            this.menuToggle = document.querySelector('.nav-toggle');
            this.menuToggle.addEventListener('click', () => {
                this.toggle();
            });
        });
    }

    toggle() {
        // Get references to elements
        const menuItemHasChildren = this.menu.querySelectorAll('.has-child > a');

        // Toggle classes on elements
        this.navWrap.classList.toggle('openNav');
        this.body.classList.toggle('body-openNav');
        this.menuToggle.classList.toggle('taggOpen');

        // Remove click event listeners
        menuItemHasChildren.forEach(item => {
            item.removeEventListener('click', this.menuItemClick);
        });
        // Add click event listener to menu items with children
        menuItemHasChildren.forEach(item => {
            item.addEventListener('click', this.menuItemClick);
        });
    }

    menuItemClick(e) {
        e.preventDefault();
        const thisItem = this;
        const parentUl = thisItem.closest('ul');

        parentUl.querySelectorAll('ul.nav-sub').forEach(subMenu => {
            subMenu.classList.remove('open');
        });

        parentUl.querySelectorAll('li').forEach(li => {
            li.classList.remove('mobile-nav-open');
        });

        const subMenu = thisItem.nextElementSibling;
        subMenu.classList.toggle('open');
        thisItem.parentElement.classList.toggle('mobile-nav-open');
    }
}

export default MenuHandler;




