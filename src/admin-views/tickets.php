<!-- Generate Tickets Form -->
<hr>
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
