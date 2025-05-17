document.addEventListener("DOMContentLoaded", function () {
    // SIDEBAR ACTIVE LINK TOGGLE
    const sidebarLinks = document.querySelectorAll('#sidebar .side-menu.top li a');
    sidebarLinks.forEach(link => {
        const li = link.parentElement;
        link.addEventListener('click', () => {
            // Remove active class from all links
            sidebarLinks.forEach(l => l.parentElement.classList.remove('active'));
            // Add active class to clicked item
            li.classList.add('active');

            // Automatically close the sidebar when a link is clicked
            document.getElementById('sidebar').classList.add('hide');

            // Navigate to the link's href
            const target = link.getAttribute('href');
            if (target && target !== '#') {
                window.location.href = target;
            }
        });
    });

    // TOGGLE SIDEBAR SHOW/HIDE
    const sidebarToggleButton = document.querySelector('#content nav .bx.bx-menu');
    const sidebar = document.getElementById('sidebar');
    sidebarToggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('hide');
    });


    // Handle submenu toggle
    const profilingLink = document.getElementById('profiling-link');
    const profilingSubmenu = document.getElementById('profiling-submenu');

    profilingLink.addEventListener('click', function (e) {
        e.preventDefault();
        profilingSubmenu.classList.toggle('active');
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