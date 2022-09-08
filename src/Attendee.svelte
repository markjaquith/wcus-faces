<script>
export let size = 48
export let gravatar
let show = true
let selected = false
</script>

{#if show}
	<img data-selected={selected} width={size} height={size} class="block" alt="" src={gravatar + '&s=' + size} on:error|preventDefault={() => show = false} on:click={() => selected = !selected} on:click />
{/if}

<style>
	img {
		border: 4px solid white;
		margin: 2px;
		opacity: 0.8;
	}
	img:hover {
		cursor: pointer;
	}
	img[data-selected=true] {
		border: 4px solid green;
		opacity: 1;
	}
</style>