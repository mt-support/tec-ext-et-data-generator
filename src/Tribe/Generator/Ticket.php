<?php
namespace Tribe\Extensions\ET_Test_Data_Generator\Generator;

class Ticket {
	
	 /**
	 * A boolean to mark whether the Tickets will have unlimited capacity.
	 *
	 * @since 1.0.0
	 *
	 * @var boolean
	 */
	protected $unlimited_capacity = false;

	 /**
	 * An int to set custom Capacity for Tickets.
	 *
	 * @since 1.0.0
	 *
	 * @var int|null
	 */
	protected $custom_capacity;

	/**
	 * An int to set custom Stock for Tickets.
	 *
	 * @since 1.0.0
	 *
	 * @var int|null
	 */
	protected $custom_stock;

	/**
	 * A boolean to mark whether the Tickets will have individual or shared capacity.
	 *
	 * @since 1.0.0
	 *
	 * @var boolean
	 */
	protected $shared_capacity = false;

	/**
	 * Creates randomly generated Tickets.
	 *
	 * @since 1.0.0
	 * 
	 * @param int                           $quantity The number of Tickets to create.
	 * @param array<string,string|int|bool> $args     An array of arguments to customize the Ticket creation.
	 * @param callable|null                 $tick     An optional callback that will be fired after each Ticket creation;
	 *                                                the callback will receive the just created Ticket post object as
	 *                                                argument.
	 *
	 * @return array<\WP_Post> An array of the generated Tickets post objects.
	 * @throws \Tribe__Repository__Usage_Error If the arguments do not make sense in the context of the ORM Ticket
	 *                                         creation.
	 *
	 */
	public function create( $quantity = 1, array $args = [], callable  $tick = null ) {
		if( empty( $args['event_id'] ) ) {
			die('Event ID is required to generate Tickets.');
		}

		$event_id  = $args['event_id'];


		//Check Capacity type
		if( !empty( $args['capacity_type'] ) ) {
			switch( $args['capacity_type'] ) {
				case 'shared':
					$this->shared_capacity = true;
					break;
				case 'unlimited':
					$this->unlimited_capacity = true;
					break;
			}
		}

		if( !$this->unlimited_capacity && !empty( $args['capacity'] ) ) {
			$this->custom_capacity = $args['capacity'];
			//If custom Capacity is set, but not custom Stock, Custom Stock will equal the Custom Capacity.
			$this->custom_stock = !empty( $args['stock'] ) ? $args['stock'] : $this->custom_capacity;
		}

		//Get number of existing tickets for Event
		$provider = \Tribe__Tickets__Tickets::get_event_ticket_provider( $event_id );
		$existing_tickets  = count( tribe( $provider )->get_tickets($event_id) );

		//Generate Tickets
		for ( $i = 1; $i <= $quantity; $i++ ) {
			$title = 'Ticket ' . ( $existing_tickets + $i );
			$Tickets[] = $this->add_ticket( $event_id, $title );

			if ( is_callable( $tick ) ) {
				$tick( end( $Tickets ) );
			}
		}

		return $Tickets;
	}

	/**
	 * Creates Ticket for Event.
	 *
	 * @since 1.0.0
	 *
	 * @param $event_id
	 */
	public function add_ticket( $event_id, $title ) {
		if ( ! class_exists( \Tribe__Tickets__Tickets::class ) ) {
			return;
		}

		$provider = \Tribe__Tickets__Tickets::get_event_ticket_provider( $event_id );

		// Prior to 4.12.2, ET will return a string rather than an instance.
		if ( is_string( $provider ) ) {
			$provider = new $provider;
		}
		
		// If we don't have a paid provider as default, bail.
		if ( Tribe__Tickets__RSVP::class === $provider->class_name ) {
			return;
		}

			
		$type    = $this->get_random_ticket_type();
		$price   = $this->get_random_ticket_price( $type );

		if( $this->custom_capacity ) {
			$capacity = $this->custom_capacity;
			$stock    = $this->custom_stock;
		} else if( $this->unlimited_capacity ) {
			$capacity = '';
			$stock = '';
		} else {
			$capacity = random_int(1,9) * 10;
			$stock    = $capacity;
		}

		if( $this->shared_capacity ) {
			$data       = [
				'ticket_name'             => $type . ' - ' . $title,
				'ticket_price'            => $price,
				'ticket_description'      => 'Ticket for ' . $type . ' access to the Attendee.',
				'ticket_show_description' => 'yes',
				'tribe-ticket'            => [
					'mode'                    => \Tribe__Tickets__Global_Stock::GLOBAL_STOCK_MODE,
					'capacity'                => $capacity,
					'stock'                   => $stock
				],
			];
		} else {
			$data       = [
				'ticket_name'             => $type . ' - ' . $title,
				'ticket_price'            => $price,
				'ticket_description'      => 'Ticket for ' . $type . ' access to the Attendee.',
				'ticket_show_description' => 'yes',
				'tribe-ticket'            => [
					'capacity'                => $capacity,
					'stock'                   => $stock
				],
			];
		}
				

		$provider->ticket_add( $event_id, $data );
		add_post_meta( $event_id, '_AttendeeCost', $price );
	}

	/**
	 * Randomly pick a ticket type from a list.
	 *
	 * @since 1.0.0
	 *
	 */
	public function get_random_ticket_type() {
		$type_list  = [ 'Standard', 'General', 'Basic', 'Student', 'Early Bird', 'VIP', 'Platinum' ];
		$index = array_rand( $type_list, 1 );

		return $type_list[$index];
	}

	/**
	 * Creates random price for the ticket, based on ticket type.
	 *
	 * @since 1.0.0
	 *
	 * @param $type
	 */
	public function get_random_ticket_price( $type ) {
		
		$random_price = 9.99;

		switch( $type ) {
			
			case 'Student':
				$random_price = random_int(1,5) * 5;
				break;
			case 'Early Bird':
				$random_price = random_int(3,6) * 6;
				break;
			case 'Basic':
				$random_price = random_int(2,5) * 10;
				break;
			case 'Standard':
				$random_price = random_int(2,4) * 10;
				break;
			case 'General':
				$random_price = random_int(3,4) * 10;
				break;
			case 'VIP':
				$random_price = random_int(5,7) * 10;
				break;
			case 'Platinum':
				$random_price = random_int(6,9) * 13;
				break;
		}
			
		return $random_price;
	}
}
