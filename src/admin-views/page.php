<?php
/**
 * Template for the ET Test Data Generation page in Settings.
 *
 * @var string $nonce_action_key The nonce action key.
 *
 * @version 1.0.0
 */

?>
<h1><?php echo get_admin_page_title() ?></h1>
<?php do_action( 'tec_ext_et-test_data_generator_notices' ) ?>

<?php
//Fetch events and generate options for the dropdowns
$events = get_posts(array(
	'post_type' => 'tribe_events',
	'posts_per_page' => 300,
	'meta_key' => '_EventStartDate',
    'orderby' => 'meta_value',
	'order' => 'DESC'
));

$events_with_tickets = array();
foreach ($events as $event) {
    $event_id = $event->ID;
    // Check if the event has an RSVP or ticket
    if (tribe_events_has_tickets($event_id)) {
        array_push($events_with_tickets, $event);
    }
}

if( count($events_with_tickets) > 0 ) {
	// Loop through all events and generate ticket information
	$tickets_by_event = array();
	foreach ($events_with_tickets as $event) {
		// Get all tickets for the current event
		$tickets = \Tribe__Tickets__Tickets::get_event_tickets( $event->ID );

		// Store ticket information in an array
		$tickets_info = array();
		foreach ($tickets as $ticket) {
			$tickets_info[] = array(
				'id' => $ticket->ID,
				'name' => $ticket->name,
				'price' => $ticket->price,
			);

	// Store the ticket information in the tickets_by_event array
	$tickets_by_event[$event->ID] = $tickets_info;
	}

	// Convert the tickets_by_event array to a JSON object and echo it out
	echo '<script>var ticketsByEvent = ' . json_encode($tickets_by_event) . ';</script>';
}

$all_events_options = '';
$ticketed_events_options = '';

foreach ($events as $event) {
	$all_events_options = $all_events_options . '<option value="' . $event->ID . '">' . $event->post_title . '</option>' . PHP_EOL;
}

foreach ($events_with_tickets as $event) {
	$ticketed_events_options = $ticketed_events_options . '<option value="' . $event->ID . '">' . $event->post_title . '</option>' . PHP_EOL;
}

if( $all_events_options == '' ) {
	$all_events_options = '<option value="">No events found.</option>';
}

if( $events_with_tickets == '' ) {
	$events_with_tickets = '<option value="">No events with RSVP or Tickets found.</option>';
}
}
?>

<!-- Info section -->
<div style="background-color: whitesmoke">
	<h3>Important Notes</h3>
	<ul>
		<li>* What this tool really uses are the Event ID (for generating RSVPs and Tickets) and the Ticket ID (for generating Attendees). The dropdown selectors are only to help you find the IDs if you don't have them.</li>
		<li>* Since only IDs are required, you can manually enter the ID and click the generate button, without selecting anything on the dropdowns.</li>
		<li>* Each Generator option (RSVPs, Tickets, and Attendees) is an individual form, so you can only submit one type of generation request at a time.</li>
		<li>* In order to prevent chaos, sadness and tears, the dropdowns are limited to fetching only 300 events. If you have a site with a bazillion events, then you should find and use the IDs and no the dropdowns.</li>
		<li>* The number of RSVPs, Tickets and Attendees that can be generated will depend on this server's capabilities, which means that very big requests might time-out and this page will "die". If this happens, just refresh.</li>
		<li>* If this page times out and dies, don't re-send the POST request when you try to reload the page or the generation request will be re-submitted.</li>
	</ul>
</div>

