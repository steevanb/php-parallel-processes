document.addEventListener("DOMContentLoaded", function(event) {
    document.querySelector(".md-search__input").addEventListener("focus", (e) => {
        const event = new CustomEvent("readthedocs-search-show");
        document.dispatchEvent(event);
    });
});
