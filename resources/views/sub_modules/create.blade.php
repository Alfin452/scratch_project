<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Subbab (Materi)') }} - Bab {{ $module->order }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-8">

                    <form action="{{ route('sub_modules.store', $module->id) }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="col-span-3">
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul Subbab</label>
                                <input type="text" name="title" id="title" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition"
                                    placeholder="Contoh: Pengenalan Tipe Data">
                            </div>
                            <div class="col-span-1">
                                <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Urutan</label>
                                <input type="number" name="order" id="order" required value="{{ $nextOrder }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition text-center"
                                    placeholder="1">
                            </div>
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Konten Pembelajaran (Materi)</label>
                            <textarea name="content" id="content" class="hidden"></textarea>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('modules.show', $module->id) }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition">
                                Batal
                            </a>
                            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-900 transition shadow-lg hover:shadow-indigo-500/30">
                                Simpan Materi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- CKEditor Configuration (Bisa di-include dari file terpisah agar rapi jika mau) --}}
    <style>
        .ck-editor__editable_inline { min-height: 400px; }
        .dark .ck-editor__editable { background-color: #1f2937 !important; color: #d1d5db !important; border-color: #374151 !important; }
        .spasi-normal { line-height: normal !important; }
        .spasi-1-15 { line-height: 1.15 !important; }
        .spasi-1-5 { line-height: 1.5 !important; }
        .spasi-2 { line-height: 2 !important; }
        .dark .ck.ck-editor__main>.ck-editor__editable h2,
        .dark .ck.ck-editor__main>.ck-editor__editable h3,
        .dark .ck.ck-editor__main>.ck-editor__editable p { color: #f3f4f6 !important; }
        .dark .ck.ck-toolbar { background-color: #1f2937 !important; border-color: #4B5563 !important; }
        .dark .ck.ck-button { color: #D1D5DB !important; }
        .dark .ck.ck-button:hover { background-color: #4B5563 !important; }
        .dark .ck.ck-button.ck-on { background-color: #4f46e5 !important; color: white !important; }
    </style>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/super-build/ckeditor.js"></script>
    <script>
        class MyUploadAdapter {
            constructor(loader) { this.loader = loader; }
            upload() {
                return this.loader.file
                    .then(file => new Promise((resolve, reject) => {
                        this._initRequest();
                        this._initListeners(resolve, reject, file);
                        this._sendRequest(file);
                    }));
            }
            abort() { if (this.xhr) { this.xhr.abort(); } }
            _initRequest() {
                const xhr = this.xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('modules.uploadImage') }}', true);
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                xhr.responseType = 'json';
            }
            _initListeners(resolve, reject, file) {
                const xhr = this.xhr;
                const loader = this.loader;
                const genericErrorText = `Couldn't upload file: ${file.name}.`;
                xhr.addEventListener('error', () => reject(genericErrorText));
                xhr.addEventListener('abort', () => reject());
                xhr.addEventListener('load', () => {
                    const response = xhr.response;
                    if (!response || response.error) {
                        return reject(response && response.error ? response.error.message : genericErrorText);
                    }
                    resolve({ default: response.url });
                });
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', evt => {
                        if (evt.lengthComputable) {
                            loader.uploadTotal = evt.total;
                            loader.uploaded = evt.loaded;
                        }
                    });
                }
            }
            _sendRequest(file) {
                const data = new FormData();
                data.append('upload', file);
                this.xhr.send(data);
            }
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }

        CKEDITOR.ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: {
                    items: [
                        'heading', '|', 'style', '|', 'bold', 'italic', 'strikethrough', 'underline', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|', 'bulletedList', 'numberedList', 'outdent', 'indent', '|',
                        'uploadImage', 'blockQuote', 'insertTable', 'link', '|', 'undo', 'redo'
                    ],
                    shouldNotGroupWhenFull: true
                },
                style: {
                    definitions: [
                        { name: 'Spasi Normal', element: 'p', classes: ['spasi-normal'] },
                        { name: 'Spasi 1.15', element: 'p', classes: ['spasi-1-15'] },
                        { name: 'Spasi 1.5', element: 'p', classes: ['spasi-1-5'] },
                        { name: 'Spasi 2.0 (Double)', element: 'p', classes: ['spasi-2'] }
                    ]
                },
                removePlugins: [
                    'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges', 
                    'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData', 
                    'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 'SlashCommand', 'Template', 'DocumentOutline', 
                    'FormatPainter', 'TableOfContents', 'PasteFromOfficeEnhanced', 'CaseChange'
                ],
                extraPlugins: [MyCustomUploadAdapterPlugin]
            })
            .catch(error => { console.error(error); });
    </script>
</x-app-layout>
