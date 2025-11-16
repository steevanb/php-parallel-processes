document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".admonition .admonition-title").forEach(title => {
        if (title.textContent.trim() === "Try it!") {
            const link = document.createElement("a");
            link.href = "/en/latest/examples/available-examples";
            link.className = "tryit-link";
            link.textContent = "How to execute examples";
            title.appendChild(link);
        }
    });
});
