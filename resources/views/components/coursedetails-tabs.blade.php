<div class="mb-4 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
        <li class="me-2" role="presentation">
            <button class="inline-block p-6 border-b-2 rounded-t-lg ubc-text-blue ubc-border-blue ubc-font-bold text-lg" id="courses-tab" data-tabs-target="#courses" type="button" role="tab" aria-controls="courses" aria-selected="true" onclick="showTab('coursesTable', 'courses-tab')">
                Course Details
            </button>
        </li>
        <li class="me-2" role="presentation">
            <button class="inline-block p-6 border-b-2 rounded-t-lg ubc-text-gray ubc-border-gray ubc-font-bold text-lg" id="tas-tab" data-tabs-target="#tas" type="button" role="tab" aria-controls="tas" aria-selected="false" onclick="showTab('taTable', 'tas-tab')">
                TAs
            </button>
        </li>
    </ul>
</div>

<script>
    function showTab(tabId, tabButtonId) {
        const activeTabInput = document.getElementById('activeTab');
        activeTabInput.value = tabId;
        localStorage.setItem('activeTab', tabId);

        const coursesTable = document.getElementById('coursesTable');
        const taTable = document.getElementById('taTable');
        const editButton = document.getElementById('editButton');
        const createNewButton = document.getElementById('createNewButton');
        const assignButton = document.getElementById('assignButton');
        const assignTAButton = document.getElementById('assignTAButton');
        const createNewTAButton = document.getElementById('createNewTAButton');

        if (tabId === 'coursesTable') {
            coursesTable.classList.remove('hidden');
            taTable.classList.add('hidden');
            editButton.style.display = 'block';
            createNewButton.style.display = 'block';
            assignButton.style.display = 'block';
            assignTAButton.style.display = 'none';
            createNewTAButton.style.display = 'none';
        } else {
            coursesTable.classList.add('hidden');
            taTable.classList.remove('hidden');
            editButton.style.display = 'none';
            createNewButton.style.display = 'none';
            assignButton.style.display = 'none';
            assignTAButton.style.display = 'block';
            createNewTAButton.style.display = 'block';
        }

        document.querySelectorAll('button[data-tabs-target]').forEach(function (btn) {
            btn.classList.remove('text-ubc-blue', 'border-tab-ubc-blue', 'font-bold');
            btn.classList.add('text-gray-600-font-bold', 'border-gray-300');
        });

        const activeTabButton = document.getElementById(tabButtonId);
        activeTabButton.classList.add('text-ubc-blue', 'border-tab-ubc-blue', 'font-bold');
        activeTabButton.classList.remove('text-gray-600-font-bold', 'border-gray-300');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const activeTab = localStorage.getItem('activeTab') || 'coursesTable';
        if (activeTab === 'taTable') {
            showTab('taTable', 'tas-tab');
        } else {
            showTab('coursesTable', 'courses-tab');
        }
    });

    document.getElementById('assignButton').addEventListener('click', function() {
        const route = this.getAttribute('data-route');
        console.log('Navigating to route:', route);
        document.location.href = "/assign-courses";
    });
</script>
