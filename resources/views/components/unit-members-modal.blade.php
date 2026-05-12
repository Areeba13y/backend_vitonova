<!-- Unit Members Modal -->
<div id="unitMembersModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative w-full max-w-4xl mx-4">
        <div class="bg-white rounded-lg shadow-xl">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 id="membersModalTitle" class="text-lg font-semibold text-gray-800">Unit Members</h3>
                    <p id="membersModalSubtitle" class="text-sm text-gray-500 mt-1">Loading...</p>
                </div>
                <button id="closeMembersModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Loader -->
                <div id="membersLoader" class="flex items-center justify-center py-12">
                    <div class="flex items-center">
                        <i class="fas fa-spinner fa-spin text-indigo-500 text-2xl mr-3"></i>
                        <span class="text-gray-600">Loading members...</span>
                    </div>
                </div>

                <!-- Data Table -->
                <div id="membersTableContainer" class="hidden overflow-auto" style="max-height: 400px;">
                    <table id="unitMembersTable" class="w-full">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Name</th>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Email</th>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Designation</th>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pb-3">Contact</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600">
                        </tbody>
                    </table>
                </div>
                <!-- Pagination inside modal -->
                <div id="membersPagination" class="hidden mt-4 flex justify-between items-center"></div>

                <!-- Empty State -->
                <div id="membersEmptyState" class="hidden text-center py-12">
                    <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">No members found in this unit</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const membersModal = document.getElementById('unitMembersModal');
    const closeBtn = document.getElementById('closeMembersModal');
    const loader = document.getElementById('membersLoader');
    const tableContainer = document.getElementById('membersTableContainer');
    const emptyState = document.getElementById('membersEmptyState');
    const modalTitle = document.getElementById('membersModalTitle');
    const modalSubtitle = document.getElementById('membersModalSubtitle');

    let membersDataTable = null;

    function closeMembersModal() {
        membersModal.classList.add('hidden');
        if (membersDataTable) {
            membersDataTable.destroy();
            membersDataTable = null;
        }
        tableContainer.classList.add('hidden');
        emptyState.classList.add('hidden');
        loader.classList.remove('hidden');
    }

    closeBtn.addEventListener('click', closeMembersModal);

    membersModal.addEventListener('click', function(e) {
        if (e.target === membersModal) {
            closeMembersModal();
        }
    });

    window.openUnitMembersModal = function(unitId, unitName, memberCount) {
        modalTitle.textContent = unitName + ' - Members';
        modalSubtitle.textContent = memberCount + ' member' + (memberCount != 1 ? 's' : '') + ' total';
        
        membersModal.classList.remove('hidden');
        loader.classList.remove('hidden');
        tableContainer.classList.add('hidden');
        emptyState.classList.add('hidden');

        setTimeout(function() {
            loader.classList.add('hidden');
            tableContainer.classList.remove('hidden');

            if (membersDataTable) {
                membersDataTable.destroy();
            }

            membersDataTable = $('#unitMembersTable').DataTable({
                serverSide: true,
                ajax: {
                    url: '{{ route("users.datatable") }}',
                    type: 'GET',
                    data: function(d) {
                        d.unit_id = unitId;
                    },
                    beforeSend: function() {
                    },
                    complete: function() {
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTable Error:', xhr.responseText);
                        tableContainer.classList.add('hidden');
                        emptyState.classList.remove('hidden');
                    }
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'designation', name: 'designation' },
                    { data: 'contact', name: 'contact' }
                ],
                order: [[0, 'asc']],
                dom: '<"flex justify-between items-center mb-3"<"flex items-center"l><"flex items-center"i>>rt<"flex justify-between items-center mt-3"<"flex items-center"p>>',
                language: {
                    search: "",
                    searchPlaceholder: "Search members...",
                    lengthMenu: "Show _MENU_",
                    info: "Showing _START_ to _END_ of _TOTAL_ members",
                    emptyTable: '<div class="text-center py-8"><i class="fas fa-users text-gray-300 text-4xl mb-3"></i><p class="text-gray-500">No members found</p></div>',
                    zeroRecords: '<div class="text-center py-8"><i class="fas fa-search text-gray-300 text-4xl mb-3"></i><p class="text-gray-500">No matching records found</p></div>',
                    paginate: {
                        first: '<i class="fas fa-chevrons-left"></i>',
                        last: '<i class="fas fa-chevrons-right"></i>',
                        next: '<i class="fas fa-chevron-right"></i>',
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                },
                drawCallback: function(settings) {
                    var api = this.api();
                    var recordsTotal = api.page.info().recordsTotal;
                    if (recordsTotal === 0) {
                        tableContainer.classList.add('hidden');
                        emptyState.classList.remove('hidden');
                    } else {
                        tableContainer.classList.remove('hidden');
                        emptyState.classList.add('hidden');
                    }
                }
            });
        }, 300);
    };
});
</script>
