<!-- User Modal -->
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add New User</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4">
                <form id="userForm" method="POST">
                    @csrf
                    <input type="hidden" id="methodField" name="_method" value="POST">
                    <input type="hidden" id="userId" name="user_id">

                    <!-- Name Field -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input id="name" type="text" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <span id="nameError" class="text-red-600 text-sm hidden"></span>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input id="email" type="email" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <span id="emailError" class="text-red-500 text-sm hidden"></span>
                    </div>

                    <!-- Contact Field -->
                    <div class="mb-4">
                        <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">Contact</label>
                        <input id="contact" type="text" name="contact"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <span id="contactError" class="text-red-500 text-sm hidden"></span>
                    </div>

                    <!-- Address Field -->
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                        <span id="addressError" class="text-red-500 text-sm hidden"></span>
                    </div>

                    <!-- Designation Field -->
                    <div class="mb-4">
                        <label for="designation" class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                        <input id="designation" type="text" name="designation"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <span id="designationError" class="text-red-500 text-sm hidden"></span>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4" id="passwordField">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span id="passwordHint" class="text-gray-500 text-xs hidden">(leave blank to keep current)</span>
                        </label>
                        <input id="password" type="password" name="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <span id="passwordError" class="text-red-500 text-sm hidden"></span>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-6" id="confirmPasswordField">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                            <span id="submitText">Create User</span>
                            <span id="submitLoading" class="hidden">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Laravel routes for AJAX calls
    const routes = {
        userStore: '{{ route("users.store") }}',
        userUpdate: '{{ route("users.update", ":id") }}',
        userDestroy: '{{ route("users.destroy", ":id") }}'
    };

    const userModal = document.getElementById('userModal');
    const userForm = document.getElementById('userForm');
    const modalTitle = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitLoading = document.getElementById('submitLoading');
    
    // Modal controls
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');

    // Close modal functions
    function closeUserModal() {
        userModal.classList.add('hidden');
        resetForm();
    }

    // Event listeners for closing modals
    closeModal.addEventListener('click', closeUserModal);
    cancelBtn.addEventListener('click', closeUserModal);

    // Close modal when clicking outside
    userModal.addEventListener('click', function(e) {
        if (e.target === userModal) {
            closeUserModal();
        }
    });

    // Reset form function
    function resetForm() {
        userForm.reset();
        document.getElementById('methodField').value = 'POST';
        document.getElementById('userId').value = '';
        
        // Hide all error messages
        document.querySelectorAll('[id$="Error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });

        // Reset field styles
        document.querySelectorAll('input, textarea').forEach(el => {
            el.classList.remove('border-red-500');
        });

        // Reset password field requirements
        document.getElementById('password').required = true;
        document.getElementById('password_confirmation').required = true;
        document.getElementById('passwordHint').classList.add('hidden');
    }

    // Form submission
    userForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');

        const formData = new FormData(userForm);
        const userId = document.getElementById('userId').value;
        const isEdit = userId !== '';
        const method = document.getElementById('methodField').value;
        
        // Remove user_id from form data since it should only be in URL
        formData.delete('user_id');
        
        let url = isEdit ? routes.userUpdate.replace(':id', userId) : routes.userStore;
        
        // For PUT/PATCH requests, we need to use POST with _method field
        const fetchOptions = {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        };

        console.log('Submitting form:', {
            url: url,
            method: method,
            isEdit: isEdit,
            userId: userId,
            formDataEntries: Array.from(formData.entries())
        });
        
        fetch(url, fetchOptions)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                Toast.fire({
                    icon: 'success',
                    title: data.message || (isEdit ? 'User updated successfully!' : 'User created successfully!')
                });
                closeUserModal();
                // Reload the page to refresh the user list
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                // Handle validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(field + 'Error');
                        const inputElement = document.getElementById(field);
                        
                        if (errorElement && inputElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.classList.remove('hidden');
                            inputElement.classList.add('border-red-500');
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: data.message || 'Please check your input and try again.',
                        confirmButtonText: 'Got it',
                        confirmButtonColor: '#22c55e'
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Connection Error',
                text: 'Unable to connect to the server. Please check your internet connection and try again.',
                confirmButtonText: 'Retry',
                confirmButtonColor: '#22c55e'
            });
        })
        .finally(() => {
            // Reset loading state
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            submitLoading.classList.add('hidden');
        });
    });

    // Global functions for opening modals
    window.openAddUserModal = function() {
        resetForm();
        modalTitle.textContent = 'Add New User';
        submitText.textContent = 'Create User';
        userForm.action = routes.userStore;
        document.getElementById('methodField').value = 'POST';
        userModal.classList.remove('hidden');
    };

    window.openEditUserModal = function(userElement) {
        resetForm();
        
        // Get user data from data attribute if it's an element, otherwise assume it's user data
        let user;
        if (userElement && userElement.dataset && userElement.dataset.user) {
            user = JSON.parse(userElement.dataset.user);
        } else if (typeof userElement === 'object') {
            user = userElement;
        } else {
            console.error('Invalid user data');
            return;
        }
        
        modalTitle.textContent = 'Edit User';
        submitText.textContent = 'Update User';
        userForm.action = routes.userUpdate.replace(':id', user.id);
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('userId').value = user.id;
        
        // Fill form with user data
        document.getElementById('name').value = user.name || '';
        document.getElementById('email').value = user.email || '';
        document.getElementById('contact').value = user.contact || '';
        document.getElementById('address').value = user.address || '';
        document.getElementById('designation').value = user.designation || '';
        
        // Make password optional for edit
        document.getElementById('password').required = false;
        document.getElementById('password_confirmation').required = false;
        document.getElementById('passwordHint').classList.remove('hidden');
        
        userModal.classList.remove('hidden');
    };

    window.editUser = function(button) {
        try {
            const userData = JSON.parse(button.dataset.user);
            console.log('Edit user data:', userData);
            openEditUserModal(userData);
        } catch (error) {
            console.error('Error parsing user data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Data Error',
                text: 'Unable to load user information for editing.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#22c55e'
            });
        }
    };

    window.deleteUser = function(userId, userName) {
        console.log('Delete user called with:', { userId, userName });
        
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete user "${userName}". This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#22c55e',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the user.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const deleteUrl = routes.userDestroy.replace(':id', userId);
                console.log('Deleting user at URL:', deleteUrl);

                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    console.log('Delete response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Delete response data:', data);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message || 'User has been successfully deleted.',
                            timer: 2000,
                            showConfirmButton: false,
                            iconColor: '#22c55e'
                        });
                        // Reload the page to refresh the user list
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Deletion Failed',
                            text: data.message || 'Failed to delete user. Please try again.',
                            confirmButtonText: 'Try Again',
                            confirmButtonColor: '#22c55e'
                        });
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Error',
                        text: 'An error occurred while deleting the user. Please check your connection and try again.',
                        confirmButtonText: 'Retry',
                        confirmButtonColor: '#22c55e'
                    });
                });
            }
        });
    };
});
</script>
