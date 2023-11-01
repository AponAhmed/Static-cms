
const wrap = document.querySelector('.menu-list');
highlightDropZones = function () {
    let dropZone = wrap.querySelectorAll('ul');
    dropZone.forEach((el) => {
        el.classList.add('drag-placeholder');
    });

}

unhighlightDropZones = function () {
    let dropZone = wrap.querySelectorAll('ul');
    dropZone.forEach((el) => {
        el.classList.remove('drag-placeholder');
    });
}


class ItemEditor {
    constructor(data = {}, callbacks, open = false, blink = false) {
        this.ui = false;
        this.blink = blink;
        this.callbacks = callbacks;
        const defaultData = {
            title: 'Menu Title',
            url: '',
            className: '',
            newWindow: false,
            child: []

        };

        this.data = {
            ...defaultData,
            ...data
        }
        this.createMenuItem();
        if (open) {
            this.element.classList.add('menu-open');
        }
        this.setupEventListeners()
    }

    changedData() {
        this.titleLabel.innerHTML = this.data.title;
        if (this.callbacks) {
            this.callbacks.call(this);
        }
    }

    createMenuItem() {
        this.element = document.createElement("li");
        this.element.className = "list-item";

        this.singleMenuItem = document.createElement("div");
        this.singleMenuItem.className = "single-menu-item";

        this.menuItemHeader = document.createElement("div");
        this.menuItemHeader.className = "menu-item-header";


        this.sortableItem = document.createElement("div");
        this.sortableItem.className = "sortable-item";
        this.menuItemHeader.appendChild(this.sortableItem);

        this.titleLabel = document.createElement("label");
        this.titleLabel.className = "single-title-label";
        this.titleLabel.textContent = this.data.title;
        this.menuItemHeader.appendChild(this.titleLabel);

        this.triggerSpan = document.createElement("span");
        this.triggerSpan.className = "trigger";
        this.triggerSpan.textContent = "";
        this.menuItemHeader.appendChild(this.triggerSpan);

        this.removeDom = document.createElement("span");
        this.removeDom.className = "remove-item";
        this.removeDom.innerHTML = "&times;";
        this.menuItemHeader.appendChild(this.removeDom);

        this.singleMenuItem.appendChild(this.menuItemHeader);

        this.menuItemContent = document.createElement("div");
        this.menuItemContent.className = "menu-item-content";

        if (this.data.multiple) {
            this.menuItemHeader.classList.add("multi-page-menu");
            this.menuItemHeader.title = "Multiple Page's Menu";
        }
        if (this.blink) {
            this.menuItemHeader.classList.add("blink-once");
            this.menuItemContent.classList.add("blink-once");
        }

        this.titleDiv = this.createInputDiv("Title:", "menu-title", this.data.title);
        this.titleInput = this.titleDiv.querySelector(".menu-title");
        this.menuItemContent.appendChild(this.titleDiv);

        this.urlDiv = this.createInputDiv("URL:", "menu-url", this.data.url);
        this.urlInput = this.urlDiv.querySelector(".menu-url");
        this.menuItemContent.appendChild(this.urlDiv);

        this.classDiv = this.createInputDiv("Class:", "menu-class", this.data.className);
        this.classInput = this.classDiv.querySelector(".menu-class");
        this.menuItemContent.appendChild(this.classDiv);

        this.newWindowDiv = this.createCheckboxDiv("Open in new window:", "menu-new-window", this.data.newWindow);
        this.newWInput = this.newWindowDiv.querySelector(".menu-new-window");
        this.menuItemContent.appendChild(this.newWindowDiv);

        this.singleMenuItem.appendChild(this.menuItemContent);

        this.childList = document.createElement("ul");
        this.childList.className = "child-list";
        this.singleMenuItem.appendChild(this.childList);

        this.element.appendChild(this.singleMenuItem);

        new Sortable(this.childList, {
            animation: 150,
            handle: ".sortable-item",
            group: "nested",
            fallbackOnBody: true,
            onUpdate: () => this.callbacks ? this.callbacks.call(this) : null,
            onStart: () => {
                highlightDropZones();
            },
            onEnd: () => {
                unhighlightDropZones();
            },
        });

        this.ui = this.element;
    }

