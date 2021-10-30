<?php
namespace Russel\WpQuiz;

use Russel\WpQuiz\PluginHooks;

class PluginInit {
    protected $loader;
    protected $name;
    protected $version;
    protected $views;

    public function __construct() {
        $this->name = 'WpQUIZ';
        $this->version = '1.0.0';
        $this->loader = new PluginHooks();
        $this->activateMe();
        $this->deactivateMe();
        $this->define_admin_hooks();
    }

    public function activateMe() {
        register_activation_hook(WpQUIZ_PLUGIN_PUBLIC_PATH, array($this, 'wpquiz_install_required_table'));
    }

    public function wpquiz_install_required_table() {
        global $wpdb;

        $topic_table = $wpdb->prefix . WpQUIZ_topics_table;

        $topic_table_sql ="CREATE TABLE  IF NOT EXISTS " . $topic_table . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        subject_id INT(20) NOT NULL,
        topic_title VARCHAR(100) NOT NULL,
        level_number int(11) NOT NULL,
        statue ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
        PRIMARY KEY  (id)
        );";

        require_once(ABSPATH.'wp-admin/includes/upgrade.php');

        dbDelta($topic_table_sql);//Create wp_wpquiz_topics table
    }

    public function deactivateMe() {
        register_deactivation_hook(WpQUIZ_PLUGIN_PUBLIC_PATH, array($this, 'wpquiz_deactivate_wpquiz'));
    }

    public function wpquiz_deactivate_wpquiz() {
        global $wpdb;
        $topic_table = $wpdb->prefix . WpQUIZ_topics_table;
        $sqlTopic = "DROP TABLE IF EXISTS $topic_table";

        $wpdb->query($sqlTopic);
    }

    public function load_view(){
        $current_view = $this->views[current_filter()];

        include_once(dirname(WpQUIZ_PLUGIN_PUBLIC_PATH).'/admin/view/'.$current_view.'.php');
    }

    public function define_admin_hooks(){
        $this->loader->add_action('admin_menu', $this, 'wpquiz_admin_menu');
    }

    public function wpquiz_admin_menu(){
        $admin_menu_hook = add_menu_page(
            'Wp QUIZ',
            'Wp QUIZ',
            'manage_options',
            'manage-quiz',
            array(&$this, 'load_view')
        );

        $this->views[$admin_menu_hook] = 'admin';
    }

    public function init() {
        $this->loader->run();
    }
}