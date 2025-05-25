document.addEventListener('DOMContentLoaded', function () {
    const navbarToggler = document.querySelector('.navbar-toggler');
    let menuOpenIcon = document.getElementById('menu-open-icon');
    let menuCloseIcon = document.getElementById('menu-close-icon');

    if (!navbarToggler || !menuOpenIcon || !menuCloseIcon) {
        return;
    }

    navbarToggler.addEventListener('click', function () {
        if (menuOpenIcon.classList.contains('d-none')) {
            menuOpenIcon.classList.remove('d-none');
            menuCloseIcon.classList.add('d-none');
        } else {
            menuOpenIcon.classList.add('d-none');
            menuCloseIcon.classList.remove('d-none');
        }
    });
});

document.addEventListener('contextmenu', function(event) {
    event.preventDefault();
});

console.log("Welcome to the Nova Framework!");
console.log("Made with ❤️ by the Nova Team.");
