<?php

if (!defined('ABSPATH')) {
    exit;
}

class MSB_WP_Multi_Language_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'msb_multi-language',
            __('MultiLanguage', 'msb-app-theme'),
            [
                'description' => __('Multi language (MSB).', 'msb-app-theme'),
                'panels_groups' => array('msb'),
                'panels_title' => __('MultiLanguage', 'msb-app-theme'),
            ]
        );
        add_action('wp_footer', array($this, 'add_google_translate_script'));
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget']; ?>
        <div id="custom-language-switcher" class="language-switcher">
            <div class="lang-select-wrapper">
                <img id="selected-flag" src="https://flagcdn.com/24x18/vn.png" alt="flag">
                <select id="languageSelect">
                    <option value="vi" data-flag="https://flagcdn.com/24x18/vn.png">Tiếng Việt</option>
                    <option value="en" data-flag="https://flagcdn.com/24x18/gb.png">English</option>
                </select>
            </div>
        </div>

        <div id="google_translate_element" style="visibility:hidden; position:absolute; left:-9999px;"></div>

        <?php echo $args['after_widget'];
    }

    public function add_google_translate_script()
    { ?>
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
    <?php }


    public function form($instance)
    {
        echo '<p>No settings required.</p>';
    }

    public function update($new_instance, $old_instance)
    {
        return $old_instance;
    }
}

// Đăng ký widget
function register_custom_multi_language_widget()
{
    register_widget('MSB_WP_Multi_Language_Widget');
}
add_action('widgets_init', 'register_custom_multi_language_widget');

function load_custom_language_widget_style()
{
    wp_enqueue_style(
        'custom-language-widget-style',
        get_stylesheet_directory_uri() . '/widgets/multi-language/css/multi-language.css',
        [],
        filemtime(get_stylesheet_directory() . '/widgets/multi-language/css/multi-language.css')
    );
}
add_action('wp_enqueue_scripts', 'load_custom_language_widget_style');

