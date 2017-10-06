#!/bin/bash
echo 'WEB SERVICE REQUEST TESTS'
echo 'list all venues'
echo ''
curl http://localhost/~jp/TAFE/form_submit/ws/bookings_ws.php?ws_venue=ALL
echo ''
echo 'List Venue details'
curl http://localhost/~jp/TAFE/form_submit/ws/bookings_ws.php?venue=HTML5
echo ''
echo 'j'
curl http://localhost/~jp/TAFE/form_submit/ws/bookings_ws.php?ws_venue=HTML5
echo ''
echo 'List bookings for event ID'
curl http://localhost/~jp/TAFE/form_submit/ws/bookings_ws.php?ws_bookings_for_event=1
echo ''
echo 'Delete all bookings with this e-mail'
curl --data "email_addr=email@email.com" http://localhost/~jp/TAFE/form_submit/ws.php
echo ''
echo 'Book an event on eventID'
curl --data "first_name=asdf&last_name=asdf&email_addr=asdf@asdf.com&phone_number=123412341234&event_id=1" http://localhost/~jp/TAFE/form_submit/ws.php
echo ''
echo 'Insert a new event'
curl --data "event_name=asdf&event_location=asdf&event_datetime=2016-12-12-8-45-45&event_length=2&event_capacity=10" http://localhost/~jp/TAFE/form_submit/ws.php
echo ''
