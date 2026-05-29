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

            try {
                if (navigator.share) {
                    await navigator.share({ url: shareUrl });
                    return;
                }

                if (navigator.clipboard) {
                    await navigator.clipboard.writeText(shareUrl);
                }

                button.textContent = button.getAttribute("data-copied-label") || button.textContent;
            } catch (error) {
                window.prompt("Copy link", shareUrl);
            }
        });
    });

    document.querySelectorAll("[data-expand-button]").forEach(button => {
        button.addEventListener("click", () => {
            const post = button.closest(".feed-body")?.querySelector("[data-expandable-post]");

            if (!post) {
                return;
            }

            const expanded = post.classList.toggle("is-expanded");
            post.classList.toggle("is-collapsed", !expanded);
            button.textContent = expanded
                ? button.getAttribute("data-less-label")
                : button.getAttribute("data-more-label");
        });
    });

    document.addEventListener("click", event => {
        document.querySelectorAll(".profile-menu[open]").forEach(menu => {
            if (!menu.contains(event.target)) {
                menu.removeAttribute("open");
            }
        });
    });
});
