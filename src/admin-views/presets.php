<?php
if ( ! class_exists( 'TEC\Tickets_Plus\Ticket_Presets\Repositories\Ticket_Presets' ) ) {
	return;
} ?>

<hr>
<form id="gen-presets" method="post" action="" novalidate="novalidate">
	<div><h2>Generate Presets</h2></div>
	<input type="hidden" name="action" value="tec_tickets_save_preset">
	<?php wp_nonce_field( $nonce_action_key ); ?>

	<div>
		<label for="num_presets">Create</label>
		<input type="number" id="num_presets" name="tec-ext-et-test-data-generator[presets][quantity]"
			placeholder="#" value="1" style="width: 90px">
	</div>

	<div class="tribe-tickets-preset-field">
		<label for="preset-name"><?php esc_html_e( 'Preset Name', 'et-test-data-generator' ); ?> <span class="required">*</span></label>
		<input type="text" id="preset-name" name="tec-ext-et-test-data-generator[presets][name]">
		<p class="description"><?php esc_html_e( 'Give your preset a descriptive name.', 'et-test-data-generator' ); ?></p>
	</div>

	<div class="tribe-tickets-preset-field">
		<label for="preset-description"><?php esc_html_e( 'Description', 'et-test-data-generator' ); ?> <span class="required">*</span></label>
		<textarea id="preset-description" name="tec-ext-et-test-data-generator[presets][description]"></textarea>
		<p class="description"><?php esc_html_e( 'Describe what this preset is used for.', 'et-test-data-generator' ); ?></p>
	</div>

	<div class="tribe-tickets-preset-field">
		<label for="preset-cost"><?php esc_html_e( 'Cost', 'et-test-data-generator' ); ?> <span class="required">*</span></label>
		<input type="number" id="preset-cost" name="tec-ext-et-test-data-generator[presets][cost]" min="0" step="0.01">
		<p class="description"><?php esc_html_e( 'Set the default ticket cost.', 'et-test-data-generator' ); ?></p>
	</div>

	<div class="tribe-tickets-preset-field">
		<label for="preset-ticket-name"><?php esc_html_e( 'Default Ticket Name', 'et-test-data-generator' ); ?> <span class="required">*</span></label>
		<input type="text" id="preset-ticket-name" name="tec-ext-et-test-data-generator[presets][ticket_name]">
		<p class="description"><?php esc_html_e( 'The default name for tickets created from this preset.', 'et-test-data-generator' ); ?></p>
	</div>

	<input type="hidden" name="tec-ext-et-test-data-generator[presets][ticket_type]" value="default">

	<div class="tribe-tickets-preset-field">
		<label for="preset-capacity-amount"><?php esc_html_e( 'Default Capacity', 'et-test-data-generator' ); ?> <span class="required">*</span></label>
		<div class="tribe-tickets-preset-capacity">
			<div class="capacity-amount-wrapper">
				<input type="number" id="preset-capacity-amount" name="tec-ext-et-test-data-generator[presets][capacity][amount]" min="0">
			</div>
			<select id="preset-capacity-type" name="tec-ext-et-test-data-generator[presets][capacity][type]">
				<option value="own" selected><?php esc_html_e( 'Set capacity', 'et-test-data-generator' ); ?></option>
				<option value="unlimited"><?php esc_html_e( 'Unlimited', 'et-test-data-generator' ); ?></option>
			</select>
		</div>
		<p class="description"><?php esc_html_e( 'Set the default ticket capacity.', 'et-test-data-generator' ); ?></p>
	</div>

	<div class="tribe-tickets-preset-field">
		<h3><?php esc_html_e( 'Sale Period', 'et-test-data-generator' ); ?></h3>

		<div class="tribe-tickets-preset-sale-logic">
			<div class="tribe-tickets-preset-sale-start">
				<label><?php esc_html_e( 'Start Sale', 'et-test-data-generator' ); ?></label>
				<select name="tec-ext-et-test-data-generator[presets][sale_start_logic][relative_to]">
					<option value="published" selected><?php esc_html_e( 'When published', 'et-test-data-generator' ); ?></option>
					<option value="start"><?php esc_html_e( 'Event start', 'et-test-data-generator' ); ?></option>
					<option value="now"><?php esc_html_e( 'Immediately', 'et-test-data-generator' ); ?></option>
				</select>
				<select name="tec-ext-et-test-data-generator[presets][sale_start_logic][direction]">
					<option value="before" selected><?php esc_html_e( 'Before', 'et-test-data-generator' ); ?></option>
					<option value="after"><?php esc_html_e( 'After', 'et-test-data-generator' ); ?></option>
				</select>
				<input type="number" name="tec-ext-et-test-data-generator[presets][sale_start_logic][length]" min="1" value="1">
				<select name="tec-ext-et-test-data-generator[presets][sale_start_logic][period]">
					<option value="minute"><?php esc_html_e( 'Minutes', 'et-test-data-generator' ); ?></option>
					<option value="hour"><?php esc_html_e( 'Hours', 'et-test-data-generator' ); ?></option>
					<option value="day" selected><?php esc_html_e( 'Days', 'et-test-data-generator' ); ?></option>
					<option value="week"><?php esc_html_e( 'Weeks', 'et-test-data-generator' ); ?></option>
					<option value="month"><?php esc_html_e( 'Months', 'et-test-data-generator' ); ?></option>
				</select>
			</div>

			<div class="tribe-tickets-preset-sale-end">
				<label><?php esc_html_e( 'End Sale', 'et-test-data-generator' ); ?></label>
				<select name="tec-ext-et-test-data-generator[presets][sale_end_logic][relative_to]">
					<option value="start" selected><?php esc_html_e( 'Event start', 'et-test-data-generator' ); ?></option>
					<option value="end"><?php esc_html_e( 'Event end', 'et-test-data-generator' ); ?></option>
				</select>
				<select name="tec-ext-et-test-data-generator[presets][sale_end_logic][direction]">
					<option value="before"><?php esc_html_e( 'Before', 'et-test-data-generator' ); ?></option>
					<option value="after" selected><?php esc_html_e( 'After', 'et-test-data-generator' ); ?></option>
				</select>
				<input type="number" name="tec-ext-et-test-data-generator[presets][sale_end_logic][length]" min="1" value="1">
				<select name="tec-ext-et-test-data-generator[presets][sale_end_logic][period]">
					<option value="minute"><?php esc_html_e( 'Minutes', 'et-test-data-generator' ); ?></option>
					<option value="hour" selected><?php esc_html_e( 'Hours', 'et-test-data-generator' ); ?></option>
					<option value="day"><?php esc_html_e( 'Days', 'et-test-data-generator' ); ?></option>
					<option value="week"><?php esc_html_e( 'Weeks', 'et-test-data-generator' ); ?></option>
					<option value="month"><?php esc_html_e( 'Months', 'et-test-data-generator' ); ?></option>
				</select>
			</div>
		</div>
	</div>

	<div class="tribe-tickets-preset-submit">
		<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Preset', 'et-test-data-generator' ); ?></button>
	</div>
