document.addEventListener('DOMContentLoaded', function () {

    //Functions for header and footer
    const header = document.querySelector('.site-header');
    const burger = document.querySelector('.cw-header__burger');

    if (header && burger) {
        burger.addEventListener('click', function () {
            header.classList.toggle('is-open');

            const expanded = burger.getAttribute('aria-expanded') === 'true';
            burger.setAttribute('aria-expanded', String(!expanded));
        });
    }

    const footerColumns = Array.from(document.querySelectorAll('.footer-column--menu'));
    const mobileQuery = window.matchMedia('(max-width: 767px)');

    if (footerColumns.length) {
        const closeAllColumns = (shouldUpdateAria = true) => {
            footerColumns.forEach((column) => {
                column.classList.remove('is-open');
                const title = column.querySelector('.footer-column__title');
                if (title && shouldUpdateAria) {
                    title.setAttribute('aria-expanded', 'false');
                }
            });
        };

        const toggleColumn = (column) => {
            if (!mobileQuery.matches) {
                return;
            }

            const isOpen = column.classList.contains('is-open');
            closeAllColumns();

            if (!isOpen) {
                column.classList.add('is-open');
                const title = column.querySelector('.footer-column__title');
                if (title) {
                    title.setAttribute('aria-expanded', 'true');
                }
            }
        };

        footerColumns.forEach((column) => {
            const title = column.querySelector('.footer-column__title');
            if (!title) {
                return;
            }

            title.addEventListener('click', () => toggleColumn(column));

            title.addEventListener('keydown', (event) => {
                const isEnter = event.key === 'Enter';
                const isSpace = event.key === ' ' || event.key === 'Spacebar';

                if (isEnter || isSpace) {
                    event.preventDefault();
                    toggleColumn(column);
                }
            });
        });

        const handleBreakpointChange = () => {
            if (mobileQuery.matches) {
                closeAllColumns();
                return;
            }

            closeAllColumns(false);
            footerColumns.forEach((column) => {
                const title = column.querySelector('.footer-column__title');
                if (title) {
                    title.setAttribute('aria-expanded', 'true');
                }
            });
        };

        if (mobileQuery.addEventListener) {
            mobileQuery.addEventListener('change', handleBreakpointChange);
        } else if (mobileQuery.addListener) {
            mobileQuery.addListener(handleBreakpointChange);
        }

        handleBreakpointChange();
    }
});

jQuery(function ($) {
    const $slider = $('.js-testimonials-slider');
    if ($slider.length) {
        $slider.slick({
            dots: true,
            arrows: true,
            adaptiveHeight: true,
            slidesToShow: 3,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }
});

document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.blog-loadmore');
    if (!btn) return;

    const max = parseInt(btn.dataset.max || '1', 10);
    const currentPage = parseInt(btn.dataset.page || '1', 10);
    const nextPage = currentPage + 1;

    if (nextPage > max) {
        btn.remove();
        return;
    }

    const grid = document.querySelector('.blog-cards');
    if (!grid) {
        btn.remove();
        return;
    }

    const exclude = grid.dataset.exclude || '';
    const contentType = grid.dataset.contentType || '';
    const date = grid.dataset.date || '';
    const search = grid.dataset.search || '';

    btn.classList.add('is-loading');
    btn.disabled = true;

    const form = new FormData();
    form.append('action', 'armoredlaw_load_more_posts');
    form.append('page', String(nextPage));
    form.append('exclude', exclude);
    form.append('content_type', contentType);
    form.append('date', date);
    form.append('s', search);

    form.append(
        'nonce',
        (window.armoredlawAjax && window.armoredlawAjax.nonce) ? window.armoredlawAjax.nonce : ''
    );

    try {
        const url =
            (window.armoredlawAjax && window.armoredlawAjax.url)
                ? window.armoredlawAjax.url
                : '/wp-admin/admin-ajax.php';

        const res = await fetch(url, {
            method: 'POST',
            body: form,
            credentials: 'same-origin',
        });

        const data = await res.json();

        if (!data || !data.success || !data.data || !data.data.html) {
            btn.remove();
            return;
        }

        grid.insertAdjacentHTML('beforeend', data.data.html);
        btn.dataset.page = String(nextPage);

        if (nextPage >= max) {
            btn.remove();
        } else {
            btn.classList.remove('is-loading');
            btn.disabled = false;
        }
    } catch (err) {
        btn.classList.remove('is-loading');
        btn.disabled = false;
    }
});
