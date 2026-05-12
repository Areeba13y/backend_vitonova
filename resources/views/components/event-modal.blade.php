<!-- Event Form Modal -->
<div id="eventModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative w-full max-w-lg mx-4">
        <div class="bg-white rounded-lg shadow-xl">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 id="eventModalTitle" class="text-lg font-semibold text-gray-800">Add Event</h3>
                <button id="closeEventModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6">
                <form id="eventForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" id="eventMethodField" name="_method" value="POST">
                    <input type="hidden" id="eventId" name="event_id">

                    <div>
                        <label for="eventImage" class="block text-sm font-medium text-gray-700 mb-1">Event Image *</label>
                        <input id="eventImage" type="file" name="image" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <img id="currentEventImage" src="" alt="" class="mt-2 w-full h-32 object-cover rounded-lg hidden">
                        <span id="imageError" class="text-red-500 text-xs hidden"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="eventCategory" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                            <input id="eventCategory" type="text" name="category" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span id="categoryError" class="text-red-500 text-xs hidden"></span>
                        </div>

                        <div>
                            <label for="eventTitle" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input id="eventTitle" type="text" name="title" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span id="titleError" class="text-red-500 text-xs hidden"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="eventSubmissionDeadline" class="block text-sm font-medium text-gray-700 mb-1">Submission Deadline *</label>
                            <input id="eventSubmissionDeadline" type="date" name="submission_deadline" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="eventDate" class="block text-sm font-medium text-gray-700 mb-1">Event Date *</label>
                            <input id="eventDate" type="date" name="event_date" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label for="eventDescription" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                        <textarea id="eventDescription" name="description" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                        <span id="descriptionError" class="text-red-500 text-xs hidden"></span>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <button type="button" id="cancelEventBtn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">Cancel</button>
                        <button type="submit" id="eventSubmitBtn" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 text-sm font-medium">
                            <span id="eventSubmitText">Save Event</span>
                            <span id="eventSubmitLoading" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Event Detail Modal -->
<div id="eventDetailModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative w-full max-w-lg mx-4">
        <div class="bg-white rounded-lg shadow-xl">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Event Details</h3>
                <button id="closeEventDetailModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6">
                <img id="detailEventImage" class="w-full h-48 object-cover rounded-lg mb-4" alt="Event Image">
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Category</p>
                        <p id="detailEventCategory" class="text-gray-900 font-medium"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Title</p>
                        <p id="detailEventTitle" class="text-gray-900 font-semibold text-lg"></p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Deadline</p>
                            <p id="detailEventSubmissionDeadline" class="text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Event Date</p>
                            <p id="detailEventDate" class="text-gray-900"></p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Description</p>
                        <p id="detailEventDescription" class="text-gray-900 whitespace-pre-line"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const routes = {
        eventStore: '{{ route("events.store") }}',
        eventUpdate: '{{ route("events.update", ":id") }}'
    };

    const eventModal = document.getElementById('eventModal');
    const eventDetailModal = document.getElementById('eventDetailModal');
    const eventForm = document.getElementById('eventForm');
    const eventModalTitle = document.getElementById('eventModalTitle');
    const eventSubmitBtn = document.getElementById('eventSubmitBtn');
    const eventSubmitText = document.getElementById('eventSubmitText');
    const eventSubmitLoading = document.getElementById('eventSubmitLoading');
    const currentEventImage = document.getElementById('currentEventImage');
    const eventImageInput = document.getElementById('eventImage');

    function closeEventModal() {
        eventModal.classList.add('hidden');
        resetEventForm();
    }

    function closeEventDetailModal() {
        eventDetailModal.classList.add('hidden');
    }

    function resetEventForm() {
        eventForm.reset();
        document.getElementById('eventMethodField').value = 'POST';
        document.getElementById('eventId').value = '';
        currentEventImage.classList.add('hidden');
        currentEventImage.src = '';
    }

    document.getElementById('closeEventModal').addEventListener('click', closeEventModal);
    document.getElementById('cancelEventBtn').addEventListener('click', closeEventModal);
    document.getElementById('closeEventDetailModal').addEventListener('click', closeEventDetailModal);

    eventModal.addEventListener('click', function(e) {
        if (e.target === eventModal) closeEventModal();
    });

    eventDetailModal.addEventListener('click', function(e) {
        if (e.target === eventDetailModal) closeEventDetailModal();
    });

    eventForm.addEventListener('submit', function(e) {
        e.preventDefault();
        eventSubmitBtn.disabled = true;
        eventSubmitText.classList.add('hidden');
        eventSubmitLoading.classList.remove('hidden');

        const formData = new FormData(eventForm);
        const eventId = document.getElementById('eventId').value;
        const isEdit = eventId !== '';
        const url = isEdit ? routes.eventUpdate.replace(':id', eventId) : routes.eventStore;

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Toast.fire({ icon: 'success', title: data.message });
                closeEventModal();
                if (typeof $('#eventsTable') !== 'undefined') {
                    $('#eventsTable').DataTable().ajax.reload();
                }
            }
        })
        .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to save event' });
        })
        .finally(() => {
            eventSubmitBtn.disabled = false;
            eventSubmitText.classList.remove('hidden');
            eventSubmitLoading.classList.add('hidden');
        });
    });

    window.openAddEventModal = function() {
        resetEventForm();
        eventModalTitle.textContent = 'Add Event';
        eventSubmitText.textContent = 'Save Event';
        eventModal.classList.remove('hidden');
    };

    window.editEvent = function(button) {
        const event = JSON.parse(button.dataset.event);
        resetEventForm();
        
        eventModalTitle.textContent = 'Edit Event';
        eventSubmitText.textContent = 'Update Event';
        document.getElementById('eventMethodField').value = 'PUT';
        document.getElementById('eventId').value = event.id;
        document.getElementById('eventCategory').value = event.category || '';
        document.getElementById('eventTitle').value = event.title || '';
        document.getElementById('eventDescription').value = event.description || '';
        document.getElementById('eventSubmissionDeadline').value = event.submission_deadline || '';
        document.getElementById('eventDate').value = event.event_date || '';

        if (event.image_url) {
            currentEventImage.src = event.image_url;
            currentEventImage.classList.remove('hidden');
        }

        eventModal.classList.remove('hidden');
    };

    window.viewEvent = function(button) {
        const event = JSON.parse(button.dataset.event);
        document.getElementById('detailEventImage').src = event.image_url || '';
        document.getElementById('detailEventCategory').textContent = event.category || '-';
        document.getElementById('detailEventTitle').textContent = event.title || '-';
        document.getElementById('detailEventSubmissionDeadline').textContent = event.submission_deadline || '-';
        document.getElementById('detailEventDate').textContent = event.event_date || '-';
        document.getElementById('detailEventDescription').textContent = event.description || '-';
        eventDetailModal.classList.remove('hidden');
    };
});
</script>
