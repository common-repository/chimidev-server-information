<?php
/*
Plugin Name: ChimiDev Server Information
Plugin URI: https://chimidev.com/wordpress-plugins/chimidev-server-information/
Description: ChimiDev Server Information plugin allows you to see more information about Wordpress and server where it installed.
Version: 0.1
Author: ChimiDev
Author URI: https://chimidev.com/
License: GPLv2 or later
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!class_exists('ChimiDevServerInformation')) {
    class ChimiDevServerInformation
    {
        private static $_instance = NULL;

        const CHIMIDEV_SERVER_INFORMATION_PLUGIN_FULLNAME = 'ChimiDev Server Information';
        const CHIMIDEV_SERVER_INFORMATION_SLUG = 'chimidev-server-information';
        const CHIMIDEV_SERVER_INFORMATION_PREFIX = 'chimidev_server_information_';

        public static function get_instance()
        {
            if (NULL === self::$_instance) {
                self::$_instance = new self();
            }
            return (self::$_instance);
        }

        public function __construct()
        {
            if (is_admin()) {
                add_action('admin_menu', [$this, 'admin_menu_page']);
            }
        }

        public function admin_menu_page()
        {
            $menu_slug_prefix = plugin_dir_path(__FILE__);

            add_menu_page(
                __('Server info', self::CHIMIDEV_SERVER_INFORMATION_SLUG), // page title
                __('Server info', self::CHIMIDEV_SERVER_INFORMATION_SLUG), // menu title
                'manage_options', // capability
                $menu_slug_prefix, // menu slug
                [$this, 'server_info_page'], // function
                'dashicons-laptop' // icon url
            );

            add_submenu_page(
                $menu_slug_prefix, // parent slug
                __('Server info', self::CHIMIDEV_SERVER_INFORMATION_SLUG), // page title
                __('Server info', self::CHIMIDEV_SERVER_INFORMATION_SLUG), // menu title
                'manage_options', // capability
                $menu_slug_prefix, // menu slug
                [$this, 'server_info_page'] // function
            );

            add_submenu_page(
                $menu_slug_prefix,
                __('PHP Info', self::CHIMIDEV_SERVER_INFORMATION_SLUG),
                __('PHP Info', self::CHIMIDEV_SERVER_INFORMATION_SLUG),
                'manage_options',
                $menu_slug_prefix.'/phpinfo',
                [$this, 'php_info_page']
            );

            add_submenu_page(
                $menu_slug_prefix,
                __('WP Info', self::CHIMIDEV_SERVER_INFORMATION_SLUG),
                __('WP Info', self::CHIMIDEV_SERVER_INFORMATION_SLUG),
                'manage_options',
                $menu_slug_prefix.'/wp_info',
                [$this, 'wp_info_page']
            );

            add_submenu_page(
                $menu_slug_prefix,
                __('WP Logs', self::CHIMIDEV_SERVER_INFORMATION_SLUG),
                __('WP Logs', self::CHIMIDEV_SERVER_INFORMATION_SLUG),
                'manage_options',
                $menu_slug_prefix.'/wp_logs',
                [$this, 'wp_logs_page']
            );

            add_submenu_page(
                $menu_slug_prefix,
                __('Directories & Files', self::CHIMIDEV_SERVER_INFORMATION_SLUG),
                __('Directories & Files', self::CHIMIDEV_SERVER_INFORMATION_SLUG),
                'manage_options',
                $menu_slug_prefix.'/dirs_and_files',
                [$this, 'dirs_and_files_page']
            );

            define('CHIMIDEV_SERVER_INFORMATION_DIR_SLUG_FOR_LISTER', plugin_basename($menu_slug_prefix).'/dirs_and_files');
        }

        public function server_info_page()
        {
            global $wpdb;

            ?>

            <style>
                .additional_info {
                    display: none;
                }

                #additional_info_switcher a {
                    margin-left: -5px;
                }

                #additional_info_switcher a.active {
                    border-color: #008EC2;
                    background: #00a0d2;
                    color: #fff;
                }
            </style>

            <script type="text/javascript">
                jQuery(function () {
                    jQuery("#show_additional_info").click(function ($) {
                        jQuery("#hide_additional_info").removeClass("active");
                        jQuery(this).addClass("active");
                        jQuery(".additional_info").show();
                        return false;
                    });

                    jQuery("#hide_additional_info").click(function ($) {
                        jQuery("#show_additional_info").removeClass("active");
                        jQuery(this).addClass("active");
                        jQuery(".additional_info").hide();
                        return false;
                    });
                });
            </script>

            <div class="wrap">
                <h1>Information</h1>

                <table class="form-table">
                    <tbody>
                    <tr>
                        <th scope="row">Additional information</th>
                        <td>
                            <fieldset id="additional_info_switcher">
                                <legend class="screen-reader-text"><span>Additional information</span></legend>
                                <a href="#" class="page-title-action" id="show_additional_info">On</a>
                                <a href="#" class="page-title-action active" id="hide_additional_info">Off</a>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Server information</th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>Server information</span></legend>
                                <p><strong>Server
                                        information:</strong> <?php echo esc_attr(function_exists('php_uname') ? php_uname('s') . ' ' . php_uname('v') . ' ' . php_uname('m') : ''); ?>
                                </p>
                                <p><strong>Server software
                                        version:</strong> <?php echo esc_attr($_SERVER['SERVER_SOFTWARE']); ?></p>
                                <p><strong>PHP version:</strong> <?php echo esc_attr(phpversion()); ?></p>
                                <p><strong>Memory limit:</strong> <?php echo esc_attr(ini_get('memory_limit')); ?></p>
                                <p><strong>Max execution
                                        time:</strong> <?php echo esc_attr(ini_get('max_execution_time')); ?></p>

                                <p><strong>Upload Max File
                                        size:</strong> <?php echo esc_attr(ini_get('upload_max_filesize')); ?></p>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Database information</th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>Database information</span></legend>
                                <p><strong>MySQL version:</strong> <?php echo esc_attr($wpdb->dbh->server_info); ?></p>
                                <p><strong>MySQL server:</strong> <?php echo esc_attr($wpdb->dbhost); ?></p>
                                <p><strong>MySQL name:</strong> <?php echo esc_attr($wpdb->dbname); ?></p>
                                <p><strong>MySQL user:</strong> <?php echo esc_attr($wpdb->dbuser); ?></p>
                                <p><strong>Tables prefix:</strong> <?php echo esc_attr($wpdb->prefix); ?></p>
                                <p class="additional_info">
                                    <strong>Charset:</strong> <?php echo esc_attr($wpdb->charset); ?></p>
                                <p class="additional_info">
                                    <strong>Collate:</strong> <?php echo esc_attr($wpdb->collate); ?></p>
                                <p><strong>MySQL engine:</strong> <?php echo esc_attr(""); ?></p>
                                <p><strong>MySQL driver:</strong> <?php echo esc_attr($this->get_db_driver()); ?></p>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Your information</th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>Your information</span></legend>
                                <p><strong>Web browser:</strong> <?php echo esc_attr($_SERVER['HTTP_USER_AGENT']); ?>
                                </p>
                            </fieldset>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <?php
        }

        public function php_info_page()
        {
            echo '<div class="wrap">';
            echo '<h1>' . get_admin_page_title() . '</h1>';

            ob_start();
            phpinfo();
            $php_info = ob_get_contents();
            ob_end_clean();

            $pinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms','$1', $php_info);

            wp_register_style(self::CHIMIDEV_SERVER_INFORMATION_SLUG.'-phpinfo', plugin_dir_url(__FILE__) . 'admin/css/phpinfo.css');
            wp_enqueue_style(self::CHIMIDEV_SERVER_INFORMATION_SLUG.'-phpinfo');

            echo '<div class="phpinfo">';
            echo $pinfo;
            echo '</div>';

            echo '</div>';
        }

        public function wp_info_page()
        {
            echo '<div class="wrap">';
            echo '<h1>' . get_admin_page_title() . '</h1>';

            $true_false_variables = [
                [
                    'name' => 'WP_DEBUG',
                    'variable' => WP_DEBUG
                ],
                [
                    'name' => 'WP_DEBUG_DISPLAY',
                    'variable' => WP_DEBUG_DISPLAY
                ],
                [
                    'name' => 'WP_DEBUG_LOG',
                    'variable' => WP_DEBUG_LOG
                ],
                [
                    'name' => 'WP_CACHE',
                    'variable' => WP_CACHE
                ],
                [
                    'name' => 'SCRIPT_DEBUG',
                    'variable' => SCRIPT_DEBUG
                ],
                [
                    'name' => 'MEDIA_TRASH',
                    'variable' => MEDIA_TRASH
                ],
                [
                    'name' => 'SHORTINIT',
                    'variable' => SHORTINIT
                ],
                [
                    'name' => 'WP_FEATURE_BETTER_PASSWORDS',
                    'variable' => WP_FEATURE_BETTER_PASSWORDS
                ],
                [
                    'name' => 'FORCE_SSL_ADMIN',
                    'variable' => FORCE_SSL_ADMIN
                ],
//                [
//                    'name' => 'FORCE_SSL_LOGIN',
//                    'variable' => FORCE_SSL_LOGIN
//                ]
            ];

            $string_variables = [
                [
                    'name' => 'WP_MEMORY_LIMIT',
                    'variable' => WP_MEMORY_LIMIT
                ],
                [
                    'name' => 'WP_MAX_MEMORY_LIMIT',
                    'variable' => WP_MAX_MEMORY_LIMIT
                ],
                [
                    'name' => 'WP_CONTENT_DIR',
                    'variable' => WP_CONTENT_DIR
                ],
                [
                    'name' => 'WP_CONTENT_URL',
                    'variable' => WP_CONTENT_URL
                ],
                [
                    'name' => 'WP_PLUGIN_DIR',
                    'variable' => WP_PLUGIN_DIR
                ],
                [
                    'name' => 'WP_PLUGIN_URL',
                    'variable' => WP_PLUGIN_URL
                ],
                [
                    'name' => 'PLUGINDIR',
                    'variable' => PLUGINDIR
                ],
                [
                    'name' => 'WPMU_PLUGIN_DIR',
                    'variable' => WPMU_PLUGIN_DIR
                ],
                [
                    'name' => 'WPMU_PLUGIN_URL',
                    'variable' => WPMU_PLUGIN_URL
                ],
                [
                    'name' => 'MUPLUGINDIR',
                    'variable' => MUPLUGINDIR
                ],
                [
                    'name' => 'COOKIEHASH',
                    'variable' => COOKIEHASH
                ],
                [
                    'name' => 'USER_COOKIE',
                    'variable' => USER_COOKIE
                ],
                [
                    'name' => 'COOKIEPATH',
                    'variable' => COOKIEPATH
                ],
                [
                    'name' => 'SITECOOKIEPATH',
                    'variable' => SITECOOKIEPATH
                ],
                [
                    'name' => 'ADMIN_COOKIE_PATH',
                    'variable' => ADMIN_COOKIE_PATH
                ],
                [
                    'name' => 'PLUGINS_COOKIE_PATH',
                    'variable' => PLUGINS_COOKIE_PATH
                ],
                [
                    'name' => 'COOKIE_DOMAIN',
                    'variable' => COOKIE_DOMAIN
                ],
                [
                    'name' => 'ADMIN_COOKIE_PATH',
                    'variable' => ADMIN_COOKIE_PATH
                ],
                [
                    'name' => 'TEMPLATEPATH',
                    'variable' => TEMPLATEPATH
                ],
                [
                    'name' => 'STYLESHEETPATH',
                    'variable' => STYLESHEETPATH
                ],
                [
                    'name' => 'WP_DEFAULT_THEME',
                    'variable' => WP_DEFAULT_THEME
                ]
            ];

            // Print WP variables
            echo '<h2>Wordpress variables</h2>';

            foreach ($true_false_variables as $variable) {
                echo '<p><strong>' . $variable['name'] . '</strong>: ';

                if (defined($variable['name']) && $variable['variable'] === true) {
                    echo 'true';
                } else {
                    echo 'false';
                }

                echo '</p>';
            }

            foreach ($string_variables as $variable) {
                echo '<p><strong>' . $variable['name'] . '</strong>: ';

                if (defined($variable['name'])) {
                    echo esc_attr($variable['variable']);
                }

                echo '<br>';
            }

            echo '</p>';
        }

        public function wp_logs_page()
        {
            echo '<div class="wrap">';
            echo '<h1>' . get_admin_page_title() . '</h1>';

            $variables = [
                [
                    'name' => 'WP_DEBUG',
                    'variable' => WP_DEBUG
                ],
                [
                    'name' => 'WP_DEBUG_LOG',
                    'variable' => WP_DEBUG_LOG
                ],
                [
                    'name' => 'WP_DEBUG_DISPLAY',
                    'variable' => WP_DEBUG_DISPLAY
                ],
            ];

            // Print debug variables
            echo '<h2>Wordpress debug variables</h2>';

            foreach ($variables as $variable) {

                echo '<p><strong>' . $variable['name'] . '</strong>: ';

                if (defined($variable['name']) && $variable['variable'] === true) {
                    echo 'true';
                } else {
                    echo 'false';
                }

                echo '</p>';
            }

            // Print wordpress debug log file
            echo '<h2>Wordpress debug log file</h2>';

            $wp_debug_log_file_path = get_home_path().'wp-content/debug.log';

            if (file_exists($wp_debug_log_file_path)) {
                $wp_debug_log_file = fopen($wp_debug_log_file_path, 'r');

                echo '<div class="card" style="max-width: none">';

                while (!feof($wp_debug_log_file)) {
                    echo esc_attr(fgets($wp_debug_log_file)) . "<br />";
                }

                fclose($wp_debug_log_file);

                echo '</div>';
            } else {
                echo "There is no Wordpress debug file by path $wp_debug_log_file_path";
            }

            echo '</div>';
        }

        public function dirs_and_files_page()
        {
            require_once 'includes/class.directory_lister.php';
            ChimiDevServerInformation_DirFiles::load();
        }

        public function feedback_page()
        {
            echo '<div class="wrap">';
            echo '<h1>' . get_admin_page_title() . '</h1>';

            echo '</div>';
        }

        public function donate_page()
        {
            echo '<div class="wrap">';
            echo '<h1>' . get_admin_page_title() . '</h1>';

            echo '</div>';
        }

        public function get_db_driver()
        {
            if (!defined('PHP_VERSION_ID')) {
                $version = explode('.', PHP_VERSION);
                define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
            }

            $class = 'MySQL';

            if (extension_loaded('mysql') && PHP_VERSION_ID < 50500)
                $class = 'MySQL';
            elseif (extension_loaded('mysqli') && (PHP_VERSION_ID < 50300 || extension_loaded('mysqlnd')))
                $class = 'DbMySQLi';
            elseif (PHP_VERSION_ID >= 50200 && extension_loaded('pdo_mysql'))
                $class = 'DbPDO';

            return $class;
        }
    }
}

ChimiDevServerInformation::get_instance();
