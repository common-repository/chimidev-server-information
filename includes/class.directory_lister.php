<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!class_exists('ChimiDevServerInformation_DirFiles')) {
    class ChimiDevServerInformation_DirFiles
    {

        public static function load() {
            if (!class_exists('DirectoryLister')) {
                require_once(plugin_dir_path(__FILE__) . 'directory_lister/DirectoryLister.php');
            }

            // Initialize the DirectoryLister object
            $lister = new DirectoryLister();

            // Restrict access to current directory
//            ini_set('open_basedir', getcwd());

            // Return file hash
            if (isset($_GET['chimidev_si_hash'])) {
                // Get file hash array and JSON encode it
                $hashes = $lister->getFileHash($_GET['chimidev_si_hash']);
                $data   = json_encode($hashes);

                die($data);
            }

            if (isset($_GET['chimidev_si_zip'])) {
                $dirArray = $lister->zipDirectory($_GET['chimidev_si_zip']);
            } else {
                // Initialize the directory array
                if (isset($_GET['chimidev_si_dir'])) {
                    $dirArray = $lister->listDirectory($_GET['chimidev_si_dir']);
                } else {
                    $dirArray = $lister->listDirectory(get_home_path());
                }

                // Define theme path
                if (!defined('THEMEPATH')) {
                    define('THEMEPATH', $lister->getThemePath());
                }

                // Set path to theme index
                $themeIndex = $lister->getThemePath(true) . '/index.php';

                // Initialize the theme
                if (file_exists($themeIndex)) {
                    include($themeIndex);
                } else {
                    die('ERROR: Failed to initialize theme');
                }
            }
        }

    }
}