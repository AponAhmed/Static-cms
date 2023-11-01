
import Notification from "./Notification";

class CartManager {
    constructor() {
        // Create the cart container
        this.count = 0;
        this.cartContainer = document.createElement('div');
        this.cartContainer.id = 'cart';
        //this.cartContainer.classList.add('hidden');

        this.counterDom = document.createElement('span');
        this.counterDom.classList.add('item-counter');
        this.counterDom.textContent = this.count;
        // Create the cart icon and add a click event listener
        this.cartIcon = document.createElement('div');
        this.cartIcon.id = 'cart-icon';
        this.cartIcon.classList.add('cart-icon');
        this.cartIcon.innerHTML = '<svg viewBox="0 0 512 512"><circle cx="176" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="400" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M48 80h64l48 272h256"/><path d="M160 288h249.44a8 8 0 007.85-6.43l28.8-144a8 8 0 00-7.85-9.57H128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>';
        this.cartIcon.addEventListener('click', this.toggleCart.bind(this));
        this.cartIcon.appendChild(this.counterDom);
        //All cart toggler event
        let togglers = document.querySelectorAll('.cart-toggler');
        togglers.forEach((element) => {
            element.addEventListener('click', this.toggleCart.bind(this));
        })



        //Cart Contents
        this.cartContent = document.createElement('div');
        this.cartContent.classList.add('cart-content');
        this.cartContent.classList.add('hidden');
        // Add a remove button and event listener to remove items
        let trig = document.createElement('span');
        trig.classList.add('cart-remove');
        trig.innerHTML = '&times;';
        trig.addEventListener('click', e => {
            this.hideContetn();
        });

        this.cartContent.appendChild(trig);
        // Create the cart items container
        this.cartItems = document.createElement('div');
        this.cartItems.id = 'cart-items';
        this.cartItems.classList.add('cart-items');
        this.cartItems.innerHTML = 'There is nothing in the basket.';

        this.cartContent.appendChild(this.cartItems);
        this.step2 = document.createElement('div');
        this.step2.classList.add('step-send');
        this.iteminfoStr = document.createElement('p');
        this.iteminfoStr.classList.add('cart-item-information');
        this.iteminfoStr.innerHTML = 'No Item has been selected to get price !';
        this.step2.appendChild(this.iteminfoStr);
        this.step2.appendChild(this.form());
        this.cartContent.appendChild(this.step2);
        //Footer
        this.footerwrap = document.createElement('div');
        this.footerwrap.classList.add('cart-footer');
        //Continue Button
        this.continueButton = document.createElement('button');
        this.continueButton.classList.add('cart-continue');
        this.continueButton.innerHTML = 'Continue';
        this.continueButton.addEventListener('click', () => {
            this.cartContent.classList.toggle('cart-send');//Send Toggle
        });
        this.footerwrap.appendChild(this.continueButton);
        //Send Button
        this.sendButton = document.createElement('button');
        this.sendButton.classList.add('cart-send-btn');
        this.sendButton.innerHTML = 'Send';
        this.sendButton.addEventListener('click', () => {
            this.send();
        });
        this.footerwrap.appendChild(this.sendButton);

        this.cancleButton = document.createElement('button');
        this.cancleButton.classList.add('cancle-continue');
        this.cancleButton.innerHTML = 'Cancle';
        this.cancleButton.addEventListener('click', () => {
            this.hideContetn();
        });
        this.footerwrap.appendChild(this.cancleButton);

        this.cartContent.appendChild(this.footerwrap);

        // Append elements to the cart container
        this.cartContainer.appendChild(this.cartIcon);
        this.cartContainer.appendChild(this.cartContent);

        // Append the cart container to the body
        document.body.appendChild(this.cartContainer);

        // Initialize cart by loading data from the server
        this.cartData = {};
        this.loadCart();
    }

