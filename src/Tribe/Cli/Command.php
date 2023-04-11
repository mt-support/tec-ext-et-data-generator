<?php
/**
 * Proxies wp-cli command to the generators.
 *
 * @since   1.0.1
 *
 * @package Tribe\Extensions\ET_Test_Data_Generator\Cli
 */

namespace Tribe\Extensions\ET_Test_Data_Generator\Cli;

use Tribe\Extensions\ET_Test_Data_Generator\Generator\RSVP;
use Tribe\Extensions\ET_Test_Data_Generator\Generator\Ticket;
use Tribe\Extensions\ET_Test_Data_Generator\Generator\Attendee;
use function WP_CLI\Utils\make_progress_bar;

/**
 * Class Command
 *
 * @since   1.0.0
 *
 * @package Tribe\Extensions\ET_Test_Data_Generator\Cli
 */
class Command {
	
	/**
	 * Create a set of RSVPs for an Event ID.
	 *
	 * ## OPTIONS
	 *
	 * [<quantity>]
	 * : The number of RSVPs to generate.
	 * ---
	 * default: 1
	 * ---
	 * 
	 * [--eventid=<event-id>]
	 * : The Event ID for which to create the RSVPs.
	 * ---
	 * Required for RSVP and Ticket generation.
	 * ---
	 *
	 * [--capacity=<quantity>]
	 * : The max number of sellable slots for the RSVP.
	 * ---
	 * default: random
	 * ---
	 *
	 * [--stock=<quantity>]
	 * : The number of available RSVPs for Attendees.
	 * ---
	 * default: random
	 * ---
	 *
	 * [--unlimitedcapacity]
	 * : Whether an RSVP has unlimited capacity.
	 * ---
	 * default: false
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp et-test-data rsvps generate 25 --eventid=95
	 *     wp et-test-data rsvps generate 25 --eventid=99 --capacity=50
	 *     wp et-test-data rsvps generate 25 --eventid=86 --capacity=20 --stock=10
	 *     wp et-test-data rsvps generate 25 --eventid=72 --unlimitedcapacity
	 *
	 * @when after_wp_load
	 */
	public function generate_rsvps( array $args = [], array $assoc_args = [] ) {
		
		if( !isset( $assoc_args['eventid'] ) ) {
			\WP_CLI::error( 'Event ID is REQUIRED for RSVP generation.' );
		}

		$generator_args = $this->process_generator_args( $assoc_args );

		$quantity = isset( $args[0] ) ? (int) $args[0] : 1;

		$progress_bar = make_progress_bar( 'Creating RSVPs...', $quantity );
		$tick         = static function () use ( $progress_bar ) {
			$progress_bar->tick();
		};

		try {
			( new RSVP() )->create( $quantity, $generator_args, $tick );
			$progress_bar->finish();
		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}
		
		\WP_CLI::success( "Generated {$quantity} " . _n( 'RSVP', 'RSVPs', $quantity ) );
	}

	/**
	 * Create a set of Tickets for an Event ID.
	 *
	 * ## OPTIONS
	 *
	 * [<quantity>]
	 * : The number of Tickets to generate.
	 * ---
	 * default: 1
	 * ---
	 * 
	 * [--eventid=<event-id>]
	 * : The Event ID for which to create the Tickets.
	 * ---
	 * Required for Ticket generation.
	 * ---
	 * 
	 * [--capacity=<quantity>]
	 * : The max number of sellable slots for the Ticket.
	 * ---
	 * default: random
	 * ---
	 *
	 * [--stock=<quantity>]
	 * : The number of available Tickets for Attendees.
	 * ---
	 * default: random
	 * ---
	 *
	 * [--unlimitedcapacity]
	 * : Whether a Ticket has unlimited capacity.
	 * ---
	 * default: false
	 * ---
	 *
	 * [--sharedcapacity]
	 * : Whether a ticket should share its capacity with other tickets in the Event.
	 * ---
	 * default: false
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp et-test-data tickets generate 4 --eventid=104
	 *     wp et-test-data tickets generate 7 --eventid=42 --capacity=25 --stock=5
	 *     wp et-test-data tickets generate 23 --eventid=80 --capacity=50 --sharedcapacity
	 *
	 * @when after_wp_load
	 */
	public function generate_tickets( array $args = [], array $assoc_args = [] ) {

		if( !isset( $assoc_args['eventid'] ) ) {
			\WP_CLI::error( 'Event ID is REQUIRED for Ticket generation.' );
		}

		$quantity  = isset( $args[0] ) ? (int) $args[0] : 1;

		$generator_args = $this->process_generator_args( $assoc_args );

		$progress_bar = make_progress_bar( 'Creating Tickets...', $quantity );
		$tick         = static function () use ( $progress_bar ) {
			$progress_bar->tick();
		};

		try {
			( new Ticket() )->create( $quantity, $generator_args, $tick );
			$progress_bar->finish();
		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}
		\WP_CLI::success( "Generated {$quantity} " . _n( 'Ticket', 'Tickets', $quantity ) );
	}

	/**
	 * Create a set of Attendees for a Ticket ID.
	 *
	 * ## OPTIONS
	 *
	 * [<quantity>]
	 * : The number of Attendees to generate.
	 * ---
	 * default: 1
	 * ---
	 * 
	 * [--ticketid=<ticket-id>]
	 * : The Ticket ID for which to create Attendees.
	 * ---
	 * Required for Attendees generation.
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp et-test-data attendees generate 10 --ticket-id=88
	 *
	 * @when after_wp_load
	 */
	public function generate_attendees( array $args = [], array $assoc_args = [] ) {
		
		if( !isset( $assoc_args['ticketid'] ) ) {
			\WP_CLI::error( 'Ticket ID is REQUIRED for Attendee generation.' );
		}

		$generator_args = $this->process_generator_args( $assoc_args );

		$quantity     = isset( $args[0] ) ? (int) $args[0] : 1;

		$progress_bar = make_progress_bar( 'Creating Attendees...', $quantity );
		$tick         = static function () use ( $progress_bar ) {
			$progress_bar->tick();
		};

		try {
			( new Attendee() )->create( $quantity, $generator_args, $tick );
			$progress_bar->finish();
		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}
		\WP_CLI::success( "Generated {$quantity} " . _n( 'Attendee', 'Attendees', $quantity ) );
	}

	/*
	* Process the assoc_args from the CLI into an array for the generators.
	* 
	* @when after_wp_load
	*/
	public function process_generator_args( $assoc_args ) {

		$generator_args = array();

		if ( ! empty( $assoc_args['eventid'] ) ) {
			$generator_args['event_id'] = $assoc_args['eventid'];
		}

		if ( ! empty( $assoc_args['ticketid'] ) ) {
			$generator_args['ticket_id'] = $assoc_args['ticketid'];
		}

		if ( ! empty( $assoc_args['capacity'] ) ) {
			$generator_args['capacity'] = $assoc_args['capacity'];
		}

		if ( ! empty( $assoc_args['stock'] ) ) {
			$generator_args['stock'] = $assoc_args['stock'];
		}

		if ( ! empty( $assoc_args['unlimitedcapacity'] ) ) {
			$generator_args['capacity_type'] = 'unlimited';
		}

		if ( ! empty( $assoc_args['sharedcapacity'] ) ) {
			$generator_args['capacity_type'] = 'shared';
		}

		return $generator_args;
	}
}
