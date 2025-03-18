<?php
namespace Tribe\Extensions\ET_Test_Data_Generator\Generator;

use TEC\Tickets_Plus\Ticket_Presets\Repositories\Ticket_Presets;
use TEC\Tickets_Plus\Ticket_Presets\Models\Ticket_Preset;

class Preset {

	/**
	 * A boolean to mark whether the Presets will have unlimited capacity.
	 *
	 * @since 1.0.0
	 *
	 * @var boolean
	 */
	protected $unlimited_capacity = false;

	/**
	 * An int to set custom Capacity for Presets.
	 *
	 * @since 1.0.0
	 *
	 * @var int|null
	 */
	protected $custom_capacity;

	/**
	 * Stores the arguments passed to the create method.
	 *
	 * @since 1.0.0
	 *
	 * @var array<string,string|int|bool>
	 */
	protected $args = [];

	/**
	 * Creates randomly generated Ticket Presets.
	 *
	 * @since 1.0.0
	 *
	 * @param int                           $quantity The number of Presets to create.
	 * @param array<string,string|int|bool> $args     An array of arguments to customize the Preset creation.
	 * @param ?callable                     $tick     An optional callback that will be fired after each Preset creation;
	 *                                                the callback will receive the just created Preset ID as argument.
	 *
	 * @return array<int> An array of the generated Preset IDs.
	 */
	public function create( $quantity = 1, array $args = [], ?callable $tick = null ) {
		$presets = [];
		// Store args for use in other methods
		$this->args = $args;

		// Get current number of existing presets.
		$repository       = tribe( Ticket_Presets::class );
		$existing_presets = $repository->count_all();

		// Generate Presets.
		for ( $i = 1; $i <= $quantity; $i++ ) {
			$args['name'] = ! empty( $args['title_prefix'] )
				? $args['title_prefix'] . ' ' . ( $existing_presets + $i )
				: 'Preset ' . ( $existing_presets + $i );

			$preset_id = $this->add_preset( $args );

			if ( $preset_id ) {
				$presets[] = $preset_id;

				if ( is_callable( $tick ) ) {
					$tick( $preset_id );
				}
			}
		}

		return $presets;
	}

	/**
	 * Creates a single Ticket Preset.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string,string|int|bool> $args The preset arguments.
	 *
	 * @return int|false The preset ID if successful, false otherwise.
	 */
	public function add_preset( $args ) {
		if ( ! class_exists( 'TEC\Tickets_Plus\Ticket_Presets\Repositories\Ticket_Presets' ) ) {
			return false;
		}

		$type = $this->get_random_ticket_type();
		// Use fixed price if provided in args.
		$price = $this->args['fixed_price'] ?? $this->get_random_ticket_price( $type );

		// Check Capacity type.
		if ( ! empty( $args['capacity_type'] ) && $args['capacity_type'] === 'unlimited' ) {
			$capacity = -1;
			$capacity_type = 'unlimited';
		} else if ( ! empty( $args['capacity'] ) ) {
			$capacity = absint( $args['capacity'] );
			$capacity_type = 'own';
		} else {
			$capacity = random_int( 1, 9 ) * 10;
			$capacity_type = 'own';
		}

		// Use custom description if provided
		$description = isset( $this->args['description'] )
			? $this->args['description']
			: "Preset for {$type} ticket.";

		$ticket_name = isset( $this->args['ticket_name'] )
			? $this->args['ticket_name']
			: "Generated {$type} Ticket";

		// Construct the preset data structure.
		$data = [
			'name' => $args['name'],
			'description' => $description,
			'cost' => $price,
			'ticket_name' => $ticket_name,
			'ticket_type' => $type,
			'aic' => [],
			'capacity' => [
				'type' => $capacity_type,
				'amount' => $capacity,
			],
			'sale_start_logic' => [
				'relative_to' => $args['sale_start_logic']['relative_to'] ?? 'start',
				'direction'   => $args['sale_start_logic']['direction'] ?? 'before',
				'period'      => $args['sale_start_logic']['period'] ?? 'day',
				'length'      => $args['sale_start_logic']['length'] ?? 1
			],
			'sale_end_logic' => [
				'relative_to' => $args['sale_end_logic']['relative_to'] ?? 'start',
				'direction'   => $args['sale_end_logic']['direction'] ?? 'after',
				'period'      => $args['sale_end_logic']['period'] ?? 'hour',
				'length'      => $args['sale_end_logic']['length'] ?? 1
			],
		];

		$preset = Ticket_Preset::create(
			[
				'slug' => sanitize_title( $data['name'] ),
				'data' => wp_json_encode( $data ),
			]
		);

		// Save the preset using the repository.
		$repository = tribe( Ticket_Presets::class );
		return $repository->insert( $preset );
	}

	/**
	 * Randomly pick a ticket type from a list.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_random_ticket_type() {
		$type_list = [ 'Standard', 'General', 'Basic', 'Student', 'Early Bird', 'VIP', 'Platinum' ];
		$index = array_rand( $type_list, 1 );

		return $type_list[$index];
	}

	/**
	 * Creates random price for the preset, based on ticket type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type The ticket type.
	 *
	 * @return float
	 */
	public function get_random_ticket_price( $type ) {
		$random_price = 9.99;

		switch ( $type ) {
			case 'Student':
				$random_price = random_int( 1, 5 ) * 5;
				break;
			case 'Early Bird':
				$random_price = random_int( 3, 6 ) * 6;
				break;
			case 'Basic':
				$random_price = random_int( 2, 5 ) * 10;
				break;
			case 'Standard':
				$random_price = random_int( 2, 4 ) * 10;
				break;
			case 'General':
				$random_price = random_int( 3, 4 ) * 10;
				break;
			case 'VIP':
				$random_price = random_int( 5, 7 ) * 10;
				break;
			case 'Platinum':
				$random_price = random_int( 6, 9 ) * 13;
				break;
		}

		return $random_price;
	}
}
