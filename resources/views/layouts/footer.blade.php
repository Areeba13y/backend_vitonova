<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Vitonova Admin. All rights reserved.</p>
            </div>
            <div class="text-sm text-gray-400">
                <span id="current-datetime">{{ now()->format('M d, Y') }}</span>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateDateTime() {
        const now = new Date();
        const options = { month: 'short', day: 'numeric', year: 'numeric' };
        const dateTimeElement = document.getElementById('current-datetime');
        if (dateTimeElement) {
            dateTimeElement.textContent = now.toLocaleDateString('en-US', options);
        }
    }
    updateDateTime();
});
</script>
