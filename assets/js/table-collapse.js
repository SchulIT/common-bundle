let arrowDown = 'fa-chevron-down';
let arrowUp = 'fa-chevron-up';

let collapse = function(el) {
    changeState(el, true);
};

let show = function(el) {
    changeState(el, false);
};

let isCollapsed = function(el) {
    let targetSelector = el.getAttribute('data-target');
    let targets = document.querySelectorAll(targetSelector);

    let indicator = el.querySelector('.indicator');

    if(indicator === null) {
        let collapsed = true;

        targets.forEach(function(target) {
            if(target.classList.contains('collapse') !== true) {
                collapsed = false;
            }
        });
    } else {
        return indicator.classList.contains(arrowDown);
    }
};

let changeState = function(el, collapsed) {
    let targetSelector = el.getAttribute('data-target');
    let targets = document.querySelectorAll(targetSelector);

    let indicator = el.querySelector('.indicator');

    el.setAttribute('data-is-collapsed', collapsed);

    if(indicator === null) {
        targets.forEach(function (target) {
            if (collapsed === false) {
                target.classList.remove('collapse');
            } else {
                target.classList.add('collapse');
            }
        });
    } else {
        if(collapsed === false) { // show
            indicator.classList.remove(arrowDown);
            indicator.classList.add(arrowUp);
            targets.forEach(function(target) {
                target.classList.remove('collapse');
            });
        } else { // hide
            indicator.classList.remove(arrowUp);
            indicator.classList.add(arrowDown);
            targets.forEach(function(target) {
                target.classList.add('collapse');
            });
        }
    }
};

let toggle = function(el) {
    changeState(el, !isCollapsed(el));
};

document.querySelectorAll('[data-toggle=table-collapse]').forEach(function(el) {
    el.addEventListener('click', function(event) {
        event.preventDefault();
        toggle(el);
    });
});

document.querySelectorAll('[data-toggle=table-collapse-all]').forEach(function(el) {
    el.addEventListener('click', function(event) {
        event.preventDefault();

        let containerSelector = el.getAttribute('data-container');
        let container = document.querySelectorAll(containerSelector);

        container.forEach(function(containerEl) {
            containerEl.querySelectorAll('[data-toggle=table-collapse]').forEach(function(collapseEl) {
                collapse(collapseEl);
            });
        });
    });
});

document.querySelectorAll('[data-toggle=table-show-all]').forEach(function(el) {
    el.addEventListener('click', function(event) {
        event.preventDefault();

        let containerSelector = el.getAttribute('data-container');
        let container = document.querySelectorAll(containerSelector);

        container.forEach(function(containerEl) {
            containerEl.querySelectorAll('[data-toggle=table-collapse]').forEach(function(collapseEl) {
                show(collapseEl);
            });
        });
    });
});