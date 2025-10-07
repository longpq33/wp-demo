<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <!-- <header class="site-header">
        <div class="container">
            <div class="branding">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <?php bloginfo('name'); ?>
                </a>
                <p class="site-description"><?php bloginfo('description'); ?></p>
            </div>

            <nav class="primary-nav" aria-label="Primary">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                ));
                ?>
            </nav>
        </div>
    </header> -->


<main id="primary" class="site-main container">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <div id="post-<?php the_ID(); ?>">
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
          </div>
        <?php endwhile; ?>
    <?php endif; ?>
</main>
</body>
</html>




