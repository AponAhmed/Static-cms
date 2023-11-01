
function currentEditorInstance() {
    return $('#editorInstance').val();
}

function editor(id, type, h) {
    //CK Editor Initialize.
    //@param1-Selecor ID.
    //@param2-tools type(basic,full).
    CKEDITOR.replace(id, {
        entities: false,
        language: 'en',
        uiColor: '#ffffff',
        customConfig: '',
        resize_enabled: 'false',
        height: h + 'px',
        // toolbar: ''
    });

    CKEDITOR.config.imageUploadUrl = '/editor/upload/';
    CKEDITOR.config.extraPlugins = 'uploadimage', 'uploadwidget', 'notificationaggregator', 'notification', 'toolbar', 'button', 'filetools', 'dialogui', 'widget', 'widgetselection';
    //CKEDITOR.config.height = h + 'px';
    CKEDITOR.config.removePlugins = 'elementspath';
    CKEDITOR.config.allowedContent = {
        $1: {
            // Use the ability to specify elements as an object.
            elements: CKEDITOR.dtd,
            attributes: true,
            styles: true,
            classes: true,
        }
    };
    CKEDITOR.config.disallowedContent = 'script; *[on*]';
    if (type == null || type == "basic") {

        //if tools type basic than----
        CKEDITOR.config.extraPlugins = 'acolumn,wordcount,mylink,liststyle';
        CKEDITOR.config.toolbarGroups = [
            { name: 'clipboard', groups: ['clipboard', 'undo'] },
            { name: 'forms', groups: ['forms'] },
            { name: 'styles', groups: ['styles'] },
            { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
            { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'] },
            { name: 'links', groups: ['links'] },
            { name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing'] },
            { name: 'insert', groups: ['insert'] },
            { name: 'colors', groups: ['colors'] },
            { name: 'tools', groups: ['tools'] },
            { name: 'others', groups: ['others'] },
            { name: 'about', groups: ['about'] },
            { name: 'document', groups: ['mode', 'document', 'doctools', 'insert'] }
        ];
        CKEDITOR.config.removeButtons = 'Save,NewPage,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,Image,HiddenField,Subscript,Superscript,CopyFormatting,RemoveFormat,Outdent,Indent,CreateDiv,BidiLtr,Language,BidiRtl,Anchor,Flash,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,ShowBlocks,About,Undo,Redo,Strike,Maximize,Table,Styles,Font,Link,Unlink';
        //// CKEDITOR.config.toolbar = ['insert','insertMed'];
        //BulletedList,Blockquote


    } else if (type == "document") {
        //if tools type Full than----
        CKEDITOR.config.toolbarGroups = [
            { name: 'clipboard', groups: ['clipboard', 'undo'] },
            { name: 'forms', groups: ['forms'] },
            { name: 'styles', groups: ['styles'] },
            { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
            { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'] },
            { name: 'links', groups: ['links'] },
            { name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing'] },
            { name: 'insert', groups: ['insert'] },
            { name: 'colors', groups: ['colors'] },
            { name: 'tools', groups: ['tools'] },
            { name: 'others', groups: ['others'] },
            { name: 'about', groups: ['about'] },
            { name: 'document', groups: ['mode', 'document', 'doctools'] }
        ];
        CKEDITOR.config.removeButtons = 'Save,NewPage,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Superscript,CopyFormatting,RemoveFormat,NumberedList,Outdent,Indent,CreateDiv,BidiLtr,Language,BidiRtl,Anchor,Flash,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,ShowBlocks,About,Undo,Redo,Strike,Maximize,Table,Styles,Font,Link,Unlink';
    } else if (type == "simple") {
        //if tools type Full than----
        CKEDITOR.config.toolbarGroups = [
            //{ name: 'clipboard', groups: ['clipboard', 'undo'] },
            //{ name: 'forms', groups: ['forms'] },
            { name: 'styles', groups: ['styles'] },
            //{ name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
            { name: 'paragraph', groups: ['list', 'bidi', 'paragraph'] },
            { name: 'links', groups: ['links'] },
            { name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing'] },
            //{ name: 'insert', groups: ['insert'] },
            { name: 'colors', groups: ['colors'] },
            { name: 'tools', groups: ['tools'] },
            { name: 'others', groups: ['others'] },
            { name: 'about', groups: ['about'] },
            { name: 'document', groups: ['mode', 'document', 'doctools'] }
        ];
        CKEDITOR.config.removeButtons = 'Save,NewPage,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Superscript,CopyFormatting,RemoveFormat,NumberedList,Outdent,Indent,CreateDiv,BidiLtr,Language,BidiRtl,Anchor,Flash,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,ShowBlocks,About,Undo,Redo,Strike,Maximize,Table,Styles,Font,Link,Unlink';

    }
    //CKEDITOR.config.extraPlugins = 'imagepaste'; image past
}



/**
 * @param DOM of Tab Wraper
 * -Structure
 * div.tab-wrap
 * ->ul > li[data-id=id]
 * ->div.tab-contents-wrap
 *   ->div#id.tab-pan
 */

//Custom Dialog Popup
class DialogBox {
    constructor({ ...options }) {
        this.title = options.title || "Title Here";
        this.body = options.body || "Dialog Body Here";
        this.position = options.position || "center";
        this.actions = options.actions || [
            {
                label: "Ok",
                class: "btn-primary",
                callback: function (_this) {
                    _this.close();
                }
            }
        ];
        this.bind();
        return this;
    }
    build() {
        //build element
        let dialogBox = document.createElement("div");
        dialogBox.classList.add("dialog-box");
        //build header
        let header = document.createElement("div");
        header.classList.add("header");
        //Title wrap
        let titleWrap = document.createElement("div");
        titleWrap.classList.add("title-wrap");
        titleWrap.innerHTML = this.title;
        header.appendChild(titleWrap);
        //close button
        let closeButton = document.createElement("div");
        closeButton.classList.add("close-button");
        closeButton.innerHTML = "&times;";
        closeButton.addEventListener("click", () => {
            dialogBox.remove();
        });
        header.appendChild(closeButton);
        dialogBox.appendChild(header);
        //build body
        let body = document.createElement("div");
        body.classList.add("body");
        body.innerHTML = this.body;
        dialogBox.appendChild(body);
        //build actions
        if (this.actions.length > 0) {
            let actions = document.createElement("div");
            actions.classList.add("actions");
            this.actions.forEach((el) => {
                let action = document.createElement("div");
                action.classList.add("action");
                action.classList.add(el.className);
                action.innerHTML = el.label;
                action.addEventListener("click", () => {
                    el.callback(this);
                });
                actions.appendChild(action);
            });
            dialogBox.appendChild(actions);
        }
        this.dialogBox = dialogBox;
        return dialogBox;
    }
    bind() {
        //append dialog
        document.body.appendChild(this.build());
        //position dialog
        if (this.position == "center") {
            this.dialogBox.style.top = "50%";
            this.dialogBox.style.left = "50%";
            this.dialogBox.style.transform = "translate(-50%, -50%)";
        } else {
            //check position object or not
            //console.log(this.dialogBox.clientHeight);
            if (typeof this.position == "object") {
                this.dialogBox.style.top = (this.position.top - this.dialogBox.clientHeight) + "px";
                this.dialogBox.style.left = this.position.left + "px";
            }
        }

    }

    close() {
        this.dialogBox.remove();
    }
}

class Tab {
    constructor(dom) {
        this.dom = dom;
        this.init();
        this.target = false;//Target ID
    }
    init() {
        this.lis = this.dom.querySelectorAll('li.section-head');
        this.pans = this.dom.querySelectorAll('.tab-pan');
        this.lis.forEach((node) => {
            node.addEventListener('click', () => {
                this.removeActive();
                this.target = node.getAttribute('data-id');
                this.target = this.dom.querySelector("#" + this.target);
                node.classList.add('active');
                this.target.classList.add('active');
            });
        });

    }
    removeActive() {
        this.lis.forEach((node) => {
            node.classList.remove('active');
        });
        this.pans.forEach((node) => {
            node.classList.remove('active');
        });
    }
}

class ConfirmationDialog {
    constructor() {
        // Create a container for the confirmation UI
        this.confirmationContainer = document.createElement('div');
        this.confirmationContainer.classList.add('confirmation-container');

        this.yesButton = document.createElement('button');
        this.yesButton.textContent = 'Yes';
        this.noButton = document.createElement('button');
        this.noButton.textContent = 'No';


        this.yesButton.addEventListener('click', () => {
            this.resolve(true);
            this.removeConfirmationUI();
        });

        this.noButton.addEventListener('click', () => {
            this.resolve(false);
            this.removeConfirmationUI();
        });
        this.message = document.createElement('p');
        this.message.textContent = 'Confirm?';

        this.confirmationContainer.appendChild(this.message);
        this.confirmationContainer.appendChild(this.yesButton);
        this.confirmationContainer.appendChild(this.noButton);
    }

    async confirm(message = "Confirm?") {
        return new Promise((resolve) => {
            this.resolve = resolve; // Store the resolve function
            this.message.textContent = message || 'Confirm?';
            document.body.appendChild(this.confirmationContainer);
        });
    }

    removeConfirmationUI() {
        this.confirmationContainer.remove();
    }
}


// Alias function that wraps the ConfirmationDialog class
function confirmbox(message = "Confirm?") {
    const dialog = new ConfirmationDialog();
    return dialog.confirm(message);
}

window.onload = function () {
    // Get all the elements with class "confirm-delete" (or any other class you use)
    const confirmDeleteLinks = document.querySelectorAll('a.confirm');
    if (confirmDeleteLinks) {
        confirmDeleteLinks.forEach(link => {
            link.addEventListener('click', async (event) => {
                event.preventDefault(); // Prevent the default link behavior
                const msg = link.getAttribute('data-confirm') || 'Confirm to perform this action ?';
                const confirmationDialog = new ConfirmationDialog();
                // To show the confirmation dialog and get the result:
                confirmationDialog.confirm(msg)
                    .then((confirmed) => {
                        if (confirmed) {
                            // User clicked "Yes"
                            window.location.href = event.target.getAttribute('href');
                        } else {
                            // User clicked "No" or closed the dialog
                            console.log('Not confirmed');
                        }
                    });

            });
        });
    }


    $(".meta-box").each(function () {
        let _this = this;
        $(_this).find('.meta-box-header').click(function () {
            $(_this).find('.meta-box-container ').toggleClass('hide');
        });
    });
};
