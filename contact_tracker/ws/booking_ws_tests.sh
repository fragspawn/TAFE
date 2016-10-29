#!/bin/bash
echo 'WEB SERVICE REQUEST TESTS'
curl http://localhost/~jp/formsubmit/ws/bookings_ws.php?ws_venue=ALL
echo ''
curl http://localhost/~jp/formsubmit/ws/bookings_ws.php?venue=HTML5
echo ''
curl http://localhost/~jp/formsubmit/ws/bookings_ws.php?ws_venue=HTML5
echo ''
curl http://localhost/~jp/formsubmit/ws/bookings_ws.php?ws_bookings_for_event=1
echo ''
curl --data "first_name=asdf&last_name=asdf&email_addr=asdf@asdf.com&phone_number=123412341234&event_id=1" http://localhost/~jp/formsubmit/ws.php
echo ''
curl --data "event_name=asdf&event_location=asdf&event_datetime=2016-12-12-8-45-45&event_length=2&event_capacity=10" http://localhost/~jp/formsubmit/ws.php
echo ''
