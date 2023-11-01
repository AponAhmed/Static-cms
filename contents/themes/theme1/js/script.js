import '../../../../manage/assets/reset.css';
import '../../../../manage/assets/box-grid.css';    //Grid System Css support

import '../../../../manage/assets/frontend/css/common.css';
//Theme Styles
import '../css/main-menu.css';
import '../css/header.css';
import '../css/blog-template-style.css';
import '../css/style.css';                          //Theme Style
import '../css/footer.css';

//Js from Here
import MenuHandler from './menu';
import './ScrollFixed';
import '../../../../manage/assets/frontend/js/Slider';
import '../../../../manage/assets/frontend/js/inner-link';
import '../../../../manage/assets/frontend/js/contact';
import '../../../../manage/assets/frontend/js/Cart';
import '../../../../manage/assets/frontend/js/Expand';

const menus = document.querySelectorAll('nav.nav-menu');
new MenuHandler(menus);

