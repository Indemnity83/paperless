<x-app-layout>
    <div class="h-full">
        <div class=" mx-auto xl:grid xl:grid-cols-3 h-full">
            <div class="xl:col-span-2 p-4 lg:p-6 bg-gray-200 overflow-y-hidden">

                <nav class="pb-4 lg:pb-6 flex items-center justify-between border-gray-200 sm:px-0" aria-label="Pagination">
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-700">
                            Showing page
                            <span class="font-medium" id="page_num"></span>
                            of
                            <span class="font-medium" id="page_count"></span>
                        </p>
                    </div>
                    <div class="flex-1 flex justify-between sm:justify-end">
                        <button id="prev" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </button>
                        <button id="next" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </button>
                    </div>
                </nav>

                <div class="h-full overflow-y-auto">
                    <canvas class="bg-white w-full shadow" id="pdf-canvas"></canvas>
                </div>

                <script type="text/javascript">
                    var url = '{{ \Storage::disk('media')->url($document->attachment) }}',
                        pdfDoc = null,
                        pageNum = 1,
                        pageRendering = false,
                        pageNumPending = null,
                        scale = 1.0,
                        canvas = document.getElementById('pdf-canvas'),
                        ctx = canvas.getContext('2d');

                    // The workerSrc property shall be specified.
                    pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';

                    /**
                     * Get page info from document, resize canvas accordingly, and render page.
                     * @param num Page number.
                     */
                    function renderPage(num) {
                        pageRendering = true;
                        // Using promise to fetch the page
                        pdfDoc.getPage(num).then(function(page) {
                            var viewport = page.getViewport({ scale: scale, });
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            // Render PDF page into canvas context
                            var renderContext = {
                                canvasContext: ctx,
                                viewport: viewport,
                            };
                            var renderTask = page.render(renderContext);

                            // Wait for rendering to finish
                            renderTask.promise.then(function () {
                                pageRendering = false;
                                if (pageNumPending !== null) {
                                    // New page rendering is pending
                                    renderPage(pageNumPending);
                                    pageNumPending = null;
                                }
                            });
                        });

                        // Update page counters
                        document.getElementById('page_num').textContent = num;
                    }

                    /**
                     * If another page rendering in progress, waits until the rendering is
                     * finised. Otherwise, executes rendering immediately.
                     */
                    function queueRenderPage(num) {
                        if (pageRendering) {
                            pageNumPending = num;
                        } else {
                            renderPage(num);
                        }
                    }

                    /**
                     * Displays previous page.
                     */
                    function onPrevPage() {
                        if (pageNum <= 1) {
                            return;
                        }
                        pageNum--;
                        queueRenderPage(pageNum);
                    }
                    document.getElementById('prev').addEventListener('click', onPrevPage);

                    /**
                     * Displays next page.
                     */
                    function onNextPage() {
                        if (pageNum >= pdfDoc.numPages) {
                            return;
                        }
                        pageNum++;
                        queueRenderPage(pageNum);
                    }
                    document.getElementById('next').addEventListener('click', onNextPage);

                    /**
                     * Asynchronously downloads PDF.
                     */
                    var loadingTask = pdfjsLib.getDocument(url);
                    loadingTask.promise.then(function(pdfDoc_) {
                        pdfDoc = pdfDoc_;
                        document.getElementById('page_count').textContent = pdfDoc.numPages;

                        // Initial/first page rendering
                        renderPage(pageNum);
                    });
                </script>

            </div>
            <aside class="hidden xl:block xl:pl-8 py-8 xl:py-10 px-4 sm:px-6 lg:px-8">
                <h2 class="sr-only">Details</h2>
                <div class="space-y-5">
                    <div class="flex items-center space-x-2">
                        <!-- Heroicon name: chat-alt -->
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z" />
                            <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                        </svg>
                        <span class="text-gray-900 text-sm font-medium">{{ $document->pages }} {{ \Str::plural('page', $document->pages)  }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                            <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-900 text-sm font-medium">{{ \FileHelper::bytesForHumans($document->attachment_size) }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Heroicon name: calendar -->
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-900 text-sm font-medium">
                            <time datetime="{{ $document->created_at->toAtomString() }}">{{ $document->created_at->diffForHumans() }}</time>
                        </span>
                    </div>
                    <div class="">
                        @foreach($document->details as $term => $detail)
                            <p class="text-xs font-medium mb-2">
                                <span class="text-gray-400">{{ $term }}:</span> <span class="text-gray-600">{{ $detail }}</span>
                            </p>
                        @endforeach
                    </div>
                </div>
                <div class="mt-6 border-t border-gray-200 py-6 space-y-8">
                    <div>
                        <h2 class="text-sm font-medium text-gray-500">Tags</h2>
                        <ul class="mt-2 leading-8">
                            <li class="inline">
                                <a href="#" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Tag
                                </a>
                            </li>
                            <li class="inline">
                                <a href="#" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Tag
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
