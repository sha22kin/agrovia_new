// Agroviea Dashboard Script - Frest Admin Integration

(function () {
  'use strict';

  // 1. Sidebar Collapsible (Expanded & Mini Icon-Only)
  function initSidebar() {
    var shell = document.querySelector('.av-shell');
    var toggleBtn = document.getElementById('sidebarToggle');

    if (!shell) return;

    // Restore saved state on desktop
    try {
      if (window.innerWidth >= 992 && localStorage.getItem('av_sidebar_collapsed') === '1') {
        shell.classList.add('sidebar-collapsed');
      }
    } catch (e) {}

    function toggleDesktopOrMobile(e) {
      if (e) e.preventDefault();
      if (window.innerWidth >= 992) {
        // Desktop: toggle expanded / mini icon-only
        shell.classList.toggle('sidebar-collapsed');
        var isCollapsed = shell.classList.contains('sidebar-collapsed');
        try {
          localStorage.setItem('av_sidebar_collapsed', isCollapsed ? '1' : '0');
        } catch (e) {}
      } else {
        // Mobile / Tablet: toggle offcanvas
        var offcanvasEl = document.getElementById('avSidebar');
        if (offcanvasEl && window.bootstrap && window.bootstrap.Offcanvas) {
          var bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
          bsOffcanvas.toggle();
        }
      }
    }

    if (toggleBtn) {
      toggleBtn.addEventListener('click', toggleDesktopOrMobile);
    }
  }

  // 2. Copy the public id (Buyer ID / Seller ID)
  function initCopyId() {
    document.querySelectorAll('[data-copy]').forEach(function (el) {
      el.addEventListener('click', function () {
        var text = el.getAttribute('data-copy');
        if (navigator.clipboard) { navigator.clipboard.writeText(text); }
        if (window.avToast) { avToast('success', 'ID ' + text + ' copied'); }
        el.classList.remove('fa-copy'); el.classList.add('fa-check');
        setTimeout(function () { el.classList.remove('fa-check'); el.classList.add('fa-copy'); }, 1200);
      });
    });
  }

  // 3. Shortcut: Ctrl + / or Cmd + / to focus global search
  function initSearchShortcut() {
    document.addEventListener('keydown', function (e) {
      if ((e.ctrlKey || e.metaKey) && e.key === '/') {
        var searchInput = document.querySelector('.av-search-wrap input');
        if (searchInput) {
          e.preventDefault();
          searchInput.focus();
          searchInput.select();
        }
      }
    });
  }

  // Initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initSidebar();
      initCopyId();
      initSearchShortcut();
    });
  } else {
    initSidebar();
    initCopyId();
    initSearchShortcut();
  }
})();
