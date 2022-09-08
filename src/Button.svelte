<script>
export let disabled = false
</script>

<button {disabled} class="bg-blue-500 hover:bg-blue-700 disabled:opacity-25 text-white font-bold py-2 px-4 rounded my-3" on:click><slot /></button>