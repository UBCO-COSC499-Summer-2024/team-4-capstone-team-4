<section class="dnd-container">
    @vite(['resources/css/dnd.css'])

    <form action="{{ route($route) }}" method="post" enctype="multipart/form-data" class="drop-zone-form form drop-zone glass dnd-column">
        @csrf
        <label for="file" class="drop-zone-label">
            <span class="material-symbols-outlined drop-zone-icon icon">cloud_upload</span>
            <span class="drop-zone-title">Drop files here or click to upload</span>
            <input type="file" name="files[]" accept=".csv" id="file" class="drop-zone-input" multiple>
        </label>

    <div class="drop-zone-content-files dnd-column">
        {{-- @forelse ($files as $file)
            <div class="drop-zone-content-file form-item">
                <span class="file-name">{{ $file['name'] }}</span>
                <div class="right form-group">
                    <span class="file-size">{{ formatSize($file['size']) }}</span>
                    <button class="remove-file" data-file="{{ $file['name'] }}">
                        <span class="material-symbols-outlined icon">delete</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="drop-zone-content-file">
                <span>No files uploaded</span>
            </div>
        @endforelse --}}
    </div>
    <div class="flex flex-col justify-center items-center">
        <button type="submit" class="max-w-fit import-form-add-button disabled:opacity-50">Upload</button>
    </div>
</form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZoneForm = document.querySelector('.drop-zone-form');
        const fileInput = document.querySelector('.drop-zone-input');
        const dropZoneTitle = document.querySelector('.drop-zone-title');
        const fileList = document.querySelector('.drop-zone-content-files');
        const submitButton = document.querySelector('.import-form-add-button');

        submitButton.disabled = true;

        // Drag and drop events
        dropZoneForm.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZoneForm.classList.add('drag-over');
            dropZoneTitle.textContent = 'Drop files here...';
        });

        dropZoneForm.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZoneForm.classList.remove('drag-over');
            dropZoneTitle.textContent = 'Drop files here or click to upload';
        });

        dropZoneForm.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZoneForm.classList.remove('drag-over');
            dropZoneTitle.textContent = 'Drop files here or click to upload';
            handleFiles(e.dataTransfer.files);
        });

        // Handle file input change
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        // Function to handle files
        function handleFiles(files) {
            Array.from(files).forEach(file => {
                if (!isDuplicate(file)) {
                    const fileItem = document.createElement('div');
                    fileItem.classList.add('drop-zone-content-file', 'form-item');

                    const fileName = document.createElement('span');
                    fileName.textContent = file.name;
                    fileName.classList.add('file-name');

                    const fileSize = document.createElement('span');
                    fileSize.textContent = formatSize(file.size);
                    fileSize.classList.add('file-size');

                    const removeButton = document.createElement('button');
                    const icon = document.createElement('span');
                    icon.classList.add('material-symbols-outlined', 'icon');
                    icon.textContent = 'delete';
                    removeButton.appendChild(icon);
                    removeButton.classList.add('remove-file');
                    removeButton.addEventListener('click', () => {
                        fileItem.remove();
                        // Optional: Remove the file from the input element's files list
                        const newFiles = Array.from(fileInput.files).filter(f => f.name !== file.name);
                        // Custom logic to update the input files list can be added here if needed
                        updateSubmitButtonState();
                    });

                    const rightItems = document.createElement('div');
                    rightItems.classList.add('right', 'file-extras');
                    rightItems.appendChild(fileSize);
                    rightItems.appendChild(removeButton);

                    fileItem.appendChild(fileName);
                    fileItem.appendChild(rightItems);
                    fileList.appendChild(fileItem);
                }
            });

            updateSubmitButtonState();
        }

        // Check for duplicate files by name and size
        function isDuplicate(newFile) {
            const existingFiles = fileList.querySelectorAll('.drop-zone-content-file');
            for (let i = 0; i < existingFiles.length; i++) {
                const fileName = existingFiles[i].querySelector('.file-name').textContent;
                const fileSize = existingFiles[i].querySelector('.file-size').textContent;
                if (fileName === newFile.name && fileSize === formatSize(newFile.size)) {
                    return true;
                }
            }
            return false;
        }

        // Optional: Format file size
        function formatSize(bytes) {
            const units = ['B', 'KB', 'MB', 'GB', 'TB'];
            let i = 0;
            while (bytes >= 1024 && i < units.length - 1) {
                bytes /= 1024;
                i++;
            }
            return `${bytes.toFixed(1)} ${units[i]}`;
        }

        function updateSubmitButtonState() {
            const filesPresent = fileList.querySelectorAll('.drop-zone-content-file').length > 0;
            submitButton.disabled = !filesPresent;
        }
    });
</script>
