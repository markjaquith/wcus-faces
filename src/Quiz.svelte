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
	<p class="text-gray-500 mt-4">Select some people you’d like to be quizzed on, and then tap “Start Quiz”</p>

	<Button on:click={() => playing = true} disabled={quizAttendees.length < 1}>Start Quiz{quizAttendees.length ? ` (${quizAttendees.length})` : ''}</Button>

	<ul class="flex flex-wrap">
		{#each attendees as attendee}
			<li>
				<Attendee gravatar={attendee.gravatar} size=80 on:click={() => toggle(attendee)} />
			</li>
		{/each}
	</ul>

	<Button on:click={() => playing = true} disabled={quizAttendees.length < 1}>Start Quiz{quizAttendees.length ? ` (${quizAttendees.length})` : ''}</Button>
{/if}

{#if playing && currentAttendee}
	<Button on:click={() => playing = false}>Start Over</Button>
	<div>
		<div>
			<button on:click={advance} class="hover:cursor-hand">
				<img width=512 height=512 class="block w-[512px] h-[512px]" alt={showingName ? currentAttendee.name : ''} src={currentAttendee.gravatar + '&s=512'} />
			</button>
		</div>
		{#if showingName}
			<div class="font-bold">
			{currentAttendee.name}
			</div>
			{#if currentAttendee.twitter}
				<div>
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
