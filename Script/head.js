document.addEventListener('DOMContentLoaded', function () {
    // ACTIVE MENU ITEM
    const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

    allSideMenu.forEach(item => {
        const li = item.parentElement;

        item.addEventListener('click', function () {
            allSideMenu.forEach(i => {
                i.parentElement.classList.remove('active');
            });
            li.classList.add('active');
        });
    });

    // TOGGLE SIDEBAR
    const menuBar = document.querySelector('nav .bx.bx-menu');
    const sidebar = document.getElementById('sidebar');

    menuBar.addEventListener('click', function () {
        sidebar.classList.toggle('hide');

        // Optional: close submenus when collapsed
        if (sidebar.classList.contains('hide')) {
            document.querySelectorAll('#sidebar .submenu').forEach(sub => sub.classList.remove('active'));
        }
    });

    // NAVBAR SEARCH TOGGLE (Mobile)
    const searchButton = document.querySelector('#content nav form .form-input button');
    const searchButtonIcon = searchButton.querySelector('.bx');
    const searchForm = document.querySelector('#content nav form');

    searchButton.addEventListener('click', function (e) {
        if (window.innerWidth < 576) {
            e.preventDefault();
            searchForm.classList.toggle('show');
            if (searchForm.classList.contains('show')) {
                searchButtonIcon.classList.replace('bx-search', 'bx-x');
            } else {
                searchButtonIcon.classList.replace('bx-x', 'bx-search');
            }
        }
    });

    // DARK MODE TOGGLE
    const switchMode = document.getElementById('switch-mode');

    // Load saved mode
    if (localStorage.getItem('mode') === 'dark') {
        switchMode.checked = true;
        document.body.classList.add('dark');
    }

    switchMode.addEventListener('change', function () {
        document.body.classList.toggle('dark', this.checked);
        localStorage.setItem('mode', this.checked ? 'dark' : 'light');
    });
});
