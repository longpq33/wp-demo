<?php
/**
 * MSB Widgets LESS Compiler using lessphp library
 * Professional LESS compilation with full feature support
 */

require_once dirname(__DIR__) . '/lessc.inc.php';

class MSB_Widgets_LESS_Compiler_Pro {
    
    public $theme_dir;
    public $widgets_dir;
    public $css_dir;
    private $lessc;
    
    public function __construct() {
        // Use WordPress function if available, otherwise use parent directory
        $this->theme_dir = function_exists('get_template_directory') ? get_template_directory() : dirname(__DIR__);
        $this->widgets_dir = $this->theme_dir . '/widgets';
        $this->css_dir = $this->theme_dir . '/assets/css/widgets';
        
        // Create CSS directory if it doesn't exist
        if (!file_exists($this->css_dir)) {
            if (function_exists('wp_mkdir_p')) {
                wp_mkdir_p($this->css_dir);
            } else {
                mkdir($this->css_dir, 0755, true);
            }
        }
        
        // Initialize lessphp compiler
        $this->lessc = new lessc();
        
        // Configure compiler options (compressed output)
        $this->lessc->setFormatter('compressed');
        $this->lessc->setPreserveComments(false);
    }
    
    /**
     * Auto compile all .less files under widgets subfolders (e.g. widgets/<name>/*.less)
     */
    public function auto_compile_widgets() {
        $less_files = $this->list_all_less_files();
        $compiled = 0;
        foreach ($less_files as $entry) {
            $less_path = $entry['path'];
            $name = $entry['name']; // basename without .less
            $css_file = $this->css_dir . '/' . $name . '.css';
            if ($this->needs_compilation($less_path, $css_file)) {
                try {
                    $this->lessc->checkedCompile($less_path, $css_file);
                    $compiled++;
                } catch (Exception $e) {
                    error_log("LESS compilation failed for $less_path: " . $e->getMessage());
                    try {
                        $css_content = $this->lessc->compile(file_get_contents($less_path));
                        file_put_contents($css_file, $css_content);
                        $compiled++;
                    } catch (Exception $e2) {
                        error_log("Manual LESS compilation also failed for $less_path: " . $e2->getMessage());
                    }
                }
            }
        }
        return $compiled;
    }
    
    /**
     * List all .less files under widgets
     * @return array<int, array{name:string, path:string}>
     */
    private function list_all_less_files() {
        $results = array();
        foreach (glob($this->widgets_dir . '/*/*.less') as $file) {
            $name = basename($file, '.less');
            $results[] = array('name' => $name, 'path' => $file);
        }
        return $results;
    }
    
    /**
     * Compile LESS for specific widget using lessphp
     */
    public function compile_widget_less($name_or_widget) {
        // Support both old convention (<widget>/<widget>.less) and arbitrary .less basenames
        $candidate_paths = array(
            $this->widgets_dir . '/' . $name_or_widget . '/' . $name_or_widget . '.less',
        );
        // Also allow matching any .less with same basename anywhere under widgets
        foreach (glob($this->widgets_dir . '/*/' . $name_or_widget . '.less') as $g) {
            $candidate_paths[] = $g;
        }
        $less_file = null;
        foreach ($candidate_paths as $p) {
            if (file_exists($p)) { $less_file = $p; break; }
        }
        if ($less_file === null) { return false; }

        $name = basename($less_file, '.less');
        $css_file = $this->css_dir . '/' . $name . '.css';

        if (!$this->needs_compilation($less_file, $css_file)) {
            return true;
        }
        try {
            $this->lessc->checkedCompile($less_file, $css_file);
            return true;
        } catch (Exception $e) {
            error_log("LESS compilation failed for $less_file: " . $e->getMessage());
            try {
                $css_content = $this->lessc->compile(file_get_contents($less_file));
                file_put_contents($css_file, $css_content);
                return true;
            } catch (Exception $e2) {
                error_log("Manual LESS compilation also failed for $less_file: " . $e2->getMessage());
                return false;
            }
        }
    }
    
    /**
     * Check if compilation is needed
     */
    private function needs_compilation($less_file, $css_file) {
        if (!file_exists($css_file)) {
            return true;
        }
        
        $less_time = filemtime($less_file);
        $css_time = filemtime($css_file);
        
        return $less_time > $css_time;
    }
    
    /**
     * Compile LESS content directly
     */
    public function compile_less_content($less_content) {
        try {
            return $this->lessc->compile($less_content);
        } catch (Exception $e) {
            error_log("LESS compilation failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get CSS file path for widget
     */
    public function get_widget_css_path($widget_name) {
        return $this->css_dir . '/' . $widget_name . '.css';
    }
    
    /**
     * Get CSS URL for widget
     */
    public function get_widget_css_url($widget_name) {
        if (function_exists('get_template_directory_uri')) {
            return get_template_directory_uri() . '/assets/css/widgets/' . $widget_name . '.css';
        } else {
            return '/assets/css/widgets/' . $widget_name . '.css';
        }
    }
    
    /**
     * Check if widget has LESS file
     */
    public function widget_has_less($name) {
        if (file_exists($this->widgets_dir . '/' . $name . '/' . $name . '.less')) {
            return true;
        }
        return (bool) glob($this->widgets_dir . '/*/' . $name . '.less');
    }
    
    /**
     * Get all widgets with LESS files
     */
    public function get_widgets_with_less() {
        // Return basenames of all .less files (used as handles and css filenames)
        $names = array();
        foreach ($this->list_all_less_files() as $entry) {
            $names[] = $entry['name'];
        }
        return array_values(array_unique($names));
    }
    
    /**
     * Set compiler options
     */
    public function set_formatter($formatter) {
        $this->lessc->setFormatter($formatter);
    }
    
    /**
     * Set variables from PHP
     */
    public function set_variables($variables) {
        $this->lessc->setVariables($variables);
    }
    
    /**
     * Enable/disable comment preservation
     */
    public function set_preserve_comments($preserve) {
        $this->lessc->setPreserveComments($preserve);
    }
    
    /**
     * Get compiler version info
     */
    public function get_version() {
        return 'lessphp v0.5.0';
    }
}

// Initialize compiler
function msb_init_widgets_less_compiler() {
    return new MSB_Widgets_LESS_Compiler_Pro();
}

// Auto compile on theme load (only if WordPress is loaded)
if (function_exists('add_action')) {
    add_action('after_setup_theme', function() {
        $compiler = msb_init_widgets_less_compiler();
        $compiler->auto_compile_widgets();
    });

    // Auto compile on admin save
    add_action('save_post', function() {
        $compiler = msb_init_widgets_less_compiler();
        $compiler->auto_compile_widgets();
    });
}
