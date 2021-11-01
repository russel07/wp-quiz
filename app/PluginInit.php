<?php
namespace Russel\WpQuiz;

use Russel\WpQuiz\PluginHooks;

class PluginInit {
    protected $loader;
    protected $name;
    protected $prefix;
    protected $version;
    protected $views;

    public function __construct() {
        $this->name = WpQUIZ_PLUGIN_NAME;
        $this->prefix = WpQUIZ_PLUGIN_PREFIX;
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
        $this->loader->add_action('admin_init', $this, 'wpquiz_enqueue_admin_style');
        $this->loader->add_action('admin_init', $this, 'wpquiz_enqueue_admin_scripts');
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

    public function wpquiz_enqueue_admin_style(){
        wp_enqueue_style($this->prefix.'admin_css', plugin_dir_url(WpQUIZ_PLUGIN_PUBLIC_PATH).'public/css/style.css');
    }

    public function wpquiz_enqueue_admin_scripts(){
        wp_register_script($this->prefix.'vue_app', plugin_dir_url(WpQUIZ_PLUGIN_PUBLIC_PATH).'public/js/app.js', array(), $this->version, true );

        wp_enqueue_script( $this->prefix.'vue_app' );
        wp_localize_script( $this->prefix.'vue_app', 'ajax_object', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce(NONCE),
            'plugin_assets' => plugin_dir_url(WpQUIZ_PLUGIN_PUBLIC_PATH).'public/'
        ));
    }

    public function init() {
        $this->loader->run();
    }
}