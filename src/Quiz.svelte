<script>
import { onMount } from 'svelte'
import Attendee from './Attendee.svelte'
import Button from './Button.svelte'
import arrayShuffle from 'array-shuffle'
export let attendees = []
let quizAttendees = []
let currentAttendee = null
let showingName = false
let playing = false

$: if (playing) {
	chooseRandomAttendee()
}

$: if (!playing) {
	quizAttendees = []
	showingName = false
}

function chooseRandomAttendee() {
	let randomIndex
	do {
		randomIndex = Math.floor(Math.random() * quizAttendees.length)
	} while (currentAttendee === quizAttendees[randomIndex] && quizAttendees.length > 1)

	currentAttendee = quizAttendees[randomIndex]
}

function toggle(attendee) {
	if (quizAttendees.includes(attendee)) {
		quizAttendees = quizAttendees.filter(a => a !== attendee)
	} else {
		quizAttendees.push(attendee)
	}
	quizAttendees = quizAttendees
	console.log(quizAttendees)
}

function advance() {
	showingName = !showingName
	if (!showingName) {
		chooseRandomAttendee()
	}
}

onMount(() => {
	attendees = arrayShuffle(attendees)
})
</script>

{#if !playing}
	<p class="text-gray-500 my-4">Select some faces, then tap “Start Quiz”:</p>

	<ul class="flex flex-wrap">
		{#each attendees as attendee}
			<li>
				<Attendee gravatar={attendee.gravatar} size=80 on:click={() => toggle(attendee)} />
			</li>
		{/each}
	</ul>
	{#if quizAttendees.length}
		<div class="floating-button">
			<Button on:click={() => playing = true} disabled={quizAttendees.length < 1}>Start Quiz{quizAttendees.length ? ` (${quizAttendees.length})` : ''}</Button>
		</div>
	{/if}
{/if}

{#if playing && currentAttendee}
	<Button on:click={() => playing = false}>Restart</Button>
	<div>
		<div>
			<button on:click={advance} class="hover:cursor-hand">
				<img width=512 height=512 class="block w-[512px] h-auto" alt={showingName ? currentAttendee.name : ''} src={currentAttendee.gravatar + '&s=512'} />
			</button>
		</div>
		{#if showingName}
			<div class="font-bold text-xl">
			{currentAttendee.name}
			</div>
			{#if currentAttendee.twitter}
				<div class="text-lg text-gray-600">
					@{currentAttendee.twitter}
				</div>
			{/if}
		{/if}
	</div>
{/if}

{#if quizAttendees}
	{#each quizAttendees as attendee}
		<img class="hidden" alt="" src={attendee.gravatar + '&s=512'} />
	{/each}
{/if}

<style>
.floating-button :global(button) {
	position: fixed;
	bottom: 1.5rem;
	transform: translateX(-50%);
	box-shadow: 0 0 75px 40px rgba(0, 0, 0, 0.75);
}
</style>