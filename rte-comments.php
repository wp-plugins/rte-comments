<?php
/*
Plugin Name: RTE Comments
Plugin URI: http://www.connections-pro.com
Description: Add TinyMCE Rich Text Editor to WordPress Comments
Version: 1.0.3
Author: Steven A. Zahm
Author URI: http://www.connections-pro.com
Text Domain: rte_comments
Domain Path: /lang

Copyright 2013  Steven A. Zahm  (email : helpdesk@connections-pro.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! class_exists( 'RTE_Comments' ) ) {

	class RTE_Comments {

		/**
		* @var (object) RTE_Comments stores the instance of this class.
		*/
		private static $instance;

		/**
		 * A dummy constructor to prevent RTE_Comments from being loaded more than once.
		 *
		 * @access private
		 * @since 1.0
		 * @see RTE_Comments::instance()
		 * @see RTE_Comments();
		 */
		private function __construct() { /* Do nothing here */ }

		/**
		 * Main RTE_Comments Instance
		 *
		 * Insures that only one instance of RTE_Comments exists in memory at any one time.
		 *
		 * @access public
		 * @since 1.0
		 * @return object RTE_Comments
		 */
		public static function getInstance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new RTE_Comments();
				self::$instance->init();
			}
			return self::$instance;
		}

		/**
		 * Initiate the plugin.
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function init() {
			self::defineConstants();

			// Nothing to do during activation/deactivation yet...
			// register_activation_hook( dirname(__FILE__) . '/rte-comments.php', array( __CLASS__, 'activate' ) );
			// register_deactivation_hook( dirname(__FILE__) . '/rte-comments.php', array( __CLASS__, 'deactivate' ) );

			// Nothing to translate at present...
			// load_plugin_textdomain( 'rte_comments' , false , RTEC_DIR_NAME . 'lang' );

			// Unregister the comment-reply.js
			add_action( 'init', array( __CLASS__, 'deregisterJS' ) );

			// Enqueue the replacement comment-reply.js
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueueScripts' ) );

			// Alter the comment reply link to work with rte-comment-reply.js
			add_filter( 'comment_reply_link', array( __CLASS__, 'commentReplyLink' ) );

			// Replace the default comment form field with TinyMCE
			add_filter( 'comment_form_field_comment', array( __CLASS__, 'rteCommentForm' ) );
		}

		/**
		 * Define the constants.
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function defineConstants() {

			define( 'RTEC_VERSION', '1.0' );

			define( 'RTEC_DIR_NAME', plugin_basename( dirname( __FILE__ ) ) );
			define( 'RTEC_BASE_NAME', plugin_basename( __FILE__ ) );
			define( 'RTEC_BASE_PATH', plugin_dir_path( __FILE__ ) );
			define( 'RTEC_BASE_URL', plugin_dir_url( __FILE__ ) );
			
		}

		/**
		 * Called when activating  via the activation hook.
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		public static function activate() {

		}

		/**
		 * Called when deactivating via the deactivation hook.
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		public static function deactivate() {

		}

		/**
		 * Replace the standard comment form field with TinyMCE.
		 *
		 * @access private
		 * @since 1.0
		 * @param  (sting) $comment_field The HTML for the comment form field.
		 * @return (string) The HTML for the TinyMCE comment field.
		 */
		public static function rteCommentForm( $comment_field ) {

			ob_start();

			wp_editor( 
				'', 
				'comment', array(
						'media_buttons'           => false, // show insert/upload button(s) to users with permission
						'textarea_rows'           => '10', // re-size text area
						'dfw'                     => false, // replace the default full screen with DFW (WordPress 3.4+)
						'tinymce'                 => array(
							'theme_advanced_buttons1' => 'bold, italic, underline, |, bullist, numlist, |, justifyleft, justifycenter, justifyright, |, link, unlink, |, pastetext, pasteword, removeformat, |, undo, redo',
							'theme_advanced_buttons2' => '', // 2nd row, if needed
							'theme_advanced_buttons3' => '', // 3rd row, if needed
							'theme_advanced_buttons4' => '' // 4th row, if needed
					),
					'quicktags' => array(
						'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,close'
					)
				)
			);

			$comment_field = ob_get_clean();

			return $comment_field;
		}

		/**
		 * Change the comment reply link.
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		public static function commentReplyLink( $link ) {

			return str_replace( 'onclick=', 'data-onclick=', $link );
		}
		
		/**
		 * Deregister the comment-reply.js.
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		public static function deregisterJS() {

			wp_deregister_script( 'comment-reply' );
			
		}

		/**
		 * Enqueue the JavaScript.
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		public static function enqueueScripts() {

			// This js file is basicall a copy paste of the default comment-reply.js file with some code added to move TinyMCE in the DOM gracefully.
			wp_enqueue_script( 'rte-comment-reply', RTEC_BASE_URL . 'js/rte-comment-reply.js', array( 'jquery'), RTEC_VERSION );
			
		}

	}

	/**
	 * The main function responsible for returning the RTE_Comments instance
	 * to functions everywhere.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $rte_comments = RTE_Comments(); ?>
	 *
	 * @access public
	 * @since 1.0
	 * @return mixed (object) || (bool) An instance of RTE_Comments or FALSE if the current user can not post HTML markup or JavaScript code in pages, posts, and comments.
	 */
	function RTE_Comments() {
		return current_user_can( 'unfiltered_html' ) ? RTE_Comments::getInstance() : FALSE;
	}

	/**
	 * Start the plugin.
	 */
	add_action( 'plugins_loaded', 'RTE_Comments' );
	
}