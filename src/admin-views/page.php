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
// Initialize variables outside the conditional block
$all_events_options = '';
$ticketed_events_options = '';
$tickets_by_event = [];

// Fetch events and generate options for the dropdowns
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

if( count( $events_with_tickets ) > 0 ) {
	// Loop through all events and generate ticket information
	foreach ( $events_with_tickets as $event ) {
		// Get all tickets for the current event
		$tickets = \Tribe__Tickets__Tickets::get_event_tickets($event->ID);

		// Store ticket information in an array
		$tickets_info = array();
		foreach ( $tickets as $ticket ) {
			$tickets_info[] = array(
				'id' => $ticket->ID,
				'name' => $ticket->name,
				'price' => $ticket->price,
			);
		}

		// Store the ticket information in the tickets_by_event array
		$tickets_by_event[ $event->ID ] = $tickets_info;
	}

	// Convert the tickets_by_event array to a JSON object and echo it out
	echo '<script>var ticketsByEvent = ' . json_encode($tickets_by_event) . ';</script>';
}

// Generate event options
foreach ( $events as $event ) {
	$all_events_options .= '<option value="' . $event->ID . '">' . $event->post_title . '</option>' . PHP_EOL;
}

foreach ( $events_with_tickets as $event ) {
	$ticketed_events_options .= '<option value="' . $event->ID . '">' . $event->post_title . '</option>' . PHP_EOL;
}

if ( $all_events_options == '' ) {
	$all_events_options = '<option value="">No events found.</option>';
}

if ( $events_with_tickets == '' ) {
	$events_with_tickets = '<option value="">No events with RSVP or Tickets found.</option>';
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

<?php require_once 'rsvp.php'; ?>

<?php require_once 'tickets.php'; ?>

<?php require_once 'attendees.php'; ?>

<?php require_once 'presets.php'; ?>

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
