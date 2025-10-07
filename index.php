<?php get_header(); ?>

<main id="primary" class="site-main container">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
        
        <nav class="pagination" role="navigation" aria-label="Posts">
            <div class="nav-links">
                <div class="nav-previous"><?php next_posts_link(__('Older posts', 'msb-app-theme')); ?></div>
                <div class="nav-next"><?php previous_posts_link(__('Newer posts', 'msb-app-theme')); ?></div>
            </div>
        </nav>
    <?php else : ?>
        <section class="no-results not-found">
            <h2><?php _e('Nothing Found', 'msb-app-theme'); ?></h2>
            <p><?php _e('It seems we can’t find what you’re looking for.', 'msb-app-theme'); ?></p>
            <?php get_search_form(); ?>
        </section>
    <?php endif; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>


