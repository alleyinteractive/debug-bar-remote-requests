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
			<h2><span>Total Requests:</span> <?php echo count( Debug_Bar_Remote_Requests()->log ) ?></h2>
			<h2><span>Total Request Time:</span> <?php echo Debug_Bar_Remote_Requests()->time() ?></h2>

			<ol class="remote-requests-list wpd-queries">
				<?php foreach ( Debug_Bar_Remote_Requests()->log as $i => $log ) : ?>
					<li>
						<?php echo $log['args']['method'] ?> <?php echo $log['url'] ?>
						<?php if ( isset( $log['response']['response']['code'], $log['response']['response']['message'] ) ) : ?>
							<br />Response: <?php echo "{$log['response']['response']['code']} {$log['response']['response']['message']}" ?>
						<?php else : ?>
							<br />No response code found!
						<?php endif ?>
						<div class="qdebug">
							<?php
							$debug = explode( ', ', $log['backtrace'] );
							$debug = array_diff( $debug, array( 'require_once', 'require', 'include_once', 'include' ) );
							$debug = implode( ', ', $debug );
							$debug = str_replace( array( 'do_action, call_user_func_array' ), array( 'do_action' ), $debug );
							echo "$debug";
							?>
							<span>#<?php echo $i + 1 ?> (<?php echo Debug_Bar_Remote_Requests()->time( $log ) ?>)</span>
						</div>
						<?php if ( isset( $_GET['dbrr_full'] ) ) : ?>
							<div class="qdebug">
								<p>
									<strong>Pre-Request Args:</strong>
									<pre><?php print_r( $log['args'] ) ?></pre>
								</p>
								<p>
									<strong>Post-Request Args:</strong>
									<pre><?php print_r( $log['final_args'] ) ?></pre>
								</p>
								<p>
									<strong>Response:</strong>
									<pre><?php print_r( $log['response'] ) ?></pre>
								</p>
								<p>
									<strong>HTTP Class:</strong>
									<pre><?php echo $log['class'] ?></pre>
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