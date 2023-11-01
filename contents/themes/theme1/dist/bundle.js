(()=>{var t={519:()=>{new class{constructor(){this.scrollWrapperElements=Array.from(document.querySelectorAll(".scrolling-fixed-wrapper")),this.scrollWrapperElements.length>0&&window.innerWidth>768&&(window.addEventListener("scroll",(()=>this.handleScroll())),this.posData=[])}handleScroll(){this.scrollWrapperElements.forEach(((t,e)=>{const n=t.getBoundingClientRect(),s=t.querySelector(".scroll-fixed");this.posData[e]||(this.posData[e]=s.getBoundingClientRect());const i=n.height-Math.abs(n.y);if(n.y<0&&i>0){const t=this.posData[e];s.style.cssText=`\n                    left: ${t.x}px;\n                    height: ${t.height}px;\n                    width: ${t.width}px;\n                `,i-(t.height+40)<0?s.style.top=i-(t.height+40)+"px":s.style.removeProperty("top"),this.addFixed(s)}else this.removeFixed(s)}))}addFixed(t){t.classList.add("fixed-enabled")}removeFixed(t){t.classList.contains("fixed-enabled")&&t.classList.remove("fixed-enabled"),t.style.cssText="\n            left: auto;\n            height: auto;\n            width: auto;\n        ",t.style.removeProperty("top")}}},99:()=>{new class{constructor(){document.querySelectorAll(".expand-arrow").forEach((t=>{t.addEventListener("click",(()=>{t.parentNode.classList.toggle("active")}))}))}}},217:()=>{new class{constructor(t,e={}){if(this.container=document.querySelector(t),!this.container)return;this.sliderItems=this.container.querySelectorAll(".slider-item"),this.currentSlide=0,this.prevButton=!1,this.nextButton=!1,this.bullet=!1,this.autoSlide=void 0!==e.autoSlide?e.autoSlide:autoslide;const n=this.container.getAttribute("data-autoslide");null!==n&&(this.autoSlide="true"===n),this.slideDuration=e.duration||3e3,this.showNavigation=e.nav||!1,document.querySelector("#prev-slide")&&(this.prevButton=document.getElementById("prev-slide")),document.querySelector("#next-slide")&&(this.nextButton=document.getElementById("next-slide")),this.showNavigation&&(this.prevButton&&this.prevButton.addEventListener("click",(()=>this.prevSlide())),this.nextButton&&this.nextButton.addEventListener("click",(()=>this.nextSlide()))),this.autoSlide&&this.startAutoSlide(),document.querySelector(".bullets")?(this.bulletContainer=this.container.querySelector(".bullets"),this.sliderItems.forEach(((t,e)=>{const n=document.createElement("div");n.classList.add("bullet"),n.addEventListener("click",(()=>this.showSlide(e))),this.bulletContainer.appendChild(n)})),this.bullets=this.bulletContainer.querySelectorAll(".bullet")):this.bullets=[],this.showSlide(0,!1)}prevSlide(){this.showSlide(this.currentSlide-1)}nextSlide(){this.showSlide(this.currentSlide+1)}showSlide(t,e=!0){t<0?t=this.sliderItems.length-1:t>=this.sliderItems.length&&(t=0),e?this.fadeOut(this.sliderItems[this.currentSlide]):this.sliderItems[this.currentSlide].style.opacity=0,this.fadeIn(this.sliderItems[t]),this.currentSlide=t,this.autoSlide&&(this.stopAutoSlide(),this.startAutoSlide()),this.bullets.forEach(((e,n)=>{n===t?e.classList.add("active"):e.classList.remove("active")}))}fadeIn(t){t.classList.add("active"),t.style.opacity=0,t.style.display="flex";const e=setInterval((()=>{t.style.opacity<1?t.style.opacity=parseFloat(t.style.opacity)+.5:clearInterval(e)}),50)}fadeOut(t){t.classList.remove("active"),t.style.opacity=1;const e=setInterval((()=>{t.style.opacity>0?t.style.opacity=parseFloat(t.style.opacity)-.5:clearInterval(e)}),50)}startAutoSlide(){this.autoSlideInterval=setInterval((()=>{this.showSlide(this.currentSlide+1)}),this.slideDuration)}stopAutoSlide(){clearInterval(this.autoSlideInterval)}}(".slider-wrap",{autoSlide:!0,duration:6e3,nav:!0});let t=document.querySelectorAll(".gallery-hoverable");t.length>0&&t.forEach((function(t){let e=t.querySelector(".view-image img"),n=t.querySelector(".loader"),s=t.querySelectorAll(".image-item");const i=window.innerWidth<768;s.forEach((function(a){const o=i?"click":"mouseover";a.addEventListener(o,(()=>{(function(t){const e=new Image;return e.src=t,e.complete})(a.getAttribute("data-src"))||(n.style.display="block"),s.forEach((function(t){t.classList.remove("current")})),a.classList.add("current");var i=new Image;i.onload=()=>{let s=t.querySelector(".view-image a");"block"===n.style.display&&(n.style.display="none"),e.src=a.getAttribute("data-src"),e.alt=a.getAttribute("data-title"),s&&s.setAttribute("href",a.getAttribute("data-url"))},i.src=a.getAttribute("data-src")}))}))}))},548:()=>{let t=!1,e=null;$(document).ready((function(){const n=$(".wa-sidebar a");n.length>0&&n.click((function(n){t=!0,clearTimeout(e),n.preventDefault();const s=$(this).attr("href");$(".wa-sidebar li").removeClass("current"),$(".qa-single").removeClass("blink-once"),$(this).parent().addClass("current"),$(s).addClass("blink-once"),$("html, body").animate({scrollTop:$(s).offset().top-50},500),e=setTimeout((()=>{t=!1}),1e3)})),$(window).scroll(function(t,e){let n;return function(){const s=this,i=arguments;clearTimeout(n),n=setTimeout((function(){t.apply(s,i)}),e)}}((function(){if(!t){const t=$(".wa-content h1, .wa-content h2, .wa-content h3, .wa-content h4, .wa-content h5"),e=window.innerHeight;t.each((function(){const t=this.getBoundingClientRect();let n=$(this).find("span").attr("id");if(t.top>0){if(parseInt(t.top)>e/2+25){n=$(this).prevAll("h1, h2, h3, h4, h5, h6").filter(":first").find("span").attr("id")}return $(".nav-list-item").removeClass("current"),$("#nav-"+n).addClass("current"),!1}}))}}),100));if(window.innerWidth>768){const t=document.querySelector(".wa-sidebar ul, .wa-sidebar ol");if(t){const e=document.querySelector(".qa-wrap"),n=t.getBoundingClientRect(),s=e.getBoundingClientRect(),i=n.top+window.pageYOffset;window.addEventListener("scroll",(function(){const a=window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop||0,o=a-document.querySelector(".wa-sidebar").offsetTop;s.height-o<=n.height?e.classList.add("max-scrolled"):(e.classList.remove("max-scrolled"),a>i?(t.style.position="fixed",t.style.top="48px",t.style.width=n.width+"px"):(t.style.position="static",t.style.top="auto",t.style.width="auto"))}))}}}))}},e={};function n(s){var i=e[s];if(void 0!==i)return i.exports;var a=e[s]={exports:{}};return t[s](a,a.exports,n),a.exports}(()=>{"use strict";const t=class{constructor(t){this.menus=t,this.body=document.body,this.process()}process(){this.menus.forEach((t=>{this.menu=t,this.navWrap=t.querySelector(".nav-wrapper"),this.menuToggle=document.querySelector(".nav-toggle"),this.menuToggle.addEventListener("click",(()=>{this.toggle()}))}))}toggle(){const t=this.menu.querySelectorAll(".has-child > a");this.navWrap.classList.toggle("openNav"),this.body.classList.toggle("body-openNav"),this.menuToggle.classList.toggle("taggOpen"),t.forEach((t=>{t.removeEventListener("click",this.menuItemClick)})),t.forEach((t=>{t.addEventListener("click",this.menuItemClick)}))}menuItemClick(t){t.preventDefault();const e=this,n=e.closest("ul");n.querySelectorAll("ul.nav-sub").forEach((t=>{t.classList.remove("open")})),n.querySelectorAll("li").forEach((t=>{t.classList.remove("mobile-nav-open")}));e.nextElementSibling.classList.toggle("open"),e.parentElement.classList.toggle("mobile-nav-open")}};n(519),n(217),n(548);const e=class{constructor({...t}){this.type=t.type||"success",this.message=t.message||"",this.timeout=t.timeout||6e3,"success"!==this.type&&(this.timeout=2*this.timeout),this.bind()}build(){let t=document.createElement("div");t.classList.add("notification"),t.classList.add(this.type),"alert"==this.type||"warning"==this.type?t.innerHTML='<svg xmlns="http://www.w3.org/2000/svg" class="notification-icon" viewBox="0 0 512 512"><title>Warning</title><path d="M85.57 446.25h340.86a32 32 0 0028.17-47.17L284.18 82.58c-12.09-22.44-44.27-22.44-56.36 0L57.4 399.08a32 32 0 0028.17 47.17z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M250.26 195.39l5.74 122 5.73-121.95a5.74 5.74 0 00-5.79-6h0a5.74 5.74 0 00-5.68 5.95z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 397.25a20 20 0 1120-20 20 20 0 01-20 20z"/></svg>':"info"==this.type?t.innerHTML='<svg xmlns="http://www.w3.org/2000/svg" class="notification-icon" viewBox="0 0 512 512"><path d="M248 64C146.39 64 64 146.39 64 248s82.39 184 184 184 184-82.39 184-184S349.61 64 248 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M220 220h32v116"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M208 340h88"/><path d="M248 130a26 26 0 1026 26 26 26 0 00-26-26z"/></svg>':"success"==this.type?t.innerHTML='<svg xmlns="http://www.w3.org/2000/svg" class="notification-icon" viewBox="0 0 512 512"><title>Checkmark</title><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M416 128L192 384l-96-96"/></svg>':t.innerHTML='<svg xmlns="http://www.w3.org/2000/svg" class="notification-icon" viewBox="0 0 512 512"><title>Close Circle</title><path d="M448 256c0-106-86-192-192-192S64 150 64 256s86 192 192 192 192-86 192-192z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M320 320L192 192M192 320l128-128"/></svg>';let e=document.createElement("div");e.classList.add("message"),e.innerHTML=this.message,t.appendChild(e);let n=document.createElement("div");return n.classList.add("close"),n.innerHTML="&times;",n.addEventListener("click",(()=>{t.remove()})),t.appendChild(n),t.classList.add("slide-righr"),t}bind(){let t=document.querySelector(".notifications");t||(t=document.createElement("div"),t.classList.add("notifications"),document.body.appendChild(t)),t.appendChild(this.build()),setTimeout((()=>{t.firstChild&&t.removeChild(t.firstChild)}),this.timeout)}};class s{constructor(t,e=10){this.uploadUrl=t,this.maxImages=e,this.uploadedImages=[],this.previewArea=document.getElementById("previewArea")}uploadImages(t){const n=t.length;if(n>this.maxImages-this.uploadedImages.length)new e({message:`You can Select a maximum of ${this.maxImages} images at a Time.`,type:"alert"});else for(let s=0;s<n;s++){const n=t[s];if(!n.type.startsWith("image/")){new e({message:`File ${n.name} is not an image and will be skipped.`,type:"alert"});continue}const i=document.createElement("div");i.className="image-container",this.previewArea.appendChild(i);const a=this.createProgressBar(n.name);this.removeBtn=document.createElement("span"),this.removeBtn.innerHTML="&times;",this.removeBtn.addEventListener("click",(()=>{i.remove()})),this.removeBtn.classList.add("remove-attachment"),i.appendChild(a),i.appendChild(this.removeBtn);const o=new FormData;o.append("image",n),this.uploadFile(o,a,i)}}clearPriview(){this.previewArea.innerHTML=""}createProgressBar(t){const e=document.createElement("div");return e.className="progress-bar",e.innerHTML='<div class="progress-bar-fill"></div>',e}removeUpload(t){const e=new XMLHttpRequest,n=this.uploadUrl+"remove/",s=new FormData;s.append("filename",t),e.open("POST",n,!0),e.onreadystatechange=function(){e.readyState===XMLHttpRequest.DONE&&(200===e.status?console.log(e.responseText):console.error("Request failed with status:",e.status))},e.send(s)}async uploadFile(t,n,s){const i=new XMLHttpRequest;i.upload.addEventListener("progress",(t=>{if(t.lengthComputable){const e=t.loaded/t.total*100;n.querySelector(".progress-bar-fill").style.width=e+"%"}})),i.onreadystatechange=()=>{if(4===i.readyState)if(200===i.status){const t=JSON.parse(i.responseText);if(t.success){n.classList.add("upload-success"),this.uploadedImages.push(t.name),this.displayImage(t.url,s);let e=document.createElement("input");e.type="hidden",e.name="attachments[]",e.value=t.name,s.appendChild(e),this.removeBtn.addEventListener("click",(e=>{this.removeUpload(t.name)}))}else n.classList.add("upload-error"),new e({message:t.message,type:"error"}),this.previewArea.removeChild(s)}else n.classList.add("upload-error"),new e({message:"Error uploading file.",type:"error"}),this.previewArea.removeChild(s)},i.open("POST",this.uploadUrl+"upload/",!0),i.send(t)}displayImage(t,e){const n=document.createElement("img");n.src=t,e.appendChild(n)}}let i,a=document.getElementById("uploadTriger");var o,r,l;function c(){return Math.ceil(20*Math.random())}a&&(i=new s(AJAXURL+"contacts/"),a.addEventListener("change",(t=>{const e=t.target.files;i.uploadImages(e)})));class d{constructor(){this.n1=this.getRandom(),this.n2=this.getRandom(),this.total=this.n1+this.n2,this.sendable=!1,this.body=document.querySelector("body"),this.formElement=null,this.nameInput=null,this.emailInput=null,this.subjectInput=null,this.messageTextarea=null,this.questionLabel=null,this.answerInput=null,this.submitButton=null,this.contactMsg=null,this.dom=document.createElement("div"),this.dom.classList.add("write-us"),this.dom.classList.add("floated-contact-form-wrap"),this.buildDOM()}metch(){this.answerInput.value!=this.total?(this.answerInput.parentNode.style.border="1px solid #f00",this.sendable=!1):(this.answerInput.parentNode.removeAttribute("style"),this.sendable=!0,this.contactMsg.innerHTML="")}handleSubmit(t){if(this.contactMsg.innerHTML="",t.preventDefault(),!this.sendable)return this.contactMsg.innerHTML="Please Enter valid information",void(this.contactMsg.style.color="red");this.submitButton.innerHTML=" Sending...",this.submitButton.insertAdjacentHTML("afterend","<span class='spinLoader'><i></i><i></i><i></i><i></i><i></i><i></i></span>");var e={name:this.nameInput.value,email:this.emailInput.value,subject:this.subjectInput.value,message:this.messageTextarea.value,reff:window.location.href};jQuery.post(AJAXURL+"contacts/send/",e,(t=>{jQuery(".spinLoader").remove();var e=JSON.parse(t);this.contactMsg.innerHTML=e.message,!1===e.error?(this.formElement.reset(),this.contactMsg.style.color="green",this.submitButton.innerHTML=" Sent !",setTimeout((()=>{this.handleClose()}),2e3)):(this.contactMsg.style.color="red",this.submitButton.innerHTML="Try Again")}))}buildDOM(){this.formElement=document.createElement("form"),this.formElement.id="contactForm",this.formElement.className="write-us-form",this.formElement.addEventListener("submit",this.handleSubmit.bind(this));const t=document.createElement("div");t.className="input-wrap gac-name-in";const e=document.createElement("label");e.textContent="Your Name (required)",this.nameInput=document.createElement("input"),this.nameInput.name="name",this.nameInput.type="text",this.nameInput.className="contactFormField",this.nameInput.required=!0,t.appendChild(e),t.appendChild(this.nameInput);const n=document.createElement("div");n.className="input-wrap gac-email-in";const s=document.createElement("label");s.textContent="Your Email (required)",this.emailInput=document.createElement("input"),this.emailInput.name="email",this.emailInput.type="email",this.emailInput.className="contactFormField",this.emailInput.required=!0,n.appendChild(s),n.appendChild(this.emailInput);const i=document.createElement("div");i.className="input-wrap gac-subject-in",window.innerWidth>580&&i.classList.add("hide");const a=document.createElement("label");a.textContent="Subject",this.subjectInput=document.createElement("input"),this.subjectInput.name="subject",this.subjectInput.type="text",this.subjectInput.className="contactFormField",i.appendChild(a),i.appendChild(this.subjectInput);const o=document.createElement("div");o.className="input-wrap gac-message-in";const r=document.createElement("label");r.textContent="Message",this.messageTextarea=document.createElement("textarea"),this.messageTextarea.className="contactFormField",this.messageTextarea.name="message",this.messageTextarea.required=!0,o.appendChild(r),o.appendChild(this.messageTextarea);const l=document.createElement("div");l.className="question",this.questionLabel=document.createElement("label"),this.questionLabel.id="question",this.questionLabel.textContent=this.n1+" + "+this.n2+"=",this.answerInput=document.createElement("input"),this.answerInput.className="contactFormField",this.answerInput.addEventListener("keyup",this.metch.bind(this)),l.appendChild(this.questionLabel),l.appendChild(this.answerInput);const c=document.createElement("div");c.className="contact-footer",this.submitButton=document.createElement("button"),this.submitButton.type="submit",this.submitButton.id="submitBtn",this.submitButton.className="contactFormButton",this.submitButton.textContent="Send",this.contactMsg=document.createElement("span"),this.contactMsg.className="contactMsg";const d=document.createElement("span");d.className="closeButton",d.innerHTML="&times;",this.closeButton=d,this.closeButton.addEventListener("click",this.handleClose.bind(this)),c.appendChild(this.submitButton),c.appendChild(this.contactMsg),this.formElement.appendChild(this.closeButton),this.formElement.appendChild(t),this.formElement.appendChild(n),this.formElement.appendChild(i),this.formElement.appendChild(o),this.formElement.appendChild(l),this.formElement.appendChild(c),this.dom.appendChild(this.formElement),this.body.appendChild(this.dom)}handleClose(){this.dom.remove()}getRandom(){return Math.ceil(20*Math.random())}}function h(){var t=jQuery("#ans").val(),e=!!t&t==o;e?jQuery(".question").css("border-color","green"):jQuery(".question").css("border-color","red"),jQuery("button[type=submit]").prop("disabled",!e)}jQuery(".write-us-btn").click((function(){new d})),jQuery("#reff").val(document.referrer),r=c(),l=c(),o=r+l,jQuery("#question").text(r+" + "+l+"="),jQuery("#success, #fail").hide(),jQuery("#message").show(),jQuery("#ans").keyup(h),jQuery("#ans").change(h),jQuery("#contactForm").submit((function(t){jQuery("#submitBtn").attr("type","button"),jQuery("#submitBtn").html(" Sending..."),jQuery("#submitBtn").after("<span class='spinLoader'><i></i><i></i><i></i><i></i><i></i><i></i></span>"),t.preventDefault();var e=jQuery("#contactForm").serialize();jQuery.post(AJAXURL+"contacts/send/",e,(function(t){jQuery(".spinLoader").remove();var e=JSON.parse(t);!1===e.error?(jQuery("#contactForm")[0].reset(),jQuery(".contactMsg").html(e.message).css("color","green"),jQuery("#submitBtn").html(" Sent !"),i.clearPriview(),setTimeout((function(){jQuery("#submitBtn").html(" Send ")}),2e3)):(jQuery(".contactMsg").html(e.message).css("color","red"),jQuery("#submitBtn").attr("type","submit"))}))}));class u{constructor(){this.count=0,this.cartContainer=document.createElement("div"),this.cartContainer.id="cart",this.counterDom=document.createElement("span"),this.counterDom.classList.add("item-counter"),this.counterDom.textContent=this.count,this.cartIcon=document.createElement("div"),this.cartIcon.id="cart-icon",this.cartIcon.classList.add("cart-icon"),this.cartIcon.innerHTML='<svg viewBox="0 0 512 512"><circle cx="176" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="400" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M48 80h64l48 272h256"/><path d="M160 288h249.44a8 8 0 007.85-6.43l28.8-144a8 8 0 00-7.85-9.57H128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>',this.cartIcon.addEventListener("click",this.toggleCart.bind(this)),this.cartIcon.appendChild(this.counterDom),document.querySelectorAll(".cart-toggler").forEach((t=>{t.addEventListener("click",this.toggleCart.bind(this))})),this.cartContent=document.createElement("div"),this.cartContent.classList.add("cart-content"),this.cartContent.classList.add("hidden");let t=document.createElement("span");t.classList.add("cart-remove"),t.innerHTML="&times;",t.addEventListener("click",(t=>{this.hideContetn()})),this.cartContent.appendChild(t),this.cartItems=document.createElement("div"),this.cartItems.id="cart-items",this.cartItems.classList.add("cart-items"),this.cartItems.innerHTML="There is nothing in the basket.",this.cartContent.appendChild(this.cartItems),this.step2=document.createElement("div"),this.step2.classList.add("step-send"),this.iteminfoStr=document.createElement("p"),this.iteminfoStr.classList.add("cart-item-information"),this.iteminfoStr.innerHTML="No Item has been selected to get price !",this.step2.appendChild(this.iteminfoStr),this.step2.appendChild(this.form()),this.cartContent.appendChild(this.step2),this.footerwrap=document.createElement("div"),this.footerwrap.classList.add("cart-footer"),this.continueButton=document.createElement("button"),this.continueButton.classList.add("cart-continue"),this.continueButton.innerHTML="Continue",this.continueButton.addEventListener("click",(()=>{this.cartContent.classList.toggle("cart-send")})),this.footerwrap.appendChild(this.continueButton),this.sendButton=document.createElement("button"),this.sendButton.classList.add("cart-send-btn"),this.sendButton.innerHTML="Send",this.sendButton.addEventListener("click",(()=>{this.send()})),this.footerwrap.appendChild(this.sendButton),this.cancleButton=document.createElement("button"),this.cancleButton.classList.add("cancle-continue"),this.cancleButton.innerHTML="Cancle",this.cancleButton.addEventListener("click",(()=>{this.hideContetn()})),this.footerwrap.appendChild(this.cancleButton),this.cartContent.appendChild(this.footerwrap),this.cartContainer.appendChild(this.cartIcon),this.cartContainer.appendChild(this.cartContent),document.body.appendChild(this.cartContainer),this.cartData={},this.loadCart()}async send(){this.sendButton.innerHTML="Sending...";const t=this.formD.querySelector(".quoteForm");if(t.checkValidity()){const n=new FormData(t),s={};n.forEach(((t,e)=>{s[e]=t})),await fetch(AJAXURL+"cart/send/",{method:"POST",body:JSON.stringify(s),headers:{"Content-Type":"application/json"}}).then((t=>t.json())).then((t=>{t.error||(this.sendButton="Send",new e({message:"Price Request successfully Sent",type:"success",timeout:5e3}),this.hideContetn(),this.cartData=[],this.countUpdates())}))}else{const n=t.querySelectorAll(":invalid");this.sendButton.innerHTML="Send",n.forEach((t=>{const n=t.validationMessage;new e({message:`Error in ${t.name} : ${n}`,type:"error",timeout:1e3})}))}}form(){return this.formD=document.createElement("div"),this.formD.innerHTML='        \n    <form class="quoteForm">\n        <div class="quoteContactDetails">\n            <div class="name-email-area">\n                <div class="name-area">\n                    <input type="text" name="name" pattern="[A-Za-z ]{1,32}" title="Please Enter a valid name" placeholder="Name" required="">\n                </div>\n                <div class="whp-area">\n                    <input type="text" name="whatsapp" pattern="[\\-\\+0-9]+" title="You should Enter a valid WhatsApp Number" placeholder="WhatsApp" required="">\n                </div>\n            </div>\n            <input type="email" name="email" placeholder="Email" required="">\n            <textarea name="message" rows="6" placeholder="Write your Message Here"></textarea>\n        </div>\n    </form',this.formD}hideContetn(){this.cartContent.classList.add("hidden"),this.cartContent.classList.remove("cart-send"),this.cartContainer.classList.remove("open")}toggleCart(){this.cartContent.classList.toggle("hidden"),this.cartContainer.classList.toggle("open")}loadCart(){fetch(AJAXURL+"cart/").then((t=>t.json())).then((t=>{this.cartData=t.items,this.updateCartView()}))}countUpdates(){if(this.count=this.cartData.length,0==this.count)this.hideContetn(),this.cartItems.innerHTML="There is nothing in the basket.",this.iteminfoStr.innerHTML="No Item has been selected to get price !";else{this.iteminfoStr.innerHTML="";let t=document.createElement("span");t.innerHTML=this.count+" Item"+(this.count>1?"s":""),t.classList.add("itemtrig"),t.addEventListener("click",(()=>{this.cartContent.classList.toggle("cart-send")})),this.iteminfoStr.appendChild(t);let e=document.createElement("span");e.innerHTML=" has been selected to get price.",this.iteminfoStr.appendChild(e)}document.querySelectorAll(".cart-toggler .item-counter").forEach((t=>{t.textContent=this.count})),this.counterDom.textContent=this.count}updateCartView(){this.cartItems.innerHTML="",this.countUpdates(),this.cartData.length>0&&this.cartContainer.classList.contains("hidden")&&this.cartContainer.classList.remove("hidden"),this.cartData.forEach(((t,e)=>{const n=document.createElement("div");n.classList.add("cart-item"),n.setAttribute("data-index",e);let s=document.createElement("img");s.src=t.imageUrl,s.title=t.name,s.alt=t.name,n.appendChild(s);let i=document.createElement("a");i.href=t.url,i.innerHTML=t.name,n.appendChild(i);const a=document.createElement("span");a.innerHTML="&times;",a.classList.add("remove-item"),a.addEventListener("click",(()=>this.removeItem(t.id,(t=>{n.remove();let e=n.getAttribute("data-index");this.cartData.splice(e,1),this.updateCartView()})))),n.appendChild(a),this.cartItems.appendChild(n)}))}async addItem(t,n){await fetch(AJAXURL+"cart/add-item/",{method:"POST",body:JSON.stringify({id:t,name:n}),headers:{"Content-Type":"application/json"}}).then((t=>t.json())).then((t=>{t.error||(new e({message:"Item added successfully in Query List",type:"info",timeout:1e3}),this.cartData=t.items,this.updateCartView())}))}removeItem(t,n){fetch(AJAXURL+"cart/remove-item/",{method:"POST",body:JSON.stringify({id:t}),headers:{"Content-Type":"application/json"}}).then((t=>t.json())).then((t=>{t.error||(new e({message:"Item Removed from Query List",type:"info",timeout:1e3}),n.call(t))}))}}window.addEventListener("DOMContentLoaded",(()=>{const t=new u;document.querySelectorAll(".add2cart").forEach((function(e){e.addEventListener("click",(n=>{const s=e.textContent;e.classList.add("on-request"),e.textContent="";const i=document.createElement("span");i.className="spinner-icon",e.appendChild(i),t.addItem(e.getAttribute("data-id"),e.getAttribute("data-name")).then((()=>{e.removeChild(i),e.classList.remove("on-request");const t=document.createElement("span");t.className="checkmark-icon",t.innerHTML='<svg viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M416 128L192 384l-96-96"/></svg> Added into List',e.appendChild(t),setTimeout((()=>{e.textContent=s}),2e3)}))}))}))}));n(99);new t(document.querySelectorAll("nav.nav-menu"))})()})();