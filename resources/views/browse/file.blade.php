<div>
    <div class="mb-5 px-3 sm:px-0">
        <livewire:browse.breadcrumbs :object="$object" />
    </div>

    <script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>
    <div id="pdfDocument" class="divide-y-8 divide-gray-200"></div>


    <script id="script">
        //
        // If absolute URL from the remote server is provided, configure the CORS
        // header on that server.
        //
        var url = '/files/{{ $object->item->id }}/download';

        //
        // Loaded via <script> tag, create shortcut to access PDF.js exports.
        //
        var pdfjsLib = window['pdfjs-dist/build/pdf'];

        //
        // The workerSrc property shall be specified.
        //
        pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';

        var currPage = 1; //Pages are 1-based not 0-based
        var numPages = 0;
        var pdf = null;

        //
        // Asynchronous download PDF
        //
        var loadingTask = pdfjsLib.getDocument(url);
        loadingTask.promise.then(function(doc) {
            pdf = doc;
            numPages = doc.numPages;
            doc.getPage(1).then( handlePages );
        });

        function handlePages(page) {
            var scale = 1.5;
            var viewport = page.getViewport({ scale: scale, });

            //
            // Prepare canvas using PDF page dimensions
            //
            var canvas = document.createElement( 'canvas' );
            canvas.classList.add('w-full');
            // canvas.classList.add('border-b-4');
            // canvas.classList.add('border-grey-500');
            var context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            //
            // Render PDF page into canvas context
            //
            var renderContext = {
                canvasContext: context,
                viewport: viewport,
            };
            page.render(renderContext);

            panel = document.getElementById('pdfDocument')
            panel.appendChild( canvas );

            currPage++;
            if ( pdf !== null && currPage <= numPages )
            {
                pdf.getPage( currPage ).then( handlePages );
            }
        }
    </script>
</div>
