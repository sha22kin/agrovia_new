// Copy the public id (Buyer ID / Seller ID) from the banner.
document.querySelectorAll('[data-copy]').forEach(function (el) {
  el.addEventListener('click', function () {
    var text = el.getAttribute('data-copy');
    if (navigator.clipboard) { navigator.clipboard.writeText(text); }
    if (window.avToast) { avToast('success', 'ID ' + text + ' copied'); }
    el.classList.remove('fa-copy'); el.classList.add('fa-check');
    setTimeout(function () { el.classList.remove('fa-check'); el.classList.add('fa-copy'); }, 1200);
  });
});
