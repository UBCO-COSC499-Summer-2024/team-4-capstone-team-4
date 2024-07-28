<section class="dnd-container">
    @vite(['resources/css/dnd.css'])

    <form
        action="{{ route($action) }}"
        method="post" enctype="multipart/form-data" class="drop-zone-form form drop-zone glass dnd-column">
        @csrf
        <label for="file" class="drop-zone-label">
            <span class="material-symbols-outlined drop-zone-icon icon">cloud_upload</span>
            <span class="drop-zone-title">Drop files here or click to upload</span>
            <input type="file" name="files[]" accept="{{ $accept }}" id="file"
                @if ($multiple) multiple @endif
                class="drop-zone-input" />
        </label>

        <div class="drop-zone-content-files dnd-column">
        </div>
        <div class="flex flex-col items-center justify-center">
            <button type="submit" class="max-w-fit import-form-add-button">Save</button>
        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZoneForm = document.querySelector('.drop-zone-form');
        const fileInput = document.querySelector('.drop-zone-input');
        const dropZoneTitle = document.querySelector('.drop-zone-title');
        const fileList = document.querySelector('.drop-zone-content-files');

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
            console.log(files);
            Array.from(files).forEach(file => {
                console.log(file);
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
    });

</script>
