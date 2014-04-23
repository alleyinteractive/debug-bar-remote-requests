<?php

class Debug_Bar_Remote_Requests_Panel extends Debug_Bar_Panel {

	public function init() {
		$this->title( __( 'Remote Requests', 'debug-bar-remote-requests' ) );
	}

	public function prerender() {
		$this->set_visible( true );
	}

	public function render() {
		?>
		<div id="debug-bar-remote-requests">
			<h2><span><?php _e( 'Total Requests:', 'debug-bar-remote-requests' ); ?></span> <?php echo absint( count( Debug_Bar_Remote_Requests()->log ) ) ?></h2>
			<h2><span><?php _e( 'Total Request Time:', 'debug-bar-remote-requests' ); ?></span> <?php echo esc_html( Debug_Bar_Remote_Requests()->time() ) ?></h2>

			<ol class="remote-requests-list wpd-queries">
				<?php foreach ( Debug_Bar_Remote_Requests()->log as $i => $log ) : ?>
					<li>
						<?php echo esc_html( $log['method'] ) ?> <?php echo esc_html( $log['url'] ) ?>
						<?php if ( isset( $log['code'], $log['message'] ) ) : ?>
							<br /><?php printf( __( 'Response: %s', 'debug-bar-remote-requests' ), esc_html( "{$log['code']} {$log['message']}" ) ) ?>
						<?php else : ?>
							<br /><?php _e( 'No response code found!', 'debug-bar-remote-requests' ); ?>
						<?php endif ?>
						<div class="qdebug">
							<?php
							$debug = explode( ', ', $log['backtrace'] );
							$debug = array_diff( $debug, array( 'require_once', 'require', 'include_once', 'include' ) );
							$debug = implode( ', ', $debug );
							$debug = str_replace( array( 'do_action, call_user_func_array' ), array( 'do_action' ), $debug );
							echo esc_html( $debug );
							?>
							<span>#<?php echo intval( $i ) + 1 ?> (<?php echo esc_html( Debug_Bar_Remote_Requests()->time( $log ) ) ?>)</span>
						</div>
						<?php if ( isset( $_GET['dbrr_full'] ) ) : ?>
							<div class="qdebug">
								<p>
									<strong><?php _e( 'Pre-Request Args:', 'debug-bar-remote-requests' ); ?></strong>
									<pre><?php echo esc_html( print_r( $log['args'], 1 ) ) ?></pre>
								</p>
								<p>
									<strong><?php _e( 'Post-Request Args:', 'debug-bar-remote-requests' ); ?></strong>
									<pre><?php echo esc_html( print_r( $log['final_args'], 1 ) ) ?></pre>
								</p>
								<p>
									<strong><?php _e( 'Response:', 'debug-bar-remote-requests' ); ?></strong>
									<pre><?php echo esc_html( print_r( $log['response'], 1 ) ) ?></pre>
								</p>
								<p>
									<strong><?php _e( 'HTTP Class:', 'debug-bar-remote-requests' ); ?></strong>
									<pre><?php echo esc_html( $log['class'] ) ?></pre>
								</p>
							</div>
						<?php endif ?>
					</li>
				<?php endforeach ?>
			</ol>
		</div>
		<?php
	}
}