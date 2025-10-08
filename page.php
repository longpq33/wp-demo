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
            <?php if (!is_front_page() && !is_home() && !is_page('home')): ?>
                <?php get_template_part('template/page-footer'); ?>
            <?php endif; ?>
    </main>
</body>

</html>


<script>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'vi',
            includedLanguages: 'vi,en,ja,ko',
            autoDisplay: false
        }, 'google_translate_element');
    }
</script>

<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" defer></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const select = document.getElementById("languageSelect");
        const flagImg = document.getElementById("selected-flag");

        function changeLanguage(lang) {
            const combo = document.querySelector("select.goog-te-combo");
            if (combo) {
                combo.value = lang;
                combo.dispatchEvent(new Event("change"));
            } else {
                console.warn("⚠️ Google Translate combo chưa sẵn sàng.");
            }
        }

        // 🔁 Kiểm tra liên tục cho đến khi combo sẵn sàng
        const comboInterval = setInterval(() => {
            const combo = document.querySelector("select.goog-te-combo");
            if (combo) {
                clearInterval(comboInterval);
                console.log("✅ Google Translate combo loaded"); // ← Log bạn cần

                // Lắng nghe khi user chọn ngôn ngữ
                select.addEventListener("change", function () {
                    const lang = this.value;
                    const flag = this.options[this.selectedIndex].getAttribute("data-flag");
                    flagImg.src = flag;
                    changeLanguage(lang);
                });
            }
        }, 1000);
    });
</script>