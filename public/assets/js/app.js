// ── Hamburger ──
const toggle = document.getElementById("menu-toggle");
const menu = document.getElementById("mobile-menu");
const iconOpen = document.getElementById("icon-open");
const iconClose = document.getElementById("icon-close");
toggle.addEventListener("click", () => {
    menu.classList.toggle("hidden");
    iconOpen.classList.toggle("hidden");
    iconClose.classList.toggle("hidden");
});

// ── Theme Toggle ──
const html = document.getElementById("html-root");
const themeBtn = document.getElementById("theme-toggle");
const themeBtnMob = document.getElementById("theme-toggle-mobile");
const themeIcon = document.getElementById("theme-icon");
const themeIconMob = document.getElementById("theme-icon-mobile");

// Load saved preference
const savedTheme = localStorage.getItem("theme") || "dark";
if (savedTheme === "light") {
    html.classList.add("light");
    themeIcon.textContent = "☀️";
    themeIconMob.textContent = "☀️";
}

function toggleTheme() {
    const isLight = html.classList.toggle("light");
    const icon = isLight ? "☀️" : "🌙";
    themeIcon.textContent = icon;
    themeIconMob.textContent = icon;
    localStorage.setItem("theme", isLight ? "light" : "dark");
}

themeBtn.addEventListener("click", toggleTheme);
themeBtnMob.addEventListener("click", toggleTheme);
