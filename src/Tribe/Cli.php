<?php
/**
 * Handles the integration with wp-cli.
 *
 * @since   1.0.0
 *
 * @package Tribe\Extensions\ET_Test_Data_Generator
 */

namespace Tribe\Extensions\ET_Test_Data_Generator;

use Tribe\Extensions\ET_Test_Data_Generator\Cli\Command;

/**
 * Class Cli
 *
 * @since   1.0.0
 *
 * @package Tribe\Extensions\ET_Test_Data_Generator
 */
class Cli extends \tad_DI52_ServiceProvider {

	/**
	 * Registers the filters, actions and bindings required to provide wp-cli support to the extension.
	 *
	 * @since 1.0.0
	 *
	 * @throws \Exception
	 */
	public function register() {
		$command = new Command();
		\WP_CLI::add_command( 'et-test-data rsvps generate', [ $command, 'generate_rsvps' ] );
		\WP_CLI::add_command( 'et-test-data tickets generate', [ $command, 'generate_tickets' ] );
		\WP_CLI::add_command( 'et-test-data attendees generate', [ $command, 'generate_attendees' ] );
	}
}