</form>

<style>
	.tribe-tickets-preset-field {
		margin-bottom: 20px;
	}

	.tribe-tickets-preset-field label {
		display: block;
		font-weight: bold;
		margin-bottom: 5px;
	}

	.tribe-tickets-preset-field input[type="text"],
	.tribe-tickets-preset-field input[type="number"],
	.tribe-tickets-preset-field textarea,
	.tribe-tickets-preset-field select {
		width: 100%;
		max-width: 400px;
	}

	.tribe-tickets-preset-field textarea {
		min-height: 100px;
	}

	.tribe-tickets-preset-capacity {
		display: flex;
		gap: 10px;
		align-items: center;
	}

	.tribe-tickets-preset-capacity input {
		width: 150px !important;
	}

	.tribe-tickets-preset-sale-logic {
		display: flex;
		flex-direction: column;
		gap: 15px;
	}

	.tribe-tickets-preset-sale-start,
	.tribe-tickets-preset-sale-end {
		display: flex;
		gap: 10px;
		align-items: center;
	}

	.tribe-tickets-preset-sale-start select,
	.tribe-tickets-preset-sale-end select,
	.tribe-tickets-preset-sale-start input,
	.tribe-tickets-preset-sale-end input {
		width: auto !important;
	}

	.tribe-tickets-preset-submit {
		margin-top: 30px;
	}

	.required {
		color: #dc3232;
	}
</style>
