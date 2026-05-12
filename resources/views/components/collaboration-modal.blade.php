<!-- Collaboration Modal -->
<div id="collaborationModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative w-full max-w-lg mx-4">
        <div class="bg-white rounded-lg shadow-xl">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 id="collaborationModalTitle" class="text-lg font-semibold text-gray-800">Add Collaboration</h3>
                <button id="closeCollaborationModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6">
                <form id="collaborationForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" id="collaborationId" name="collaboration_id">
                    <input type="hidden" id="collaborationMethodField" name="_method" value="POST">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Logo *</label>
                            <input id="collaborationLogo" type="file" name="logo" accept="image/*"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <img id="collaborationLogoPreview" class="mt-2 w-16 h-16 object-cover rounded-lg hidden" alt="Preview">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Organization Name *</label>
                            <input id="organizationName" type="text" name="organization_name" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <input id="subtitle" type="text" name="subtitle"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                        <textarea id="description" name="description" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Representative Designation</label>
                            <input id="representativeDesignation" type="text" name="representative_designation"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="e.g. Minister of Foreign Affairs">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Representative Name</label>
                            <input id="representativeName" type="text" name="representative_name"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="e.g. Dr. John Smith">
                        </div>
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-2">
                            <input id="isActive" type="checkbox" name="is_active" value="1" checked
                                   class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Active (show on website)</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <button type="button" id="cancelCollaborationBtn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">Cancel</button>
                        <button type="submit" id="collaborationSubmitBtn" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 text-sm font-medium">
                            <span>Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const routes = {
        store: '{{ route("collaborations.store") }}',
        update: '{{ route("collaborations.update", ":id") }}'
    };

    const modal = document.getElementById('collaborationModal');
    const form = document.getElementById('collaborationForm');
    const logoInput = document.getElementById('collaborationLogo');
    const logoPreview = document.getElementById('collaborationLogoPreview');

    function resetForm() {
        form.reset();
        document.getElementById('collaborationId').value = '';
        document.getElementById('collaborationMethodField').value = 'POST';
        logoInput.required = false;
        document.getElementById('isActive').checked = true;
        logoPreview.classList.add('hidden');
        logoPreview.src = '';
    }

    function closeModal() {
        modal.classList.add('hidden');
        resetForm();
    }

    logoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                logoPreview.src = e.target.result;
                logoPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    document.getElementById('closeCollaborationModal').addEventListener('click', closeModal);
    document.getElementById('cancelCollaborationBtn').addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('collaborationId').value;
        const url = id ? routes.update.replace(':id', id) : routes.store;
        const formData = new FormData(form);

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
                closeModal();
                if (typeof $('#collaborationsTable') !== 'undefined') {
                    $('#collaborationsTable').DataTable().ajax.reload();
                }
            }
        })
        .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to save collaboration' });
        });
    });

    window.openAddCollaborationModal = function() {
        resetForm();
        document.getElementById('collaborationModalTitle').textContent = 'Add Collaboration';
        modal.classList.remove('hidden');
    };

    window.editCollaboration = function(button) {
        const item = JSON.parse(button.dataset.collaboration);
        resetForm();
        document.getElementById('collaborationModalTitle').textContent = 'Edit Collaboration';
        document.getElementById('collaborationId').value = item.id;
        document.getElementById('collaborationMethodField').value = 'PUT';
        document.getElementById('organizationName').value = item.organization_name || '';
        document.getElementById('subtitle').value = item.subtitle || '';
        document.getElementById('description').value = item.description || '';
        document.getElementById('representativeDesignation').value = item.representative_designation || '';
        document.getElementById('representativeName').value = item.representative_name || '';
        document.getElementById('isActive').checked = !!item.is_active;
        if (item.logo_url) {
            logoPreview.src = item.logo_url;
            logoPreview.classList.remove('hidden');
        }
        modal.classList.remove('hidden');
    };
});
</script>
