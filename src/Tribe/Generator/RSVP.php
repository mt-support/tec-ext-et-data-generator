<?php
namespace Tribe\Extensions\ET_Test_Data_Generator\Generator;

class RSVP {
	
	 /**
	 * A boolean to mark whether RSVP will have unlimited capacity.
	 *
	 * @since 1.0.0
	 *
	 * @var boolean
	 */
	protected $unlimited_capacity = false;

	 /**
	 * An int to set custom Capacity for RSVP.
	 *
	 * @since 1.0.0
	 *
	 * @var int|null
	 */
	protected $custom_capacity;

	/**
	 * An int to set custom Stock for RSVP.
	 *
	 * @since 1.0.0
	 *
	 * @var int|null
	 */
	protected $custom_stock;


	/**
	 * Creates randomly generated RSVPs.
	 *
	 * @since 1.0.0
	 *
	 * @param int                           $quantity The number of RSVPs to create.
	 * @param array<string,string|int|bool> $args     An array of arguments to customize the RSVP creation.
	 * @param callable|null                 $tick     An optional callback that will be fired after each RSVP creation;
	 *                                                the callback will receive the just created RSVP post object as
	 *                                                argument.
	 *
	 * @throws \Tribe__Repository__Usage_Error If the arguments do not make sense in the context of the ORM RSVP
	 *                                         creation.
	 *
	 * @return array<\WP_Post> An array of the generated RSVPs post objects.
	 */
	public function create( $quantity = 1, array $args = [], callable $tick = null ) {

		if( empty( $args['event_id'] ) ) {
			die('Event ID is required to generate RSVPs.');
		}

		$event_id  = $args['event_id'];

		//If RSVP is set to unlimited capacity, set variable to true and skip custom capacity and stock checks.
		if( !empty( $args['unlimitedcap'] ) ) {
			$this->unlimited_capacity = true;
		} else {
			if( !empty( $args['capacity'] ) ) {
				$this->custom_capacity = $args['capacity'];
				//If custom Capacity is set, but not custom Stock, Custom Stock will equal the Custom Capacity.
				$this->custom_stock = !empty( $args['stock'] ) ? $args['stock'] : $this->custom_capacity;
			}
		}

		//Get number of existing RSVPs for Event
		$existing_rsvps = count( tribe( 'tickets.rsvp' )->get_tickets($event_id) );

		//Generate RSVPs
		for ( $i = 1; $i <= $quantity; $i++ ) {
			$title = 'RSVP ' . ( $existing_rsvps + $i );
			$RSVPs[] = $this->add_rsvp( $event_id, $title );

			if ( is_callable( $tick ) ) {
				$tick( end( $RSVPs ) );
			}
		}

		return $RSVPs;
	}

	/**
	 * Creates RSVP for Event.
	 *
	 * @since 1.0.0
	 *
	 * @param $event_id
	 */
	public function add_rsvp( $event_id, $title ) {
		if ( ! tribe()->isBound( 'tickets.rsvp' ) ) {
			return;
		}

		if( $this->custom_capacity ) {
			$capacity = $this->custom_capacity;
			$stock    = $this->custom_stock;
		} else {
			$capacity = random_int(1,9) * 10;
			$stock    = $capacity;
		}

		if( $this->unlimited_capacity ) {
			$data = [
				'ticket_name'             => $title,
				'ticket_description'      => 'RSVP to join us!',
				'ticket_show_description' => 'yes',
				'tribe-ticket'            => [
					'capacity'                => '',
					'stock'                   => ''
				],
			];
		} else {
			$data = [
				'ticket_name'             => $title,
				'ticket_description'      => 'RSVP to join us!',
				'ticket_show_description' => 'yes',
				'tribe-ticket'            => [
					'capacity'                => $capacity,
					'stock'                   => $stock
				],
			];
		}

		tribe( 'tickets.rsvp' )->ticket_add( $event_id, $data );
		add_post_meta( $event_id, '_EventCost', '0' );
	}
}
