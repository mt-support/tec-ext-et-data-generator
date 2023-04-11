<?php
namespace Tribe\Extensions\ET_Test_Data_Generator\Generator;

use Faker\Factory;

class Attendee {
	/**
	 * Creates randomly generated Attendees
	 *
	 * @param int                           $quantity The number of Attendees to generate.
	 * @param array<string,string|int|bool> $args     The Attendee generation arguments.
	 * @param callable|null                 $tick     An optional callback that will be fired after each Attendee creation;
	 *                                                the callback will receive the just created Attendee post object as
	 *                                                argument.
	 *
	 * @return array<\WP_Post> The generated Attendees post objects.
	 *
	 * @throws \Tribe__Repository__Usage_Error If the arguments do not make sense in the context of the ORM Attendee
	 *                                         creation.
	 */
	public function create( $quantity = 1, array $args = [], callable $tick = null ) {
		if( empty( $args['ticket_id'] ) ) {
			die('Ticket ID is required to generate Attendees.');
		}
		$ticket_id = $args['ticket_id'];
		
		//Sort Event ID
		if( empty( $args['event_id'] ) ) {
			$data_api = tribe( 'tickets.data_api' );
			$event_ids_obj = $data_api->get_event_ids($ticket_id);
			$event_id = $event_ids_obj[0];
		} else {
			$event_id  = $args['event_id'];
		}

		$faker     = Factory::create();

		for ( $i = 1; $i <= $quantity; $i++ ) {
			//Add attendee here			
			$firstname = $faker->firstName . ' ' .$faker->firstName;
			$lastname  = rand( 0, 11 ) < 10 ? $faker->lastName : $faker->lastName . '-' . $faker->lastName;
			$fullname  = $firstname . ' ' . $lastname;
			$email     = tribe_strtolower( str_replace(' ', '', $firstname) ) . '@' . tribe_strtolower( trim($lastname, "' -") ) . '.qa.evnt.is';

			$data = [
				'full_name'         => $fullname,
				'email'             => $email,
				'ticket_id'         => $ticket_id,
				'post_id'           => $event_id,
			];

			/** @var Tribe__Tickets__Attendees $attendees */
			$attendees = tribe( 'tickets.attendees' );
	
			$attendee_object = $attendees->create_attendee( $ticket_id, $data );
	
			if ( ! $attendee_object ) {
				throw new Exception( __( 'Unable to process your request, attendee creation failed.', 'event-tickets' ) );
			}
			
			if ( is_callable( $tick ) ) {
				$tick( $attendee_object );
			}

			$Attendees[] = $attendee_object;
		}

		return $Attendees;
	}
}