<!-- Generate RSVP Form -->
<form id="gen-rsvps" method="post" action="" novalidate="novalidate">
	<?php wp_nonce_field( $nonce_action_key ); ?>
	<table class="form-table" role="presentation">
		<tbody>
		<tr>
			<td>
			<h2>Generate RSVPs</h2>
			</td>
		</tr>		
		<tr style="background-color: whitesmoke">
			<td>
				<label for="num_rsvps">Create</label>
				<input type="number" id='num_rsvps' name='tec-ext-et-test-data-generator[rsvps][quantity]'
                        placeholder="#" value="1" style="width: 90px">
				<label for="events_list_rsvp">RSVP tickets for Event title</label>
				<select id="events_list_rsvp" name="events_list">
					<option value="">Select an Event</option>
					<?php
						echo $all_events_options;
					?>
				</select>
				<label for="selected_event_id_rsvp">OR Event ID:</label>
                <input id="selected_event_id_rsvp" name="tec-ext-et-test-data-generator[rsvps][event_id]"
                placeholder="ID#" class="event-id" required onClick="this.select();">	
            </td>
			<td>
			<?php submit_button( 'Generate RSVPs' ); ?>			
			</td>
		</tr>
		<tr style="background-color: whitesmoke">
			<td>
			<label for="custom_rsvp_capacity">Capacity:</label>
			<input type="number" id="custom_rsvp_capacity" name="tec-ext-et-test-data-generator[rsvps][capacity]"
				placeholder="Random" style="width: 90px">
			<label for="custom_rsvp_stock">Stock:</label>
			<input type="number" id="custom_rsvp_stock" name="tec-ext-et-test-data-generator[rsvps][stock]"
				placeholder="Random" style="width: 90px">
			<label>
				<input type="checkbox" name="tec-ext-et-test-data-generator[rsvps][unlimitedcap]">Unlimited capacity
			</label>
			</td>
			<td></td>
		</tr>
		</tbody>
	</table>
</form>

<!-- Generate Tickets Form -->
<form id="gen-tickets" method="post" action="" novalidate="novalidate">
	<?php wp_nonce_field( $nonce_action_key ); ?>
	<table class="form-table" role="presentation">
		<tbody>
		<tr>
			<td>
			<h2>Generate Tickets</h2>
			</td>
		</tr>
		<?php if( class_exists( 'Tribe__Tickets__Tickets' ) ) : ?>
			<?php
			$providers = Tribe__Tickets__Tickets::modules();
			unset( $providers[ 'Tribe__Tickets__RSVP' ] );
			if ( 0 < count( $providers ) ) : ?>
			<tr style="background-color: whitesmoke">
				<td>
					<label for="num_tickets">Create</label>
					<input type="number" id='num_tickets' name='tec-ext-et-test-data-generator[tickets][quantity]'
							placeholder="#" value="1" style="width: 90px">
							<label for="events_list_tickets">Tickets for Event title</label>
							<select id="events_list_tickets" name="events_list">
								<option value="">Select an Event</option>
								<?php
									echo $all_events_options;
								?>
							</select>
							<label for="selected_event_id_tickets">OR Event ID:</label>
							<input id="selected_event_id_tickets" name="tec-ext-et-test-data-generator[tickets][event_id]"
							placeholder="ID#" class="event-id" required onClick="this.select();">	
				</td>
				<td>
				<?php submit_button( 'Generate Tickets' ); ?>			
				</td>
			</tr>
			<tr style="background-color: whitesmoke">
			<td>
			<?php if( class_exists( 'Tribe__Tickets_Plus__Main' ) ) : ?>
			<fieldset>
				<label>Capacity type: </label>
				<label>
					<input type="radio" name="tec-ext-et-test-data-generator[tickets][capacity_type]" value="individual" checked>Individual
				</label>
				<label>
					<input type="radio" name="tec-ext-et-test-data-generator[tickets][capacity_type]" value="shared">Shared
				</label>
				<label>
					<input type="radio" name="tec-ext-et-test-data-generator[tickets][capacity_type]" value="unlimited">Unlimited
				</label>
			</fieldset>
			<?php endif; ?>
			<label for="custom_tix_capacity">Capacity:</label>
			<input type="number" id="custom_tix_capacity" name="tec-ext-et-test-data-generator[tickets][capacity]"
				placeholder="Random" style="width: 90px">
			<label for="custom_tix_stock">Stock:</label>
			<input type="number" id="custom_tix_stock" name="tec-ext-et-test-data-generator[tickets][stock]"
				placeholder="Random" style="width: 90px">
			</td>
			<td></td>
		</tr>
			<?php else: ?>
				<tr style="background-color: whitesmoke">
					<td colspan="2">
						<p style="color: royalblue">
							<span style="padding-right: 10px; padding-left: 5px">ℹ</span>
							<em>️Please enable a ticket provider <strong>(Tickets Commerce, WooCommerce, EDD)</strong> to add Tickets to Events.</em>
						</p>
					</td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>
		</tbody>
	</table>
