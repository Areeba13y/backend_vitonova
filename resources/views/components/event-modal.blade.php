<!-- Event Form Modal -->
<div id="eventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 id="eventModalTitle" class="text-lg font-semibold text-gray-900">Add New Event</h3>
                <button id="closeEventModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-4">
                <form id="eventForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="eventMethodField" name="_method" value="POST">
                    <input type="hidden" id="eventId" name="event_id">

                    <div class="mb-4">
                        <label for="eventImage" class="block text-sm font-medium text-gray-700 mb-2">
                            Event Image <span id="eventImageRequired">*</span>
                        </label>
                        <div class="relative rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-4 hover:border-green-400 hover:bg-green-50 transition-colors">
                            <input id="eventImage" type="file" name="image" accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16l4-4a3 3 0 014.243 0L15 15.757a3 3 0 004.243 0L21 14m-8-8h.01M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">Choose event image</p>
                                        <p class="text-xs text-gray-500">JPG, PNG, WEBP up to 2MB</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1.5 rounded-md text-xs font-medium bg-white border border-gray-200 text-gray-700">Browse</span>
                            </div>
                            <p id="eventImageName" class="mt-3 text-xs text-gray-500 truncate">No file selected</p>
                        </div>
                        <span id="imageError" class="text-red-500 text-sm hidden"></span>
                        <div id="eventImagePreviewWrap" class="mt-4 hidden">
                            <button type="button" id="openPreviewImageBtn" class="group relative block w-full max-w-xs text-left">
                                <img id="currentEventImage" class="w-full h-44 rounded-xl object-cover border border-gray-200 shadow-sm" alt="Current Event Image">
                                <span class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent text-white text-xs px-3 py-2 rounded-b-xl opacity-0 group-hover:opacity-100 transition-opacity">
                                    Click to view full image
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="eventCategory" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <input id="eventCategory" type="text" name="category" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <span id="categoryError" class="text-red-500 text-sm hidden"></span>
                        </div>

                        <div class="mb-4">
                            <label for="eventTitle" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input id="eventTitle" type="text" name="title" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <span id="titleError" class="text-red-500 text-sm hidden"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="eventSubmissionDeadline" class="block text-sm font-medium text-gray-700 mb-2">Submission Deadline</label>
                            <input id="eventSubmissionDeadline" type="date" name="submission_deadline" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <span id="submission_deadlineError" class="text-red-500 text-sm hidden"></span>
                        </div>

                        <div class="mb-4">
                            <label for="eventDate" class="block text-sm font-medium text-gray-700 mb-2">Event Date</label>
                            <input id="eventDate" type="date" name="event_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <span id="event_dateError" class="text-red-500 text-sm hidden"></span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="eventDescription" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="eventDescription" name="description" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                        <span id="descriptionError" class="text-red-500 text-sm hidden"></span>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" id="cancelEventBtn" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="eventSubmitBtn" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                            <span id="eventSubmitText">Create Event</span>
                            <span id="eventSubmitLoading" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Event Detail Modal -->
<div id="eventDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-16 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Event Details</h3>
            <button id="closeEventDetailModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mt-4">
            <img id="detailEventImage" class="w-full h-64 object-cover rounded-lg border border-gray-200 mb-4 cursor-zoom-in" alt="Event Image">
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Category</p>
                    <p id="detailEventCategory" class="text-gray-900 font-medium"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Title</p>
                    <p id="detailEventTitle" class="text-gray-900 font-semibold text-lg"></p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Submission Deadline</p>
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

