// GREETING based on current time
function setGreeting() {
    const hour = new Date().getHours();
    let greeting = '';

    if (hour >= 4 && hour < 11) {
        greeting = 'Halo, Selamat Pagi ☀️';
    } else if (hour >= 11 && hour < 15) {
        greeting = 'Halo, Selamat Siang 🌤️';
    } else if (hour >= 15 && hour < 19) {
        greeting = 'Halo, Selamat Sore 🌅';
    } else {
        greeting = 'Halo, Selamat Malam 🌙';
    }

    const greetEl = document.getElementById('greeting');
    if (greetEl) greetEl.textContent = greeting;
}

// TABLE SEARCH
function searchTable(tableId, keyword) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr');
    const kw = keyword.toLowerCase().trim();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(kw) ? '' : 'none';
    });
}

// TABLE FILTER BY STATUS
function filterTableByStatus(tableId, colIndex, value) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr');
    const val = value.toLowerCase().trim();

    rows.forEach(row => {
        if (!val) {
            row.style.display = '';
            return;
        }
        const cell = row.cells[colIndex];
        if (!cell) return;
        const text = cell.textContent.toLowerCase().trim();
        row.style.display = text.includes(val) ? '' : 'none';
    });
}

// NAVBAR TOGGLE
function initNavToggle() {
    const toggle = document.getElementById('navToggle');
    const links  = document.getElementById('navLinks');
    if (!toggle || !links) return;

    toggle.addEventListener('click', () => {
        const isOpen = links.classList.toggle('open');
        toggle.setAttribute('aria-expanded', isOpen);

        // Animate hamburger
        const spans = toggle.querySelectorAll('span');
        if (isOpen) {
            spans[0].style.transform = 'translateY(7px) rotate(45deg)';
            spans[1].style.opacity   = '0';
            spans[2].style.transform = 'translateY(-7px) rotate(-45deg)';
        } else {
            spans[0].style.transform = '';
            spans[1].style.opacity   = '';
            spans[2].style.transform = '';
        }
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (!toggle.contains(e.target) && !links.contains(e.target)) {
            links.classList.remove('open');
            const spans = toggle.querySelectorAll('span');
            spans[0].style.transform = '';
            spans[1].style.opacity   = '';
            spans[2].style.transform = '';
        }
    });
}

// LOGIN FORM — Button loading state
function initLoginForm() {
    const form   = document.getElementById('loginForm');
    const btn    = document.getElementById('btnLogin');
    const input  = document.getElementById('username');
    if (!form || !btn) return;

    form.addEventListener('submit', (e) => {
        const val = input ? input.value.trim() : '';
        if (!val) {
            e.preventDefault();
            input.style.borderColor = 'var(--rose-deep)';
            input.focus();
            return;
        }
        btn.innerHTML = '<span>Masuk...</span>';
        btn.disabled = true;
    });

    if (input) {
        input.addEventListener('input', () => {
            input.closest('.input-wrapper').style.borderColor = '';
        });
    }
}

// STAT CARDS — Animate numbers on load
function animateNumbers() {
    const els = document.querySelectorAll('.stat-value');
    els.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(8px)';
        el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
    });

    // Stagger reveal
    setTimeout(() => {
        els.forEach((el, i) => {
            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, i * 80);
        });
    }, 150);
}

// TABLE ROWS — Fade in
function animateTableRows() {
    const rows = document.querySelectorAll('.data-table tbody tr');
    rows.forEach((row, i) => {
        row.style.opacity = '0';
        row.style.transition = `opacity 0.3s ease ${i * 40}ms`;
        setTimeout(() => { row.style.opacity = '1'; }, 100 + i * 40);
    });
}

// INIT — Run on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    setGreeting();
    initNavToggle();
    initLoginForm();
    animateNumbers();
    animateTableRows();
});

// LOGIN FORM
document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");
    const username = document.getElementById("username");
    const usernameError = document.getElementById("usernameError");

    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            let valid = true;

            usernameError.textContent = "";
            username.classList.remove("input-error");

            if (username.value.trim() === "") {
                e.preventDefault();
                valid = false;

                usernameError.textContent = "Username wajib diisi!";
                username.classList.add("input-error");
            }

            if (!valid) {
                username.focus();
            }
        });
    }
});
