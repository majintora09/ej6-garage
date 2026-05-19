document.addEventListener('DOMContentLoaded', () => {
    const page = document.querySelector('.page-content');

    if (page) {
        page.classList.add('page-visible');
    }

    document.querySelectorAll('a[href^="/"]').forEach(link => {
        link.addEventListener('click', (event) => {
            const url = link.getAttribute('href');

            if (
                url === window.location.pathname ||
                link.target === '_blank'
            ) {
                return;
            }

            event.preventDefault();

            document.body.classList.add('page-leaving');

            setTimeout(() => {
                window.location.href = url;
            }, 260);
        });
    });
});
