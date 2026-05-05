@extends('layouts.master')

@section('title', 'Units')
@section('page_title', 'Units')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <h4 class="text-xl font-semibold text-gray-800">Units</h4>
        <button type="button" onclick="openUnitModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
            Add Unit
        </button>
    </div>

    <div class="p-6 overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($units as $unit)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $unit->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $unit->code }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                <button type="button" class="text-yellow-600 hover:text-yellow-900" title="Edit"
                                    onclick='openUnitModal(@json(["id"=>$unit->id,"name"=>$unit->name]))'>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form method="POST" action="{{ route('units.destroy', $unit) }}" class="js-unit-delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12M9 7V4h6v3m-7 4v6m4-6v6m4-6v6M8 7l1 13h6l1-13"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">No units found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">{{ $units->links() }}</div>
    </div>
</div>

<div id="unitModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50 px-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        <h5 id="unitModalTitle" class="text-lg font-semibold text-gray-800 mb-4">Add Unit</h5>
        <form id="unitForm" method="POST" action="{{ route('units.store') }}">
            @csrf
            <input type="hidden" id="unitMethod" name="_method" value="POST">
            <div class="mb-4">
                <label class="block text-sm text-gray-700 mb-2">Unit Name</label>
                <input id="unitName" type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeUnitModal()" class="px-3 py-2 bg-gray-200 rounded-md">Cancel</button>
                <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded-md">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function openUnitModal(unit = null) {
    const modal = document.getElementById('unitModal');
    const form = document.getElementById('unitForm');
    const method = document.getElementById('unitMethod');
    const title = document.getElementById('unitModalTitle');
    const name = document.getElementById('unitName');

    if (unit && unit.id) {
        form.action = `{{ url('/units') }}/${unit.id}`;
        method.value = 'PUT';
        title.textContent = 'Edit Unit';
        name.value = unit.name || '';
    } else {
        form.action = `{{ route('units.store') }}`;
        method.value = 'POST';
        title.textContent = 'Add Unit';
        name.value = '';
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeUnitModal() {
    const modal = document.getElementById('unitModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

document.querySelectorAll('.js-unit-delete-form').forEach((form) => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!window.Swal || typeof window.Swal.fire !== 'function') {
            if (confirm('Delete this unit?')) form.submit();
            return;
        }
        Swal.fire({
            title: 'Are you sure?',
            text: 'Delete this unit?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});

@if(session('success'))
if (window.Toast && typeof window.Toast.fire === 'function') {
    Toast.fire({ icon: 'success', title: @json(session('success')) });
}
@endif
</script>
@endsection

