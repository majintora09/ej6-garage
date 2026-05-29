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
                window.prompt(button.getAttribute("data-copy-prompt-label") || button.getAttribute("data-copied-label") || shareUrl, shareUrl);
            }
        });
    });

    document.querySelectorAll("[data-image-preview-input]").forEach(input => {
        const previewId = input.getAttribute("data-preview-target");
        const preview = previewId ? document.getElementById(previewId) : null;
        const image = preview?.querySelector("img");

        if (!preview || !image) {
            return;
        }

        const updatePosition = () => {
            const selector = document.querySelector(`[data-image-position-select][data-preview-target="${previewId}"]`);
            image.style.objectPosition = selector?.value || "center";
        };

        input.addEventListener("change", () => {
            const file = input.files?.[0];

            if (!file) {
                preview.hidden = true;
                image.removeAttribute("src");
                return;
            }

            image.src = URL.createObjectURL(file);
            updatePosition();
            preview.hidden = false;
        });

        document.querySelectorAll(`[data-image-position-select][data-preview-target="${previewId}"]`).forEach(selector => {
            selector.addEventListener("change", updatePosition);
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
