<?php

// Fetch the HTML.
$attendeesHtml = file_get_contents(__DIR__ . '/attendees.html');

// Grab the attendees ul element HTML.
preg_match('#<ul class="tix-attendee-list.*?</ul>#s', $attendeesHtml, $attendeesUlMatches);

// Clear some memory.
unset($attendeesHtml);

// Grab the attendees li elements HTML.
$attendeesLis = explode('</li><li>', $attendeesUlMatches[0]);

// Clear some memory.
unset($attendeesUl);

$attendees = [];

// Loop through the attendees li elements.
foreach ($attendeesLis as $attendeeLi) {
		// Grab the attendee first name.
		preg_match('#<span class="tix-first">(.*?)</span>#', $attendeeLi, $attendeeFirstNameMatches);

		// Grab the attendee last name.
		preg_match('#<span class="tix-last">(.*?)</span>#', $attendeeLi, $attendeeLastNameMatches);

		// Grab the attendee Gravatar.
		preg_match('# src="(.*?)"#', $attendeeLi, $attendeeGravatarMatches);

		$gravatar = str_replace('s=96', '', $attendeeGravatarMatches[1]);
		$gravatar = str_replace('&#038;', '&', $gravatar);
		$gravatar = str_replace('&d=mm&r=g', '&d=404&r=pg', $gravatar);

		// Grab the attendee Twitter.
		// Here is an example of the HTML: <a class="tix-field tix-attendee-twitter" href="http://twitter.com/AaronCampbell">@AaronCampbell</a>
		preg_match('#<a class="tix-field tix-attendee-twitter" href="http://twitter.com/(.*?)">@.*?</a>#', $attendeeLi, $attendeeTwitterMatches);

		// Add the attendee to the attendees array.
		$attendees[] = [
				'name' => $attendeeFirstNameMatches[1] . ' ' . $attendeeLastNameMatches[1],
				'gravatar' => $gravatar,
				'twitter' => $attendeeTwitterMatches[1] ?? null,
		];
}

echo json_encode($attendees);