    createInputDiv(labelText, inputClass, inputValue) {
        const div = document.createElement("div");

        const label = document.createElement("label");
        label.textContent = labelText;
        div.appendChild(label);

        const input = document.createElement("input");
        input.className = inputClass;
        input.type = "text";
        input.value = inputValue;
        div.appendChild(input);
        return div;
    }

    createCheckboxDiv(labelText, inputClass, isChecked) {
        const div = document.createElement("div");

        const label = document.createElement("label");
        label.textContent = labelText;
        label.classList.add('checkbox-type');

        const input = document.createElement("input");
        input.className = inputClass;
        input.type = "checkbox";
        if (isChecked) {
            input.checked = true;
        }
        div.appendChild(input);
        div.appendChild(label);
        return div;
    }

    async remove() {
        const confirmationDialog = new ConfirmationDialog();
        // To show the confirmation dialog and get the result:
        confirmationDialog.confirm(`Sure to remove '${this.data.title}' from Menu ?`)
            .then((confirmed) => {
                if (confirmed) {
                    // User clicked "Yes"
                    this.element.remove();
                } else {
                    // User clicked "No" or closed the dialog
                    console.log('Not confirmed');
                }
            });
    }

    setupEventListeners() {
        this.triggerSpan.addEventListener('click', () => {
            this.trigger(); // Open or Close
        });
        this.removeDom.addEventListener('click', () => {
            this.remove(); // remove
        });

        this.titleInput.addEventListener('keyup', () => {
            this.data.title = this.titleInput.value;
            this.titleLabel.textContent = this.data.title;
            this.changedData();
        });

        this.urlInput.addEventListener('keyup', () => {
            this.data.url = this.urlInput.value;
            this.changedData();
        });

        this.classInput.addEventListener('keyup', () => {
            this.data.className = this.classInput.value;
            this.changedData();
        });

        this.newWInput.addEventListener('change', () => {
            this.data.newWindow = this.newWInput.checked;
            this.changedData();
        });
    }

    trigger() {
        if (this.element.classList.contains('menu-open')) {
            this.element.classList.remove('menu-open');
        } else {
            this.element.classList.add('menu-open');
        }
    }
}

class SingleItem {
    constructor(item, callbacks) {
        this.data = item
        this.itemEditor = new ItemEditor(this.data, callbacks);
        this.childrens = [];

        if (this.data.hasOwnProperty('child') && this.data.child.length > 0) {
            this.data.child.forEach((el) => {
                let child = new SingleItem(el, callbacks);
                this.itemEditor.childList.appendChild(child.itemEditor.ui);
                this.childrens.push(child);
            });
        }
    }
}


class MenuManager {
    constructor(data, dom) {
        this.dom = dom;
        this.wrap = this.dom.querySelector('.menu-list');
        this.data = data || [];
        this.objs = [];
        this.buildUi();
        this.addEvent();
        this.json = null;

    }

    buildUi() {
        this.init(this.dom);
        this.data.forEach((el) => {
            this.obj = new SingleItem(el, this.modified);
            this.objs.push(this.obj);
            this.render();
        })
    }

    modified() {
        console.log('Modified');
    }

    render() {
        this.wrap.appendChild(this.obj.itemEditor.ui);
    }

    add2Menu() {
        // Get all the checkboxes with the class "add2menu"
        const checkboxes = document.querySelectorAll('.add2menu:checked');
        // Loop through the selected checkboxes
        checkboxes.forEach(checkbox => {
            // Extract data attributes from the checkbox
            const dataMultiple = checkbox.getAttribute('data-multiple');
            const dataUrl = checkbox.getAttribute('data-url');
            const dataTitle = checkbox.getAttribute('data-title');
            const dataSlug = checkbox.getAttribute('data-slug');
            // Create an ItemEditor with the extracted data
            let editor = new ItemEditor({
                // Set properties based on the extracted data
                multiple: dataMultiple === 'yes',
                url: dataUrl,
                title: dataTitle,
                slug: dataSlug
                // Add other properties here as needed
            }, this.modified, false, true);
            // Append the editor UI to the container (this.wrap)
            //console.log(editor);
            this.wrap.appendChild(editor.ui);
            // Uncheck the checkbox after adding the item
            checkbox.checked = false;
        });
        // Scroll to the bottom of the container (this.wrap)
        this.wrap.scrollTop = this.wrap.scrollHeight;
    }

