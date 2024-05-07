// Hamburger toggler
const hamburger = document.querySelector(".sidebar-toggler");
const sidebar = document.querySelector(".sidebar");
const content = document.querySelector(".content");

hamburger.addEventListener('click', (e)=> {
    e.preventDefault();
    sidebar.classList.toggle('open');
    content.classList.toggle('open');
});