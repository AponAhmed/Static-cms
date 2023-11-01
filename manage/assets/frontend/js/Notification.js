class Notification {
    constructor({ ...options }) {
        this.type = options.type || "success";
        this.message = options.message || "";
        this.timeout = options.timeout || 6000;
        //if not success then error and timeout should increse
        if (this.type !== "success") {
            this.timeout = this.timeout * 2;
        }
        this.bind();
    }
    build() {
        //element
        let singleElement = document.createElement("div");
        singleElement.classList.add("notification");
        singleElement.classList.add(this.type);
        if (this.type == 'alert' || this.type == 'warning') {
            singleElement.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="notification-icon" viewBox="0 0 512 512"><title>Warning</title><path d="M85.57 446.25h340.86a32 32 0 0028.17-47.17L284.18 82.58c-12.09-22.44-44.27-22.44-56.36 0L57.4 399.08a32 32 0 0028.17 47.17z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M250.26 195.39l5.74 122 5.73-121.95a5.74 5.74 0 00-5.79-6h0a5.74 5.74 0 00-5.68 5.95z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 397.25a20 20 0 1120-20 20 20 0 01-20 20z"/></svg>';
        } else if (this.type == 'info') {
            singleElement.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="notification-icon" viewBox="0 0 512 512"><path d="M248 64C146.39 64 64 146.39 64 248s82.39 184 184 184 184-82.39 184-184S349.61 64 248 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M220 220h32v116"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M208 340h88"/><path d="M248 130a26 26 0 1026 26 26 26 0 00-26-26z"/></svg>';
        } else if (this.type == 'success') {
            singleElement.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="notification-icon" viewBox="0 0 512 512"><title>Checkmark</title><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M416 128L192 384l-96-96"/></svg>';
        } else {
            singleElement.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="notification-icon" viewBox="0 0 512 512"><title>Close Circle</title><path d="M448 256c0-106-86-192-192-192S64 150 64 256s86 192 192 192 192-86 192-192z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M320 320L192 192M192 320l128-128"/></svg>';
        }
        //message
        let messageElement = document.createElement("div");
        messageElement.classList.add("message");
        messageElement.innerHTML = this.message;
        singleElement.appendChild(messageElement);
        //close
        let closeElement = document.createElement("div");
        closeElement.classList.add("close");
        closeElement.innerHTML = "&times;";
        closeElement.addEventListener("click", () => {
            singleElement.remove();
        });
        singleElement.appendChild(closeElement);
        singleElement.classList.add('slide-righr');
        return singleElement;
    }

    bind() {
        //check existance of notifications wraper
        let notificationsWrapper = document.querySelector(".notifications");
        if (!notificationsWrapper) {
            notificationsWrapper = document.createElement("div");
            notificationsWrapper.classList.add("notifications");
            document.body.appendChild(notificationsWrapper);
        }
        //append notification
        notificationsWrapper.appendChild(this.build());
        //remove notification after timeout
        setTimeout(() => {
            if (notificationsWrapper.firstChild) {
                notificationsWrapper.removeChild(notificationsWrapper.firstChild);
            }
        }, this.timeout);
    }

}
export default Notification;