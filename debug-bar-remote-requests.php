<?php
/*
	Plugin Name: Debug Bar Remote Requests
	Plugin URI: http://github.com/alleyinteractive.com/debug-bar-remote-requests
	Description: A simple add-on for Debug Bar that logs and profiles all remote requests made using the HTTP Request API
	Version: 0.1.2
	Author: Matthew Boynes
	Author URI: http://www.alleyinteractive.com/
*/
/*  This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( !class_exists( 'Debug_Bar_Remote_Requests' ) ) :

class Debug_Bar_Remote_Requests {

	private static $instance;

	public $log = array();

	public $total_time = 0;

	private function __construct() {
		/* Don't do anything, needs to be initialized via instance() method */
	}

	public function __clone() { wp_die( "Please don't __clone Debug_Bar_Remote_Requests" ); }

	public function __wakeup() { wp_die( "Please don't __wakeup Debug_Bar_Remote_Requests" ); }

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Debug_Bar_Remote_Requests;
			self::$instance->setup();
		}
		return self::$instance;
	}

	public function setup() {
		add_filter( 'debug_bar_panels', array( $this, 'add_panel' ) );
		add_filter( 'pre_http_request', array( $this, 'start' ), 99, 3 );
		add_action( 'http_api_debug', array( $this, 'end' ), 10, 5 );
	}

	public function add_panel( $panels ) {
		require_once( 'class-debug-bar-remote-requests-panel.php' );
		$panels[] = new Debug_Bar_Remote_Requests_Panel();
		return $panels;
	}

	public function start( $false, $args, $url ) {
		$log = array(
			'url' => $url,
			'method' => ! empty( $args['method'] ) ? $args['method'] : '',
			'start' => microtime( true ),
			'backtrace' => wp_debug_backtrace_summary( __CLASS__ )
		);
		if ( isset( $_GET['dbrr_full'] ) ) {
			$log['args'] = $args;
		}
		$this->log[] = $log;
		return $false;
	}

	public function end( $response, $type, $class, $args, $url ) {
		$i = count( $this->log );
		do {
			$i--;
			if ( isset( $this->log[ $i ]['url'] ) && $this->log[ $i ]['url'] == $url ) {
				$this->log[ $i ]['end'] = microtime( true );
				if ( is_wp_error( $response ) ) {
					$this->log[ $i ]['code'] = $response->get_error_code();
					$this->log[ $i ]['message'] = "Error: " . $response->get_error_message();
				} else {
					$this->log[ $i ]['code'] = ! empty( $response['response']['code'] ) ? $response['response']['code'] : '';
					$this->log[ $i ]['message'] = ! empty( $response['response']['message'] ) ? $response['response']['message'] : '';
				}
				if ( isset( $_GET['dbrr_full'] ) ) {
					$this->log[ $i ]['response'] = $response;
					$this->log[ $i ]['final_args'] = $args;
					$this->log[ $i ]['class'] = $class;
				}
				$this->total_time += ( $this->log[ $i ]['end'] - $this->log[ $i ]['start'] );
				return;
			}
		} while ( $i > 0 );

		# We should never end up here
		$this->log[] = array(
			'error' => sprintf( __( 'Received a response without a record of request for the URL: %s', 'debug-bar-remote-requests' ), $url )
		);
	}

	public function time( $log_item = false ) {
		if ( ! $log_item )
			$time = $this->total_time;
		else
			$time = $log_item['end'] - $log_item['start'];

		if ( $time > 1 )
			return number_format( $time, 3 ) . ' s';
		else
			return number_format( $time * 1000, 1 ) . ' ms';
	}
}

function Debug_Bar_Remote_Requests() {
	return Debug_Bar_Remote_Requests::instance();
}
add_action( 'plugins_loaded', 'Debug_Bar_Remote_Requests' );

endif;