## Event Tickets Test Data Generator

This extension aims to provide an automated tool to generate high quality, life-like data for the Event Tickets suite of plugins.

### Setting it up

Setting up the extension is simple, just install it and activate it on your WordPress site, alongside Event Tickets (v5.5.10+) and The Events Calendar (v6.0.11+).

You can access all the available features in `Tickets > Test Data`.

#### What you can do:

1. **Create Tickets for an existing event:** you can automatically generate any specified number of tickets for a specific event ID.
1. **Create Attendees for a specific RSVP:** you can automatically generate any specified number of RSVP attendeees for a specific RSVP ticket ID.
1. **Create Attendees for a specific Ticket:** you can automatically generate any specified number of Ticket attendeees for a specific Ticket ID.

###### PLEASE NOTE that the amount of RSVPs, Tickets and Attendees you can create might be limited by your site's server setup (Available memory, timeout, processing power, etc.)

#### WP-CLI Support

Example:
```bash
wp et-test-data rsvps generate 5 --eventid=95
wp et-test-data rsvps generate 7 --eventid=99 --capacity=50
wp et-test-data rsvps generate 2 --eventid=86 --capacity=20 --stock=10
wp et-test-data rsvps generate 1 --eventid=72 --unlimitedcapacity
wp et-test-data tickets generate 4 --eventid=104
wp et-test-data tickets generate 7 --eventid=42 --capacity=25 --stock=5
wp et-test-data tickets generate 23 --eventid=80 --capacity=50 --sharedcapacity
wp et-test-data attendees generate 10 --ticket-id=88
```
The commands above will:
* (1) Generate 5 RSVPs for Event ID 95 (Capacity assigned randomly).
* (2) Generate 7 RSVPs for Event ID 99, each RSVP with a capacity of 50.
* (3) Generate 2 RSVPs for Event ID 86, each RSVP with a capacity of 20 and a stock of 10.
* (4) Generate 1 RSVP for Event ID 72, with unlimited capacity.
* (5) Generate 4 Tickets for Event ID 104 (Capacity assigned randomly).
* (6) Generate 7 Tickets for Event ID 42, each Ticket with a capacity of 25 and a stock of 5.
* (7) Generate 23 Tickets for Event ID 80, each Ticket with Shared capacity of 50 across tickets.
* (8) Generate 10 Attendees for Ticket ID 88.

To specify a value for each available option via the built-in prompt, use:
```bash
wp et-test-data tickets generate --prompt 
```

For a full list of supported capabilities, check out src/Tribe/Cli/Command.php 
