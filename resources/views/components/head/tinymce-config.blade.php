<script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
    async function resizeImage(image) {
        var canvas = document.createElement("canvas");
        var ctx = canvas.getContext("2d");
        // resize image
        var MAX_WIDTH = 720;
        var MAX_HEIGHT = 720;
        var width = image.width;
        var height = image.height;
        if (width > height) {
            if (width > MAX_WIDTH) {
                height *= MAX_WIDTH / width;
                width = MAX_WIDTH;
            }
        } else {
            if (height > MAX_HEIGHT) {
                width *= MAX_HEIGHT / height;
                height = MAX_HEIGHT;
            }
        }
        canvas.width = width;
        canvas.height = height;

        //return resized image as base64
        ctx.drawImage(image, 0, 0, width, height);
        var dataurl = canvas.toDataURL("image/jpeg", 80);
        return dataurl;
    }

    async function convertImageToBase64(blob) {
        const convertBlobToBase64 = (blob) =>
            new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onerror = reject;
                reader.onload = () => {
                    resolve(reader.result);
                };
                reader.readAsDataURL(blob);
            });
        return convertBlobToBase64(blob);
    }

    tinymce.init({
        selector: 'textarea#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
        statusbar: true,
        menubar: false,
        readonly: true,
        plugins: [
            'code charmap table image imagetools lists fullscreen wordcount'
        ],
        toolbar: 'fullscreen undo redo | styleselect bold italic underline superscript subscript charmap | table image | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code',

        images_upload_handler: async function(blobInfo, success, failure) {
            let base64 = await convertImageToBase64(blobInfo.blob());
            let img = await document.createElement("img");
            img.src = base64;
            await img.complete;
            let dataurl = await resizeImage(img);
            console.log(dataurl);

            let response = await fetch('{{ route('tiny-mce-demo.image-upload') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-type': 'application/json'
                },
                body: JSON.stringify({
                    base64: dataurl,
                    test: 'test'
                })
            });

            let json = await response.json();

            let location = "{{ url('/') }}" + json.image_url
            success(location);
        },
    });
</script>
