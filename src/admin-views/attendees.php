<!-- Generate Attendees Form -->
<hr>
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