</form>

<!-- Generate Attendees Form -->
<form id="gen-attendees" method="post" action="" novalidate="novalidate">
	<?php wp_nonce_field( $nonce_action_key ); ?>
	<table class="form-table" role="presentation">
		<tbody>
		<tr>
			<td>
			<h2>Generate Attendees</h2>
			</td>
		</tr>		
		<tr style="background-color: whitesmoke">
			<td>
				<label for="num_attendees">Create</label>
				<input type="number" id='num_attendees' name='tec-ext-et-test-data-generator[attendees][quantity]'
                        placeholder="#" value="1" style="width: 90px">
				<label for="events_list_attendees">Attendees for Event</label>
				<select id="events_list_attendees" name="events_list">
				<option value="" style="width:100px">Select an Event</option>
					<?php
						echo $ticketed_events_options;
					?>
				</select>
				<input type="hidden" id="selected_event_for_attendees" name="tec-ext-et-test-data-generator[attendees][event_id]" value="">
				<label for="tickets_list_for_event">and Ticket</label>
				<select id="tickets_list_for_event" name="tickets_list">
				<option value="">Select a Ticket</option>
				</select>
				<label for="ticket_id_attendees">OR Ticket ID:</label>
                <input id="ticket_id_attendees" name="tec-ext-et-test-data-generator[attendees][ticket_id]"
                placeholder="ID#" class="event-id" required onClick="this.select();">
            </td>
			<td>
			<?php submit_button( 'Generate Attendees' ); ?>			
			</td>
		</tr>        
		</tbody>
	</table>
</form>

<style>
    input.event-id {
        min-height: 30px;
        padding-left: 8px;
    }
</style>

<!-- Javascript -->
<script>
	document.getElementById('events_list_rsvp').addEventListener('change', function() {
		document.getElementById('selected_event_id_rsvp').value = this.value;
	});
	document.getElementById('events_list_tickets').addEventListener('change', function() {
		document.getElementById('selected_event_id_tickets').value = this.value;
	});	

	// Get references to the select elements and hidden input
	var eventsList = document.getElementById('events_list_attendees');
	var ticketsList = document.getElementById('tickets_list_for_event');
	var selectedEvent = document.getElementById('selected_event_for_attendees');

	// Add an event listener to the eventsList select element
	eventsList.addEventListener('change', function() {
		// Get the selected event ID
		var selectedEventId = this.value;

		// Update the hidden input with the selected event ID
		selectedEvent.value = selectedEventId;

		// Clear the current options from the ticketsList select element
		ticketsList.innerHTML = '';

		// Add an option for each ticket in the selected event
		var tickets = ticketsByEvent[selectedEventId];
		if (tickets) {
			for (var i = 0; i < tickets.length; i++) {
			var ticket = tickets[i];
			var option = document.createElement('option');
			option.value = ticket.id;
			option.text = ticket.name + ' ($' + ticket.price + ')';
			ticketsList.appendChild(option);
			if( i == 0 ) {
				document.getElementById('ticket_id_attendees').value = ticket.id;
			}
		}
		} else {
			// Handle case where no tickets are available for the selected event
			var option = document.createElement('option');
			option.disabled = true;
			option.text = 'No tickets available for this event';
			ticketsList.appendChild(option);
		}
	});

	document.getElementById('tickets_list_for_event').addEventListener('change', function() {
    	document.getElementById('ticket_id_attendees').value = this.value;
	});
</script>