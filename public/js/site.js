document.addEventListener("DOMContentLoaded", () => {
    const page = document.querySelector(".page-content");

    if (page) {
        requestAnimationFrame(() => {
            page.classList.add("page-visible");
        });
    }

    document.querySelectorAll("a[href^='/']").forEach(link => {
        link.addEventListener("click", event => {
            const url = link.getAttribute("href");

            if (url === window.location.pathname || link.target === "_blank") {
                return;
            }

            event.preventDefault();

            document.body.classList.add("page-leaving");

            setTimeout(() => {
                window.location.href = url;
            }, 180);
        });
    });

    document.querySelectorAll("[data-share-url]").forEach(button => {
        button.addEventListener("click", async () => {
            const url = button.getAttribute("data-share-url");

            if (!url) {
                return;
            }

            const shareUrl = new URL(url, window.location.origin).toString();

            if (navigator.share) {
                await navigator.share({ url: shareUrl });
                return;
            }

            await navigator.clipboard.writeText(shareUrl);
            button.textContent = button.getAttribute("data-copied-label") || button.textContent;
        });
    });
});
