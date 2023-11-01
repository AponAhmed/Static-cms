
editor('mainEditor', 'basic', 400);


function editSlug(_tis) {
    _tis.style.display = 'none';
    PageSlug.style.display = 'flex';
}

function textToSlug(text) {
    return text
        .toLowerCase()                // Convert the text to lowercase
        .replace(/\s+/g, '-')         // Replace spaces with hyphens
        .replace(/[^a-z0-9-]/g, '')   // Remove any non-alphanumeric or hyphen characters
        .replace(/--+/g, '-')         // Replace consecutive hyphens with a single hyphen
        .replace(/^-+|-+$/g, '');    // Remove leading and trailing hyphens
}

PageTitle.addEventListener('keyup', (e) => {
    PageSlugNew.value = textToSlug(e.target.value);
});

let template = document.querySelector("#template");
if (template) {
    controlTemplateMetaBox(template.value);
    template.addEventListener('change', (e) => {
        let selecttedTemp = e.target.value;
        if (selecttedTemp != '') {
            controlTemplateMetaBox(selecttedTemp);
        } else {
            controlTemplateMetaBox();
        }
    });
}


function controlTemplateMetaBox(current = '') {
    current = current.replace('.php', '');
    let metaBoxes = document.querySelectorAll('.template-meta-box');
    console.log(current, metaBoxes);
    metaBoxes.forEach((box) => {
        if (current != '' && box.classList.contains(current)) {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    });
}