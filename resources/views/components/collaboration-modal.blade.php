<div id="collaborationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-16 mx-auto p-5 border w-11/12 md:w-2/3 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 id="collaborationModalTitle" class="text-lg font-semibold">Add Collaboration</h3>
            <button id="closeCollaborationModal" class="text-gray-500">X</button>
        </div>

        <form id="collaborationForm" class="mt-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="collaborationId" name="collaboration_id">
            <input type="hidden" id="collaborationMethodField" name="_method" value="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Logo</label>
                    <input id="collaborationLogo" type="file" name="logo" accept="image/*" class="w-full border rounded p-2" required>
                    <small id="logoError" class="text-red-500 hidden"></small>
                    <img id="collaborationLogoPreview" class="hidden w-20 h-20 object-cover rounded mt-2 border" alt="Preview">
                </div>
                <div>
                    <label class="text-sm font-medium">Organization Name</label>
                    <input id="organizationName" type="text" name="organization_name" class="w-full border rounded p-2" required>
                    <small id="organization_nameError" class="text-red-500 hidden"></small>
                </div>
            </div>

            <div class="mt-4">
                <label class="text-sm font-medium">Subtitle</label>
                <input id="subtitle" type="text" name="subtitle" class="w-full border rounded p-2">
                <small id="subtitleError" class="text-red-500 hidden"></small>
            </div>

            <div class="mt-4">
                <label class="text-sm font-medium">Description</label>
                <textarea id="description" name="description" rows="5" class="w-full border rounded p-2" required></textarea>
                <small id="descriptionError" class="text-red-500 hidden"></small>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="text-sm font-medium">Representative Designation</label>
                    <input id="representativeDesignation" type="text" name="representative_designation" class="w-full border rounded p-2" placeholder="e.g. Minister of Foreign Affairs - USA">
                    <small id="representative_designationError" class="text-red-500 hidden"></small>
                </div>
                <div>
                    <label class="text-sm font-medium">Representative Name</label>
                    <input id="representativeName" type="text" name="representative_name" class="w-full border rounded p-2" placeholder="e.g. Dr. Alberto Flores Hernandez">
                    <small id="representative_nameError" class="text-red-500 hidden"></small>
                </div>
            </div>

            <div class="mt-4">
                <label class="inline-flex items-center gap-2">
                    <input id="isActive" type="checkbox" name="is_active" value="1" checked>
                    <span class="text-sm">Active (show on website)</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                <button type="button" id="cancelCollaborationBtn" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                <button type="submit" id="collaborationSubmitBtn" class="px-4 py-2 bg-green-500 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const routes = {
        store: '{{ route("collaborations.store") }}',
        update: '{{ route("collaborations.update", ":id") }}',
        destroy: '{{ route("collaborations.destroy", ":id") }}'
    };

    const modal = document.getElementById('collaborationModal');
    const form = document.getElementById('collaborationForm');
    const logoInput = document.getElementById('collaborationLogo');
    const logoPreview = document.getElementById('collaborationLogoPreview');

    function resetForm() {
        form.reset();
        document.getElementById('collaborationId').value = '';
        document.getElementById('collaborationMethodField').value = 'POST';
        logoInput.required = true;
        document.getElementById('isActive').checked = true;
        logoPreview.classList.add('hidden');
        logoPreview.src = '';
        clearErrors();
    }

    function clearErrors() {
        ['logo','organization_name','subtitle','description','representative_designation','representative_name'].forEach(function (field) {
            const err = document.getElementById(field + 'Error');
            if (err) { err.classList.add('hidden'); err.textContent = ''; }
        });
    }

    function showErrors(errors) {
        clearErrors();
        Object.keys(errors).forEach(function (field) {
            const err = document.getElementById(field + 'Error');
            if (err) {
                err.textContent = errors[field][0];
                err.classList.remove('hidden');
            }
        });
    }

    function closeModal() {
        modal.classList.add('hidden');
        resetForm();
    }

    logoInput.addEventListener('change', function () {
        if (!this.files || !this.files[0]) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            logoPreview.src = e.target.result;
            logoPreview.classList.remove('hidden');
        };
        reader.readAsDataURL(this.files[0]);
    });

    document.getElementById('closeCollaborationModal').addEventListener('click', closeModal);
    document.getElementById('cancelCollaborationBtn').addEventListener('click', closeModal);

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearErrors();

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
        .then(async (response) => {
            const data = await response.json();
            if (!response.ok) throw { status: response.status, data };
            return data;
        })
        .then((data) => {
            Toast.fire({ icon: 'success', title: data.message });
            window.location.reload();
        })
        .catch((error) => {
            if (error.status === 422 && error.data && error.data.errors) {
                showErrors(error.data.errors);
                return;
            }
            Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to save collaboration.' });
        });
    });

    window.openAddCollaborationModal = function () {
        resetForm();
        document.getElementById('collaborationModalTitle').textContent = 'Add Collaboration';
        modal.classList.remove('hidden');
    };

    window.editCollaboration = function (button) {
        const item = JSON.parse(button.dataset.collaboration);
        resetForm();
        document.getElementById('collaborationModalTitle').textContent = 'Edit Collaboration';
        document.getElementById('collaborationId').value = item.id;
        document.getElementById('collaborationMethodField').value = 'PUT';
        logoInput.required = false;
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

    window.deleteCollaboration = function (id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete "${name}".`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch(routes.destroy.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(async (response) => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Delete failed');
                return data;
            })
            .then((data) => {
                Toast.fire({ icon: 'success', title: data.message });
                window.location.reload();
            })
            .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to delete collaboration.' }));
        });
    };
});
</script>
