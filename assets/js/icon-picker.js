document.addEventListener('DOMContentLoaded', function() {
    let updateIcon = function (iconClasses, target) {
        target.innerHTML = '';
        iconClasses.forEach(function (iconClass) {
            target.innerHTML += '<i class="' + iconClass + '"></i>';
        });
    };

    document.querySelectorAll('[data-trigger=icon]').forEach(function (el) {
        let target = el.getAttribute('data-target');
        let targetEl = document.querySelector(target);
        updateIcon(el.value?.split(','), targetEl);

        el.addEventListener('keyup', function (event) {
            updateIcon(el.value?.split(','), targetEl);
        });
    });
});