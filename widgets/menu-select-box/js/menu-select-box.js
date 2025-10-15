/**
 * Menu Select Box JavaScript
 */

(function($) {
    'use strict';

    // Menu Select Box Class
    class MenuSelectBox {
        constructor(element) {
            this.container = $(element);
            this.trigger = this.container.find('.msb-dropdown-trigger');
            this.input = this.container.find('.msb-dropdown-input');
            this.overlay = this.container.find('.msb-modal-overlay');
            this.modal = this.container.find('.msb-modal');
            this.closeBtn = this.container.find('.msb-modal-close');
            this.menuItems = this.container.find('.msb-menu-item');
            
            this.isOpen = false;
            this.init();
        }

        init() {
            console.log('MenuSelectBox initialized'); // Debug
            this.bindEvents();
        }

        bindEvents() {
            // Toggle modal on input click
            this.trigger.on('click', (e) => {
                e.preventDefault();
                console.log('Trigger clicked!'); // Debug
                this.toggleModal();
            });

            // Close modal on close button
            this.closeBtn.on('click', (e) => {
                e.preventDefault();
                this.closeModal();
            });

            // Close modal on overlay click
            this.overlay.on('click', (e) => {
                if (e.target === this.overlay[0]) {
                    this.closeModal();
                }
            });

            // Close modal on ESC key
            $(document).on('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.closeModal();
                }
            });

            // Handle menu item clicks (delegated to support any future dynamic items)
            this.container.on('click', '.msb-menu-item', (e) => {
                e.preventDefault();
                const item = $(e.currentTarget);
                const text = item.find('.msb-menu-text').text();
                const url = item.attr('href');

                // Update input and close modal
                this.input.val(text);
                this.closeModal();

                // Navigate after brief delay to allow close animation
                setTimeout(() => {
                    if (url && url !== '#') {
                        window.location.href = url;
                    }
                }, 150);
            });

            // Prevent modal from closing when clicking inside modal
            this.modal.on('click', (e) => {
                e.stopPropagation();
            });
        }

        toggleModal() {
            if (this.isOpen) {
                this.closeModal();
            } else {
                this.openModal();
            }
        }

        openModal() {
            console.log('Opening modal...'); // Debug
            this.isOpen = true;
            this.overlay.addClass('active');
            this.input.addClass('active');
            
            // Prevent body scroll
            $('body').addClass('msb-modal-open');
            
            // Focus management
            this.closeBtn.focus();
            
            // Add animation class
            this.modal.addClass('msb-modal-open');
        }

        closeModal() {
            this.isOpen = false;
            this.overlay.removeClass('active');
            this.input.removeClass('active');
            
            // Restore body scroll
            $('body').removeClass('msb-modal-open');
            
            // Remove animation class
            this.modal.removeClass('msb-modal-open');
            
            // Ensure overlay hidden if any inline styles present
            this.overlay.css({
                'display': '',
                'opacity': '',
                'visibility': ''
            });
        }

        destroy() {
            this.trigger.off();
            this.closeBtn.off();
            this.overlay.off();
            this.menuItems.off();
            $(document).off('keydown');
        }
    }

    // Initialize all menu select boxes when DOM is ready
    $(document).ready(function() {
        $('.msb-menu-select-box').each(function() {
            if (!$(this).data('menu-select-initialized')) {
                new MenuSelectBox(this);
                $(this).data('menu-select-initialized', true);
            }
        });
    });

    // Re-initialize after AJAX content load
    $(document).on('DOMNodeInserted', function(e) {
        if ($(e.target).find('.msb-menu-select-box').length > 0) {
            $(e.target).find('.msb-menu-select-box').each(function() {
                if (!$(this).data('menu-select-initialized')) {
                    new MenuSelectBox(this);
                    $(this).data('menu-select-initialized', true);
                }
            });
        }
    });

    // Handle window resize
    $(window).on('resize', function() {
        $('.msb-menu-select-box').each(function() {
            const menuBox = $(this).data('menu-select-box');
            if (menuBox && menuBox.isOpen) {
                // Re-center modal on resize
                menuBox.modal.css({
                    'top': '50%',
                    'left': '50%',
                    'transform': 'translate(-50%, -50%)'
                });
            }
        });
    });

})(jQuery);