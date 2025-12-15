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

//Function fot Reciprocity Map
(function () {
    const states = [
        ["AL","Alabama"],["AK","Alaska"],["AZ","Arizona"],["AR","Arkansas"],["CA","California"],
        ["CO","Colorado"],["CT","Connecticut"],["DE","Delaware"],["FL","Florida"],["GA","Georgia"],
        ["HI","Hawaii"],["ID","Idaho"],["IL","Illinois"],["IN","Indiana"],["IA","Iowa"],
        ["KS","Kansas"],["KY","Kentucky"],["LA","Louisiana"],["ME","Maine"],["MD","Maryland"],
        ["MA","Massachusetts"],["MI","Michigan"],["MN","Minnesota"],["MS","Mississippi"],["MO","Missouri"],
        ["MT","Montana"],["NE","Nebraska"],["NV","Nevada"],["NH","New Hampshire"],["NJ","New Jersey"],
        ["NM","New Mexico"],["NY","New York"],["NC","North Carolina"],["ND","North Dakota"],["OH","Ohio"],
        ["OK","Oklahoma"],["OR","Oregon"],["PA","Pennsylvania"],["RI","Rhode Island"],["SC","South Carolina"],
        ["SD","South Dakota"],["TN","Tennessee"],["TX","Texas"],["UT","Utah"],["VT","Vermont"],
        ["VA","Virginia"],["WA","Washington"],["WV","West Virginia"],["WI","Wisconsin"],["WY","Wyoming"],
        ["DC","District of Columbia"]
    ];

    const select = document.getElementById("alStateSelect");
    const mapWrap = document.getElementById("alMapWrap");
    if (!select || !mapWrap) return;

    select.innerHTML = `<option value="">Select State</option>` + states
        .map(([code,name]) => `<option value="${code}">${name}</option>`)
        .join("");
})();
