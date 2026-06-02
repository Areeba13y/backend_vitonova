<!-- User Modal -->
<div id="userModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative w-full max-w-lg mx-4">
        <div class="bg-white rounded-lg shadow-xl">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-800">Add New User</h3>
                    <p class="text-sm text-gray-500 mt-1">Fill in the user details</p>
                </div>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="userForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="methodField" name="_method" value="POST">
                    <input type="hidden" id="userId" name="user_id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                            <input id="name" type="text" name="name" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span id="nameError" class="text-red-500 text-xs hidden"></span>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input id="email" type="email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span id="emailError" class="text-red-500 text-xs hidden"></span>
                        </div>

                        <div>
                            <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                            <select id="unit_id" name="unit_id" required
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Unit</option>
                                @foreach(\App\Models\Unit::orderBy('name')->get() as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                            <span id="unit_idError" class="text-red-500 text-xs hidden"></span>
                        </div>

                        <div>
                            <label for="designation" class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                            <input id="designation" type="text" name="designation"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="contact" class="block text-sm font-medium text-gray-700 mb-1">Contact</label>
                            <input id="contact" type="text" name="contact"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input id="password" type="password" name="password" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span id="passwordHint" class="text-xs text-gray-400 hidden">Leave blank to keep current</span>
                            <span id="passwordError" class="text-red-500 text-xs hidden"></span>
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea id="address" name="address" rows="2"
                                  class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                    </div>

                    <div>
                        <label for="details" class="block text-sm font-medium text-gray-700 mb-1">Details</label>
                        <textarea id="details" name="details" rows="2"
                                  class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                    </div>

                    <div>
                        <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                        <input id="profile_picture" type="file" name="profile_picture" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <img id="profilePreview" src="" alt="" class="mt-2 w-16 h-16 rounded-lg object-cover hidden">
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors text-sm font-medium">
                            <span id="submitText">Save User</span>
                            <span id="submitLoading" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Saving...
                            </span>
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
        userStore: '{{ route("users.store") }}',
        userUpdate: '{{ route("users.update", ":id") }}'
    };

    const userModal = document.getElementById('userModal');
    const userForm = document.getElementById('userForm');
    const modalTitle = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitLoading = document.getElementById('submitLoading');
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const profilePreview = document.getElementById('profilePreview');
    const profilePictureInput = document.getElementById('profile_picture');

    function closeUserModal() {
        userModal.classList.add('hidden');
        resetForm();
    }

    closeModal.addEventListener('click', closeUserModal);
    cancelBtn.addEventListener('click', closeUserModal);

    userModal.addEventListener('click', function(e) {
        if (e.target === userModal) {
            closeUserModal();
        }
    });

    profilePictureInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
                profilePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    function resetForm() {
        userForm.reset();
        document.getElementById('methodField').value = 'POST';
        document.getElementById('userId').value = '';
        document.querySelectorAll('[id$="Error"]').forEach(el => {
            el.classList.add('hidden');
        });
        profilePreview.classList.add('hidden');
        document.getElementById('passwordHint').classList.add('hidden');
    }

    userForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');

        const formData = new FormData(userForm);
        const userId = document.getElementById('userId').value;
        const isEdit = userId !== '';
        formData.delete('user_id');
        
        let url = isEdit ? routes.userUpdate.replace(':id', userId) : routes.userStore;

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
                Toast.fire({ icon: 'success', title: data.message || 'User saved successfully!' });
                closeUserModal();
                if (typeof $('#usersTable') !== 'undefined') {
                    $('#usersTable').DataTable().ajax.reload();
                }
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(field + 'Error');
                        const inputElement = document.getElementById(field);
                        if (errorElement && inputElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to save user' });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to save user' });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            submitLoading.classList.add('hidden');
        });
    });

    window.openAddUserModal = function() {
        resetForm();
        modalTitle.textContent = 'Add New User';
        submitText.textContent = 'Save User';
        document.getElementById('passwordHint').classList.add('hidden');
        userModal.classList.remove('hidden');
    };

    window.editUser = function(button) {
        try {
            const userData = JSON.parse(button.dataset.user);
            resetForm();
            
            modalTitle.textContent = 'Edit User';
            submitText.textContent = 'Update User';
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('userId').value = userData.id;
            
            document.getElementById('name').value = userData.name || '';
            document.getElementById('email').value = userData.email || '';
            document.getElementById('contact').value = userData.contact || '';
            document.getElementById('unit_id').value = userData.unit_id || '';
            document.getElementById('address').value = userData.address || '';
            document.getElementById('designation').value = userData.designation || '';
            document.getElementById('details').value = userData.details || '';
            
            if (userData.profile_picture) {
                profilePreview.src = "{{ url('/') }}/" + userData.profile_picture;
                profilePreview.classList.remove('hidden');
            }
            
            document.getElementById('password').required = false;
            document.getElementById('passwordHint').classList.remove('hidden');
            
            userModal.classList.remove('hidden');
        } catch (error) {
            console.error('Error:', error);
        }
    };
});
</script>
