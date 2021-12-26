<form method="post" action="{{ route('tiny-mce-demo.store') }}">
    @csrf
    <textarea id="myeditorinstance" name="description">Hello, World!</textarea>
    <button type="submit"
        class="mt-4 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Save</button>
</form>
