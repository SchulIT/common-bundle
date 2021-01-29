document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-role=menu]').forEach(function(container) {
        let menuContainerSelector = container.getAttribute('data-menu');
        let menuContainer = container.querySelector(menuContainerSelector);

        let submenuContainerSelector = container.getAttribute('data-submenu');
        let submenuContainer = container.querySelector(submenuContainerSelector);

        let closeAll = function() {
            // Remove all open-classes
            menuContainer.querySelectorAll('.nav-item.open').forEach(function(menu) {
                if(menu.classList.contains('open')) {
                    menu.classList.remove('open');
                }
            });

            // Do not show any submenu
            submenuContainer.querySelectorAll('[data-role=submenu]').forEach(function(submenu) {
                if(submenu.classList.contains('show')) {
                    submenu.classList.remove('show');
                    submenu.classList.add('hide');
                }
            });
        };

        let restoreActive = function() {
            let active = menuContainer.querySelector('.nav-item.active') || menuContainer.querySelector('.nav-item.current_ancestor');

            if(active === null) {
                return;
            }

            active.classList.add('open');
            showSubmenu(active);
        };

        let showSubmenu = function(element) {
            let linkElement = element.querySelector('a[data-menu]');

            if(linkElement === null) {
                return;
            }

            let targetSelector = linkElement.getAttribute('data-menu');
            let target = submenuContainer.querySelector(targetSelector);

            if(target === null) {
                return;
            }

            element.classList.add('open');
            target.classList.remove('hide');
            target.classList.add('show');
        };

        let openMenu = function(element) {
            closeAll();
            showSubmenu(element);
        };

        document.addEventListener('click', function(event) {
            let clickedElement = event.target;

            // Case 1: clicked into submenu
            let submenuContainer = clickedElement.closest(submenuContainerSelector);

            if(submenuContainer !== null) {
                // Clicked into the submenu container -> do nothing!
                return;
            }

            // Case 2: clicked into again -> close
            let menuContainer = clickedElement.closest('.nav-item');

            closeAll();
            restoreActive();
        });

        menuContainer.querySelectorAll('a[data-menu]').forEach(function(el) {
            el.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();

                openMenu(el.closest('.nav-item'));
            });
        });


    });

    let hideMenuElementsIfNotEnoughSpace = function() {
        document.querySelectorAll('[data-trigger=collapsible-menu]').forEach(function(element) {
            // Important: retrieve width at the beginning because after applying the hide class, the element has 0 width
            let children = Array.prototype.slice.call(element.children);
            let childrenWidth = 12; // epsilon > 0

            children.forEach(function(child) {
                if(child.offsetWidth > 0) {
                    if (child.hasAttribute('data-width') === false || child.getAttribute('data-width') == '0') {
                        let style = window.getComputedStyle ? getComputedStyle(child, null) : child.currentStyle;
                        let marginLeft = parseInt(style.marginLeft);
                        let marginRight = parseInt(style.marginRight);

                        if (child.classList.contains('ml-auto')) {
                            marginLeft = 0;
                        }

                        if (child.classList.contains('mr-auto')) {
                            marginRight = 0;
                        }

                        child.setAttribute('data-width', child.offsetWidth + marginLeft + marginRight);
                    }

                    childrenWidth += parseInt(child.getAttribute('data-width'));
                }
            });

            let containerWidth = element.parentNode.offsetWidth;

            element.setAttribute('data-parent-width', containerWidth);
            element.setAttribute('data-children-width', childrenWidth);

            if(childrenWidth > containerWidth) {
                element.querySelectorAll('.is-collapsible').forEach(function(el) {
                    el.classList.add('collapse');
                });
            } else {
                element.querySelectorAll('.is-collapsible').forEach(function(el) {
                    el.classList.remove('collapse');
                });
            }
        });
    };

    window.addEventListener('resize', function(event) {
        hideMenuElementsIfNotEnoughSpace();
    });

    hideMenuElementsIfNotEnoughSpace();
});