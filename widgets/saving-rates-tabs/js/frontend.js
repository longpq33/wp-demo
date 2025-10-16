(function($) {
    'use strict';

    function initSavingRatesTabs() {
        $('.msb-saving-rates-tabs').each(function() {
            var $container = $(this);
            var $nav = $container.find('.msb-srt-nav');
            var $buttons = $nav.find('.msb-srt-tab-btn');
            var $panels = $container.find('.msb-srt-panel');

            function activateTab(tabId) {
                // Update buttons
                $buttons.each(function() {
                    var $btn = $(this);
                    var isActive = $btn.data('tab') === tabId;
                    $btn.toggleClass('is-active', isActive);
                    $btn.attr('aria-selected', isActive);
                });

                // Update panels
                $panels.each(function() {
                    var $panel = $(this);
                    var isActive = $panel.attr('id') === 'panel-' + tabId;
                    $panel.toggleClass('is-active', isActive);
                    if (isActive) {
                        $panel.removeAttr('hidden');
                    } else {
                        $panel.attr('hidden', '');
                    }
                });

                // Update URL hash
                if (history.replaceState) {
                    history.replaceState(null, '', '#tab-' + tabId);
                } else {
                    location.hash = 'tab-' + tabId;
                }
            }

            // Handle tab clicks
            $buttons.on('click', function(e) {
                e.preventDefault();
                var tabId = $(this).data('tab');
                activateTab(tabId);
            });

            // Handle keyboard navigation
            $buttons.on('keydown', function(e) {
                var $current = $(this);
                var $buttonsArray = $buttons.toArray();
                var currentIndex = $buttonsArray.indexOf(this);
                var $target;

                switch(e.which) {
                    case 37: // Left arrow
                        e.preventDefault();
                        $target = $buttonsArray[currentIndex - 1] || $buttonsArray[$buttonsArray.length - 1];
                        $($target).focus().click();
                        break;
                    case 39: // Right arrow
                        e.preventDefault();
                        $target = $buttonsArray[currentIndex + 1] || $buttonsArray[0];
                        $($target).focus().click();
                        break;
                    case 36: // Home
                        e.preventDefault();
                        $($buttonsArray[0]).focus().click();
                        break;
                    case 35: // End
                        e.preventDefault();
                        $($buttonsArray[$buttonsArray.length - 1]).focus().click();
                        break;
                }
            });

            // Initialize from URL hash, fallback to first tab
            var hash = location.hash || '';
            var match = hash.match(/#tab-([a-z]+)/);
            if (match) {
                activateTab(match[1]);
            } else {
                // Ensure first tab is active by default
                activateTab('counter');
            }
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        initSavingRatesTabs();
    });

    // Re-initialize if content is loaded dynamically
    $(document).on('DOMNodeInserted', function(e) {
        if ($(e.target).hasClass('msb-saving-rates-tabs') || $(e.target).find('.msb-saving-rates-tabs').length) {
            initSavingRatesTabs();
        }
    });

})(jQuery);