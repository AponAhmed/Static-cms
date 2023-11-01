document.addEventListener("DOMContentLoaded", function () {
    const addButton = document.getElementById("add-slide");
    const container = document.querySelector(".slider-container");

    addButton.addEventListener("click", function () {
        const newSlide = document.createElement("div");
        newSlide.classList.add("slider-item");

        newSlide.innerHTML = ` 
        <div class="slider-image">
            <label class="media-browser browser-slider slider-control-icon"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                    <path d="M64 192v-72a40 40 0 0140-40h75.89a40 40 0 0122.19 6.72l27.84 18.56a40 40 0 0022.19 6.72H408a40 40 0 0140 40v40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                    <path d="M479.9 226.55L463.68 392a40 40 0 01-39.93 40H88.25a40 40 0 01-39.93-40L32.1 226.55A32 32 0 0164 192h384.1a32 32 0 0131.8 34.55z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                </svg></label>
                <div class="input-fields">
            <input type="text" name="data[image][]">
            <input type="text" placeholder="Link" name="data[links][]">
            </div>
        </div>
        <div class="slider-details">
            <input type="text" name="data[image_title][]" placeholder="Title">
            <textarea name="data[image_description][]" placeholder="Description"></textarea>
        </div>
        <span onclick="removeSliderItem(this)" class="remove-slider-item slider-control-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                <path d="M112 112l20 320c.95 18.49 14.4 32 32 32h184c17.67 0 30.87-13.51 32-32l20-320" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 112h352" />
                <path d="M192 112V72h0a23.93 23.93 0 0124-24h80a23.93 23.93 0 0124 24h0v40M256 176v224M184 176l8 224M328 176l-8 224" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
            </svg>
        </span>
        `;

        container.appendChild(newSlide);
    });
});


function removeSliderItem(_this) {
    _this.closest('.slider-item').remove();
}