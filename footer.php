    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> MSB. All rights reserved.</p>
            <nav class="footer-nav" aria-label="Footer">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                ));
                ?>
            </nav>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>

