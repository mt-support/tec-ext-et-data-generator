<?php
namespace Tribe\Extensions\ET_Test_Data_Generator;
use Tribe__Settings;
use Tribe__Template as Template;

class Page {

	/**
	 * Stores the template class used.
	 *
	 * @since 1.0.0
	 *
	 * @var Template
	 */
	protected $template;

	/**
	 * Nonce key for generating Test Data.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $nonce_action_key = 'tec-ext-et-test-data-generator';

	/**
	 * Gets the instance of template class set for the metabox.
	 *
	 * @since 1.0.0
	 *
	 * @return Template Instance of the template we are using to render this metabox.
	 */
	public function get_template() {
		if ( empty( $this->template ) ) {
			$this->set_template();
		}
		return $this->template;
	}

	/**
	 * Normally ran when the class is setting up but configures the template instance that we will use render non v2 contents.
	 *
	 * @since 1.0.0
	 *
	 * @return void Setter with no return.
	 */
	public function set_template() {
		$this->template = new Template();
		$this->template->set_template_origin( tribe( Plugin::class ) );
		$this->template->set_template_folder( 'src/admin-views' );
		// Setup to look for theme files.
		$this->template->set_template_folder_lookup( false );
		// Configures this templating class extract variables.
		$this->template->set_template_context_extract( true );
	}


	/**
	 * @since 1.0.0
	 * @var string
	 */
	protected $menu_hook;

	/**
	 * Returns registered submenu slug.
	 * @since 1.0.0
	 * @return string Registered submenu slug.
	 */
	public function get_slug() {
		return 'et-test-data-generator';
	}

	/**
	 * Returns the registered submenu page hook.
	 * @since 1.0.0
	 * @return string Registered submenu page hook.
	 */
	public function get_menu_hook() {
		return $this->menu_hook;
	}

	/**
	 * Add admin menu.
	 * @since 1.0.0
	 */
	public function add_menu() {
		if ( class_exists( 'Tribe__Tickets__Main' ) ) {
			$parent = 'tec-tickets';
		}

		$this->menu_hook = add_submenu_page(
			$parent,
			__( 'ET Test Data Generator', 'tec-ext-et-test-data-generator' ),
			__( 'Test Data', 'tec-ext-et-test-data-generator' ),
			'edit_posts',
			$this->get_slug(),
			[ $this, 'render' ]
		);
	}

	/**
	 * Render admin menu page.
	 * @since 1.0.0
	 */
	public function render() {
		$args = [
			'nonce_action_key' => static::$nonce_action_key,
		];
		$this->get_template()->template( 'page', $args );
	}

	/**
	 *Parse POST request from Admin menu
	 *
	 * @since 1.0.0
	 */
	public function parse_request() {
		if ( empty( $_POST ) ) {
			return;
		}

		$redirect_url = tribe_get_request_var( '_wp_http_referer', admin_url( 'admin.php?page=et-test-data-generator' ) );
		$nonce = tribe_get_request_var( '_wpnonce' );
		if ( ! wp_verify_nonce( $nonce, static::$nonce_action_key ) ) {
			$redirect_url = add_query_arg( [ 'tribe_error' => 1 ] );
			wp_redirect( $redirect_url );
			exit;
		}

		$rsvps = tribe_get_request_var( [ 'tec-ext-et-test-data-generator', 'rsvps' ], [] );
		$tickets = tribe_get_request_var( [ 'tec-ext-et-test-data-generator', 'tickets' ], [] );
		$attendees = tribe_get_request_var( [ 'tec-ext-et-test-data-generator', 'attendees' ], [] );
		
		if ( ! empty( $rsvps['quantity'] ) ) {
			$created_rsvps = tribe( Generator\RSVP::class )->create( $rsvps['quantity'], $rsvps );
		}
		if ( ! empty( $tickets['quantity'] ) ) {
			$created_tickets = tribe( Generator\Ticket::class )->create( $tickets['quantity'], $tickets );
		}
		if ( ! empty( $attendees['quantity'] ) ) {
			$created_attendees = tribe( Generator\Attendee::class )->create( $attendees['quantity'], $attendees );
		}		

		if ( ! empty( $created_rsvps ) || ! empty( $created_tickets ) || ! empty( $created_attendees ) ) {
			$redirect_url = add_query_arg( [ 'tribe_success' => 1 ] );
			wp_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * Render success notice in template.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function render_success_notice() {
		return sprintf(
			'<p><strong>%1$s</strong> %2$s</p>',
			esc_html__(
				"Woohoo!",
				'tec-ext-et-test-data-generator'
			),
			esc_html__(
				"Your request was processed successfully.",
				'tec-ext-et-test-data-generator'
			)
		);
	}

	/**
	 * Render error notice in template.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function render_error_notice() {
		return sprintf(
			'<p><strong>%1$s</strong> %2$s</p>',
			esc_html__(
				"Oh No!",
				'tec-ext-et-test-data-generator'
			),
			esc_html__(
				"There's been an error and your request couldn't be completed. Please try again.",
				'tec-ext-et-test-data-generator'
			)
		);
	}
}
