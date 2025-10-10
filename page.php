<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
  
    <main id="primary" class="site-main container">
        <?php if (have_posts()): ?>
            <?php while (have_posts()):
                the_post(); ?>
                <div id="post-<?php the_ID(); ?>">
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
            <?php if (!is_front_page() && !is_home() && !is_page('home-1')): ?>
                <?php get_template_part('template/page-footer'); ?>
            <?php endif; ?>
    </main>
</body>

</html>