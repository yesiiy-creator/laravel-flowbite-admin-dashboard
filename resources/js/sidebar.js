const sidebar = document.getElementById('sidebar');
const backdrop = document.getElementById('sidebarBackdrop');
const toggle = document.getElementById('toggleSidebarMobile');

if (sidebar && backdrop && toggle) {
    const toggleSidebar = () => {
        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    };
    toggle.addEventListener('click', toggleSidebar);
    backdrop.addEventListener('click', toggleSidebar);
}
