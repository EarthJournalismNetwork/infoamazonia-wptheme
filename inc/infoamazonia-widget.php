<?php

/*
 * MapPress embed tool
 */

class InfoAmazonia_Widget {

	var $query_var = 'share';
	var $slug = 'share';

	function __construct() {
		add_filter('query_vars', array(&$this, 'query_var'));
		add_action('generate_rewrite_rules', array(&$this, 'generate_rewrite_rule'));
		add_action('template_redirect', array(&$this, 'template_redirect'));
	}

	function query_var($vars) {
		$vars[] = $this->query_var;
		return $vars;
	}

	function generate_rewrite_rule($wp_rewrite) {
		$widgets_rule = array(
			$this->slug . '$' => 'index.php?share=1'
		);
		$wp_rewrite->rules = $widgets_rule + $wp_rewrite->rules;
	}

	function template_redirect() {
		if(get_query_var($this->query_var)) {
			$this->template();
			exit;
		}
	}

	function template() {

		$default_map = array_shift(get_posts(array('name' => 'deforestation', 'post_type' => 'map')));

		wp_enqueue_script('infoamazonia-widget', get_stylesheet_directory_uri() . '/js/infoamazonia.widget.js', array('jquery', 'underscore', 'chosen'), '1.4.1');
		wp_localize_script('infoamazonia-widget', 'infoamazonia_widget', array(
			'baseurl' => home_url('/' . qtrans_getLanguage() . '/embed/'),
			'defaultmap' => $default_map->ID
		));
		wp_enqueue_style('infoamazonia-widget', get_stylesheet_directory_uri() . '/css/infoamazonia.widget.css', array(), '1.0');
		get_template_part('content', 'share');
		exit;
	}

	// functions

	function get_share_url($vars = array()) {
		$query = http_build_query($vars);
		return home_url('/' . $this->slug . '/?' . $query);
	}
}

$infoamazonia_widget = new InfoAmazonia_Widget();

function infoamazonia_get_share_url($vars = array()) {
	global $infoamazonia_widget;
	return $infoamazonia_widget->get_share_url($vars);
}