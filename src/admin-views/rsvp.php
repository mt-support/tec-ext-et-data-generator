<!-- Generate RSVP Form -->
<hr>
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