    async send() {
        this.sendButton.innerHTML = 'Sending...';
        const formElement = this.formD.querySelector('.quoteForm');
        if (formElement.checkValidity()) {
            const formData = new FormData(formElement);

            // Convert FormData to a JavaScript object
            const formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });
            // Now you can send an AJAX request with the formObject
            // Replace this with your actual AJAX code
            await fetch(AJAXURL + 'cart/send/', {
                method: 'POST',
                body: JSON.stringify(formObject),
                headers: {
                    'Content-Type': 'application/json',
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.error) {
                        this.sendButton = 'Send';
                        new Notification({ message: 'Price Request successfully Sent', type: 'success', timeout: 5000 });
                        this.hideContetn();
                        this.cartData = [];
                        this.countUpdates();
                        //this.cartData = data.items;
                        //this.updateCartView();
                    }
                });
        } else {
            // Handle validation errors here
            const invalidInputs = formElement.querySelectorAll(':invalid');
            this.sendButton.innerHTML = 'Send';
            // Loop through invalid inputs and get error messages
            invalidInputs.forEach((input) => {
                const errorMessage = input.validationMessage;
                new Notification({ message: `Error in ${input.name} : ${errorMessage}`, type: 'error', timeout: 1000 });
            });
        }
    }

    form() {
        this.formD = document.createElement('div');
        this.formD.innerHTML = `        
    <form class="quoteForm">
        <div class="quoteContactDetails">
            <div class="name-email-area">
                <div class="name-area">
                    <input type="text" name="name" pattern="[A-Za-z ]{1,32}" title="Please Enter a valid name" placeholder="Name" required="">
                </div>
                <div class="whp-area">
                    <input type="text" name="whatsapp" pattern="[\\-\\+0-9]+" title="You should Enter a valid WhatsApp Number" placeholder="WhatsApp" required="">
                </div>
            </div>
            <input type="email" name="email" placeholder="Email" required="">
            <textarea name="message" rows="6" placeholder="Write your Message Here"></textarea>
        </div>
    </form`;
        return this.formD;
    }

    hideContetn() {
        this.cartContent.classList.add('hidden');
        this.cartContent.classList.remove('cart-send');
        this.cartContainer.classList.remove('open');
    }

    toggleCart() {
        this.cartContent.classList.toggle('hidden');
        this.cartContainer.classList.toggle('open');
    }

    loadCart() {
        // Make an AJAX request to fetch cart data from the server
        // Update this.cartData with the response from the server

        // Example AJAX request with fetch:
        fetch(AJAXURL + 'cart/')
            .then(response => response.json())
            .then(data => {
                this.cartData = data.items;
                this.updateCartView();
            });
    }
    countUpdates() {
        this.count = this.cartData.length;
        if (this.count == 0) {
            this.hideContetn();
            this.cartItems.innerHTML = 'There is nothing in the basket.';
            this.iteminfoStr.innerHTML = 'No Item has been selected to get price !';
        } else {
            this.iteminfoStr.innerHTML = '';
            let itemcontTrig = document.createElement('span');
            itemcontTrig.innerHTML = this.count + ` Item${this.count > 1 ? 's' : ''}`;
            itemcontTrig.classList.add('itemtrig');
            itemcontTrig.addEventListener('click', () => {
                this.cartContent.classList.toggle('cart-send');//Send Toggle
            })
            this.iteminfoStr.appendChild(itemcontTrig);
            let selectMsg = document.createElement('span');
            selectMsg.innerHTML = ` has been selected to get price.`;
            this.iteminfoStr.appendChild(selectMsg);

        }
        let counters = document.querySelectorAll('.cart-toggler .item-counter');
        counters.forEach((element) => {
            element.textContent = this.count;
        })

        this.counterDom.textContent = this.count;
    }

    updateCartView() {
        this.cartItems.innerHTML = '';
        this.countUpdates();
        // Update the cart view based on this.cartData
        // This function should render cart items in the cartItems element
        if (this.cartData.length > 0 && this.cartContainer.classList.contains("hidden")) {
            this.cartContainer.classList.remove('hidden');
        }
        // Example:


        this.cartData.forEach((item, index) => {
            const itemElement = document.createElement('div');
            itemElement.classList.add('cart-item');
            itemElement.setAttribute('data-index', index);
            //itemElement.textContent = item.name;
            //image
            let img = document.createElement('img');
            img.src = item.imageUrl;
            img.title = item.name;
            img.alt = item.name;
            itemElement.appendChild(img);
            let link = document.createElement('a');
            link.href = item.url;
            link.innerHTML = item.name;
            itemElement.appendChild(link);
            // Add a remove button and event listener to remove items
            const removeButton = document.createElement('span');
            removeButton.innerHTML = '&times;'
            removeButton.classList.add('remove-item');
            removeButton.addEventListener('click', () => this.removeItem(item.id, (data) => {
                itemElement.remove();
                let i = itemElement.getAttribute('data-index');
                this.cartData.splice(i, 1);
                //this.countUpdates();
                this.updateCartView();
            }));
            itemElement.appendChild(removeButton);
            this.cartItems.appendChild(itemElement);
        });

    }

    async addItem(id, name) {
        // Make an AJAX request to add an item to the server-side cart
        // Update this.cartData with the response from the server

        // Example AJAX request with fetch:

        await fetch(AJAXURL + 'cart/add-item/', {
            method: 'POST',
            body: JSON.stringify({ id, name }),
            headers: {
                'Content-Type': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    new Notification({ message: 'Item added successfully in Query List', type: 'info', timeout: 1000 });
                    this.cartData = data.items;
                    this.updateCartView();
                }
            });
    }

    removeItem(id, callback) {
        // Make an AJAX request to remove an item from the server-side cart
        // Update this.cartData with the response from the server

        // Example AJAX request with fetch:
        fetch(AJAXURL + 'cart/remove-item/', {
            method: 'POST',
            body: JSON.stringify({ id }),
            headers: {
                'Content-Type': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    new Notification({ message: 'Item Removed from Query List', type: 'info', timeout: 1000 });
                    callback.call(data);
                }
            });
    }

}

// Initialize the CartManager when the page loads
const checkicon = `<svg viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M416 128L192 384l-96-96"/></svg>`;
window.addEventListener('DOMContentLoaded', () => {
    const cartManager = new CartManager();
    let add2cart = document.querySelectorAll('.add2cart');

    add2cart.forEach(function (btn) {
        btn.addEventListener('click', (e) => {
            const originalText = btn.textContent;
            btn.classList.add('on-request');
            btn.textContent = ''; // Clear button text

            // Create an animated icon (e.g., spinner)
            const spinnerIcon = document.createElement('span');
            spinnerIcon.className = 'spinner-icon';
            btn.appendChild(spinnerIcon);
            cartManager.addItem(btn.getAttribute('data-id'), btn.getAttribute('data-name')).then(() => {
                // Remove the spinner icon
                btn.removeChild(spinnerIcon);
                btn.classList.remove('on-request');
                // Create a checkmark icon
                const checkmarkIcon = document.createElement('span');
                checkmarkIcon.className = 'checkmark-icon';
                checkmarkIcon.innerHTML = checkicon + ' Added into List';
                btn.appendChild(checkmarkIcon);

                // Wait for a moment, then reset the button
                setTimeout(() => {
                    btn.textContent = originalText;
                }, 2000); // Adjust the time according to your preference
            });
        });
    });
});