<!-- Full Image Viewer -->
<div id="imageViewerModal" class="fixed inset-0 bg-black/90 z-[70] hidden">
    <button type="button" id="closeImageViewerModal" class="absolute top-4 right-4 text-white/80 hover:text-white">
        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    <div class="w-full h-full flex items-center justify-center p-6">
        <img id="imageViewerPreview" src="" alt="Full Preview" class="max-w-full max-h-full object-contain rounded-lg">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const routes = {
        eventStore: '{{ route("events.store") }}',
        eventUpdate: '{{ route("events.update", ":id") }}',
        eventDestroy: '{{ route("events.destroy", ":id") }}'
    };

    const eventModal = document.getElementById('eventModal');
    const eventDetailModal = document.getElementById('eventDetailModal');
    const eventForm = document.getElementById('eventForm');
    const eventModalTitle = document.getElementById('eventModalTitle');
    const eventSubmitBtn = document.getElementById('eventSubmitBtn');
    const eventSubmitText = document.getElementById('eventSubmitText');
    const eventSubmitLoading = document.getElementById('eventSubmitLoading');
    const eventsTableBody = document.getElementById('eventsTableBody');
    const eventImageInput = document.getElementById('eventImage');
    const currentEventImage = document.getElementById('currentEventImage');
    const eventImageName = document.getElementById('eventImageName');
    const eventImagePreviewWrap = document.getElementById('eventImagePreviewWrap');
    const openPreviewImageBtn = document.getElementById('openPreviewImageBtn');
    const imageViewerModal = document.getElementById('imageViewerModal');
    const closeImageViewerModal = document.getElementById('closeImageViewerModal');
    const imageViewerPreview = document.getElementById('imageViewerPreview');

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
        eventImageInput.required = true;
        document.getElementById('eventImageRequired').textContent = '*';
        eventImageName.textContent = 'No file selected';
        eventImagePreviewWrap.classList.add('hidden');
        currentEventImage.src = '';
        clearEventErrors();
    }

    function previewSelectedImage(file) {
        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            eventImageName.textContent = file.name;
            currentEventImage.src = e.target.result;
            eventImagePreviewWrap.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function openImageViewer(src) {
        if (!src) {
            return;
        }
        imageViewerPreview.src = src;
        imageViewerModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeViewer() {
        imageViewerModal.classList.add('hidden');
        imageViewerPreview.src = '';
        document.body.classList.remove('overflow-hidden');
    }

    function clearEventErrors() {
        ['image', 'category', 'title', 'description', 'submission_deadline', 'event_date'].forEach(function(field) {
            const input = document.querySelector('[name="' + field + '"]');
            const error = document.getElementById(field + 'Error');
            if (input) {
                input.classList.remove('border-red-500');
            }
            if (error) {
                error.classList.add('hidden');
                error.textContent = '';
            }
        });
    }

    function showEventErrors(errors) {
        clearEventErrors();
        Object.keys(errors).forEach(function(field) {
            const input = document.querySelector('[name="' + field + '"]');
            const error = document.getElementById(field + 'Error');
            if (input) {
                input.classList.add('border-red-500');
            }
            if (error) {
                error.textContent = errors[field][0];
                error.classList.remove('hidden');
            }
        });
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function eventRowTemplate(event) {
        const jsonData = JSON.stringify(event).replace(/"/g, '&quot;');

        return `
            <tr class="hover:bg-gray-50" id="event-row-${event.id}" data-event-id="${event.id}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <img src="${escapeHtml(event.image_url)}" alt="${escapeHtml(event.title)}" class="w-16 h-12 object-cover rounded border border-gray-200">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(event.category)}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">${escapeHtml(event.title)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml(event.submission_deadline)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml(event.event_date)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <button onclick="viewEvent(this)" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors" title="View Event" data-event="${jsonData}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        <button onclick="editEvent(this)" class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-50 transition-colors" title="Edit Event" data-event="${jsonData}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick='deleteEvent(${event.id}, ${JSON.stringify(event.title)})' class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors" title="Delete Event">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    function upsertEventRow(event) {
        const existingRow = document.getElementById('event-row-' + event.id);
        const rowHtml = eventRowTemplate(event);
        const emptyRow = document.getElementById('empty-events-row');

        if (emptyRow) {
            emptyRow.remove();
        }

        if (existingRow) {
            existingRow.outerHTML = rowHtml;
        } else {
            eventsTableBody.insertAdjacentHTML('afterbegin', rowHtml);
        }
    }

    function removeEventRow(eventId) {
        const row = document.getElementById('event-row-' + eventId);
        if (row) {
            row.remove();
        }

        const rows = eventsTableBody.querySelectorAll('tr[data-event-id]');
        if (rows.length === 0) {
            eventsTableBody.innerHTML = '<tr id="empty-events-row"><td colspan="6" class="px-6 py-10 text-center text-gray-500">No events found.</td></tr>';
        }
    }

    document.getElementById('closeEventModal').addEventListener('click', closeEventModal);
    document.getElementById('cancelEventBtn').addEventListener('click', closeEventModal);
    document.getElementById('closeEventDetailModal').addEventListener('click', closeEventDetailModal);

    eventModal.addEventListener('click', function(e) {
        if (e.target === eventModal) {
            closeEventModal();
        }
    });

    eventDetailModal.addEventListener('click', function(e) {
        if (e.target === eventDetailModal) {
            closeEventDetailModal();
        }
    });

    eventImageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            previewSelectedImage(this.files[0]);
        }
    });

    openPreviewImageBtn.addEventListener('click', function() {
        openImageViewer(currentEventImage.src);
    });

    document.getElementById('detailEventImage').addEventListener('click', function() {
        openImageViewer(this.src);
    });

    closeImageViewerModal.addEventListener('click', closeViewer);
    imageViewerModal.addEventListener('click', function(e) {
        if (e.target === imageViewerModal) {
            closeViewer();
        }
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !imageViewerModal.classList.contains('hidden')) {
            closeViewer();
        }
    });

    eventForm.addEventListener('submit', function(e) {
        e.preventDefault();
        clearEventErrors();

        const formData = new FormData(eventForm);
        const eventId = document.getElementById('eventId').value;
        const isEdit = eventId !== '';
        const url = isEdit ? routes.eventUpdate.replace(':id', eventId) : routes.eventStore;

        eventSubmitBtn.disabled = true;
        eventSubmitText.classList.add('hidden');
        eventSubmitLoading.classList.remove('hidden');

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw { status: response.status, data: data };
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    upsertEventRow(data.event);
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                    closeEventModal();
                }
            })
            .catch(error => {
                if (error.status === 422 && error.data && error.data.errors) {
                    showEventErrors(error.data.errors);
                    return;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to save event. Please try again.'
                });
            })
            .finally(() => {
                eventSubmitBtn.disabled = false;
                eventSubmitText.classList.remove('hidden');
                eventSubmitLoading.classList.add('hidden');
            });
    });

    window.openAddEventModal = function() {
        resetEventForm();
        eventModalTitle.textContent = 'Add New Event';
        eventSubmitText.textContent = 'Create Event';
        document.getElementById('eventMethodField').value = 'POST';
        eventModal.classList.remove('hidden');
    };

    window.editEvent = function(button) {
        try {
            const event = JSON.parse(button.dataset.event);
            resetEventForm();

            eventModalTitle.textContent = 'Edit Event';
            eventSubmitText.textContent = 'Update Event';
            document.getElementById('eventMethodField').value = 'PUT';
            document.getElementById('eventId').value = event.id;
            eventImageInput.required = false;
            document.getElementById('eventImageRequired').textContent = '';
            document.getElementById('eventCategory').value = event.category || '';
            document.getElementById('eventTitle').value = event.title || '';
            document.getElementById('eventDescription').value = event.description || '';
            document.getElementById('eventSubmissionDeadline').value = event.submission_deadline || '';
            document.getElementById('eventDate').value = event.event_date || '';

            if (event.image_url) {
                eventImageName.textContent = 'Current image';
                currentEventImage.src = event.image_url;
                eventImagePreviewWrap.classList.remove('hidden');
            }

            eventModal.classList.remove('hidden');
        } catch (e) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Unable to load event data.'
            });
        }
    };

    window.viewEvent = function(button) {
        try {
            const event = JSON.parse(button.dataset.event);
            document.getElementById('detailEventImage').src = event.image_url || '';
            document.getElementById('detailEventCategory').textContent = event.category || '-';
            document.getElementById('detailEventTitle').textContent = event.title || '-';
            document.getElementById('detailEventSubmissionDeadline').textContent = event.submission_deadline || '-';
            document.getElementById('detailEventDate').textContent = event.event_date || '-';
            document.getElementById('detailEventDescription').textContent = event.description || '-';
            eventDetailModal.classList.remove('hidden');
        } catch (e) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Unable to load event details.'
            });
        }
    };

    window.deleteEvent = function(eventId, eventTitle) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete event "${eventTitle}".`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            fetch(routes.eventDestroy.replace(':id', eventId), {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Delete failed');
                    }
                    return data;
                })
                .then(data => {
                    removeEventRow(data.event_id);
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Unable to delete event.'
                    });
                });
        });
    };
});
</script>
