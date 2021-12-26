<form method="post" action="{{ route('tiny-mce-demo.store') }}">
    @csrf
    <textarea id="myeditorinstance">Hello, World!</textarea>
</form>
