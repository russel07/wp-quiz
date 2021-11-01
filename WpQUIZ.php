<?php
/**
 * Plugin Name
 *
 * @package WpQUIZ
 * @author Md. Russel Hussain
 * @copyright 2021 rus.org
 * @license GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: WP QUIZ
 * Plugin URI: https://russel.authlab/plugins
 * Description: An awesome plugin for manage quiz with topics
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Md. Russel Hussain
 * Author URI: https://russel.authlab
 * Author URI: https://author.example.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI: https://example.com/my-plugin/
 * Text Domain: my-basics-plugin
 * Domain Path: /languages
 */

require_once 'vendor/autoload.php';

const WpQUIZ_PLUGIN_NAME = 'WpQUIZ';
const WpQUIZ_PLUGIN_VERSION = '1.0.1';
const WpQUIZ_PLUGIN_PREFIX = 'wpquiz_';
const WpQUIZ_PLUGIN_PUBLIC_PATH = __FILE__;

const NONCE = 'WpQUIZ3298';
const WpQUIZ_topics_table = 'wpquiz_topics';
const WpQUIZ_topic_level_table = 'topics_level';

use Russel\WpQuiz\PluginInit;

class WpQUIZ {
    protected $plugin;

    public function __construct(){
        $this->plugin = new PluginInit();
        $this->plugin->init();
    }
}

new WpQUIZ();