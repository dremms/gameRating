import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["menu"]

    toggle(event) {
        event.currentTarget.classList.toggle("is-active");
        this.menuTarget.classList.toggle("is-active");
    }
}