    addEvent() {
        let addBtn = document.querySelector("#addpage2menu");
        addBtn.addEventListener('click', this.add2Menu.bind(this));
    }

    scrollToBottom() {
        const scrollHeight = this.wrap.scrollHeight;
        const clientHeight = this.wrap.clientHeight;
        const maxScrollTop = scrollHeight - clientHeight;
        // Scroll to the bottom with smooth behavior
        this.wrap.scrollTo({
            top: maxScrollTop,
            behavior: "smooth"
        });
    }


    async updateMenu() {
        let wrap = document.querySelector('.menu-list');
        const linksJson = generateJSONFromDOM(wrap);
        let nameField = document.querySelector('#menuName');

        let oldFile = document.querySelector('#oldFile');

        let fromName = document.querySelector('#fileName').value;
        if (fromName == '') {
            return { 'status': 'error', 'message': 'Menu Name is required' };
        }
        const endpoint = ADMIN_URL + '/menu/update/';
        let fileData = { name: nameField.value, menu_links: linksJson, slug: fromName };
        const data = {
            'menu-name': fromName,
            'old-file': oldFile.value,
            'actionType': actionType.value,
            'data': JSON.stringify(fileData),
        };

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error('Failed to rename folder');
            }

            return response.json();
        } catch (error) {
            throw new Error(error.message);
        }
    }

    init(dom) {
        new Sortable(this.wrap, {
            animation: 150,
            handle: ".sortable-item",
            group: "nested",
            fallbackOnBody: true,
            onUpdate: () => this.modified(),
            onStart: () => {
                highlightDropZones();
            },
            onEnd: () => {
                unhighlightDropZones();
            },
        });
        let updatebtn = dom.querySelector('.menu-update');
        updatebtn.addEventListener("click", async (e) => {
            const response = await this.updateMenu();
            console.log(response);
            if (response.status == 'error') {
                alert(response.message);
            } else {
                alert(`Menu Updated successfully`);
            }
        });
        this.addNew = document.createElement("button");
        this.addNew.classList.add("btn-new-menu");
        this.addNew.innerHTML = '<svg viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 112v288M400 256H112"></path></svg> Custom Menu';
        this.addNew.title = 'Add Custom Menu';
        this.addNew.addEventListener("click", () => {
            let editor = new ItemEditor({}, this.modified, true);
            this.wrap.appendChild(editor.ui);
            this.scrollToBottom();
        });
        this.wrap.parentNode.appendChild(this.addNew);
    }
}





function generateJSONFromDOM(element) {
    const json = [];
    const children = element.children;
    for (let i = 0; i < children.length; i++) {
        const child = children[i];
        const menuItem = {};
        const label = child.querySelector('.single-title-label');
        if (label) {
            menuItem.title = label.textContent;
            const urlInput = child.querySelector('.menu-url');
            if (urlInput) {
                menuItem.url = urlInput.value;
            }
            const classInput = child.querySelector('.menu-class');
            if (classInput) {
                menuItem.className = classInput.value;
            }
            const newWindowInput = child.querySelector('.menu-new-window');
            if (newWindowInput) {
                menuItem.newWindow = newWindowInput.checked;
            }
        }
        const childList = child.querySelector('.child-list');
        if (childList) {
            menuItem.child = generateJSONFromDOM(childList);
        }
        json.push(menuItem);
    }
    return json;
}


function textToSlug(text) {
    return text
        .toLowerCase()                // Convert the text to lowercase
        .replace(/\s+/g, '-')         // Replace spaces with hyphens
        .replace(/[^a-z0-9-]/g, '')   // Remove any non-alphanumeric or hyphen characters
        .replace(/--+/g, '-')         // Replace consecutive hyphens with a single hyphen
        .replace(/^-+|-+$/g, '');    // Remove leading and trailing hyphens
}

menuName.addEventListener('keyup', (e) => {
    fileName.value = textToSlug(e.target.value);
});
