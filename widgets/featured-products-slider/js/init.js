/**
 * Featured Products Slider JavaScript
 * Khởi tạo và điều khiển slider sản phẩm nổi bật
 */

(function($) {
    'use strict';

    // Featured Products Slider Class
    class FeaturedProductsSlider {
        constructor(element) {
            this.slider = $(element);
            this.wrapper = this.slider.find('.msb-slider-wrapper');
            this.slides = this.slider.find('.msb-slide');
            this.prevBtn = this.slider.find('.msb-slider-prev');
            this.nextBtn = this.slider.find('.msb-slider-next');
            this.dotsContainer = this.slider.find('.msb-slider-dots');
            
            // Settings
            this.autoplay = this.slider.data('autoplay') === true;
            this.autoplaySpeed = parseInt(this.slider.data('autoplay-speed')) || 3000;
            this.currentSlide = 0;
            this.totalSlides = this.slides.length;
            this.slidesPerView = this.getSlidesPerView();
            this.autoplayInterval = null;
            
            // Initialize
            this.init();
        }

        getSlidesPerView() {
            const width = $(window).width();
            if (width >= 1200) return 4;
            if (width >= 1024) return 3;
            if (width >= 768) return 2;
            return 1;
        }

        init() {
            if (this.totalSlides === 0) return;
            
            this.createDots();
            this.bindEvents();
            this.updateSlider();
            this.startAutoplay();
        }

        createDots() {
            if (this.totalSlides <= this.slidesPerView) return;
            
            const totalDots = Math.ceil(this.totalSlides / this.slidesPerView);
            this.dotsContainer.empty();
            
            for (let i = 0; i < totalDots; i++) {
                const dot = $('<span class="msb-slider-dot"></span>');
                if (i === 0) dot.addClass('active');
                this.dotsContainer.append(dot);
            }
        }

        bindEvents() {
            // Navigation buttons
            this.prevBtn.on('click', () => this.prevSlide());
            this.nextBtn.on('click', () => this.nextSlide());
            
            // Dots navigation
            this.dotsContainer.on('click', '.msb-slider-dot', (e) => {
                const dotIndex = $(e.target).index();
                this.goToSlide(dotIndex);
            });
            
            // Touch/swipe support
            this.addTouchSupport();
            
            // Pause autoplay on hover
            this.slider.on('mouseenter', () => this.stopAutoplay());
            this.slider.on('mouseleave', () => this.startAutoplay());
            
            // Window resize
            $(window).on('resize', () => this.handleResize());
        }

        addTouchSupport() {
            let startX = 0;
            let startY = 0;
            let endX = 0;
            let endY = 0;
            let isDragging = false;

            this.slider.on('touchstart mousedown', (e) => {
                isDragging = true;
                startX = e.type === 'touchstart' ? e.originalEvent.touches[0].clientX : e.clientX;
                startY = e.type === 'touchstart' ? e.originalEvent.touches[0].clientY : e.clientY;
                this.stopAutoplay();
            });

            this.slider.on('touchmove mousemove', (e) => {
                if (!isDragging) return;
                e.preventDefault();
            });

            this.slider.on('touchend mouseup', (e) => {
                if (!isDragging) return;
                isDragging = false;
                
                endX = e.type === 'touchend' ? e.originalEvent.changedTouches[0].clientX : e.clientX;
                endY = e.type === 'touchend' ? e.originalEvent.changedTouches[0].clientY : e.clientY;
                
                const diffX = startX - endX;
                const diffY = startY - endY;
                
                // Only trigger if horizontal swipe is more significant than vertical
                if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                    if (diffX > 0) {
                        this.nextSlide();
                    } else {
                        this.prevSlide();
                    }
                }
                
                this.startAutoplay();
            });
        }

        handleResize() {
            const newSlidesPerView = this.getSlidesPerView();
            if (newSlidesPerView !== this.slidesPerView) {
                this.slidesPerView = newSlidesPerView;
                this.currentSlide = 0;
                this.createDots();
                this.updateSlider();
            }
        }

        prevSlide() {
            if (this.currentSlide > 0) {
                this.currentSlide--;
            } else {
                this.currentSlide = Math.ceil(this.totalSlides / this.slidesPerView) - 1;
            }
            this.updateSlider();
        }

        nextSlide() {
            const maxSlide = Math.ceil(this.totalSlides / this.slidesPerView) - 1;
            if (this.currentSlide < maxSlide) {
                this.currentSlide++;
            } else {
                this.currentSlide = 0;
            }
            this.updateSlider();
        }

        goToSlide(slideIndex) {
            this.currentSlide = slideIndex;
            this.updateSlider();
        }

        updateSlider() {
            const translateX = -(this.currentSlide * 100);
            this.wrapper.css('transform', `translateX(${translateX}%)`);
            
            // Update navigation buttons
            const maxSlide = Math.ceil(this.totalSlides / this.slidesPerView) - 1;
            this.prevBtn.prop('disabled', this.currentSlide === 0);
            this.nextBtn.prop('disabled', this.currentSlide === maxSlide);
            
            // Update dots
            this.dotsContainer.find('.msb-slider-dot').removeClass('active');
            this.dotsContainer.find('.msb-slider-dot').eq(this.currentSlide).addClass('active');
        }

        startAutoplay() {
            if (!this.autoplay || this.totalSlides <= this.slidesPerView) return;
            
            this.stopAutoplay();
            this.autoplayInterval = setInterval(() => {
                this.nextSlide();
            }, this.autoplaySpeed);
        }

        stopAutoplay() {
            if (this.autoplayInterval) {
                clearInterval(this.autoplayInterval);
                this.autoplayInterval = null;
            }
        }

        destroy() {
            this.stopAutoplay();
            this.slider.off();
            $(window).off('resize');
        }
    }

    // Initialize all sliders when DOM is ready
    $(document).ready(function() {
        $('.msb-featured-products-slider').each(function() {
            new FeaturedProductsSlider(this);
        });
    });

    // Re-initialize sliders after AJAX content load
    $(document).on('DOMNodeInserted', function(e) {
        if ($(e.target).find('.msb-featured-products-slider').length > 0) {
            $(e.target).find('.msb-featured-products-slider').each(function() {
                if (!$(this).data('slider-initialized')) {
                    new FeaturedProductsSlider(this);
                    $(this).data('slider-initialized', true);
                }
            });
        }
    });

})(jQuery);