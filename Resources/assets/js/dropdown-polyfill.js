document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.dropdown-toggle').forEach(function(el) {
        el.parentNode.addEventListener('show.bs.dropdown', function() {
            let tableResponsiveEl = el.closest('.table-responsive');

            if(tableResponsiveEl !== null) {
                tableResponsiveEl.style.overflow = 'inherit';
            }
        });

        el.parentNode.addEventListener('hide.bs.dropdown', function() {
            let tableResponsiveEl = el.closest('.table-responsive');

            if(tableResponsiveEl !== null) {
                tableResponsiveEl.style.overflow = 'auto';
            }
        });
    });
});