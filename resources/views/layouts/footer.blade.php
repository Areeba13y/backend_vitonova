<footer class="bg-gradient-to-r from-white via-green-100 to-green-200 text-gray-800 mt-auto shadow-lg border-t border-green-200">
    <div class="px-6 py-3">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">&copy; {{ date('Y') }} Product Management System. All rights reserved.</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center px-3 py-2 rounded-lg bg-green-500 bg-opacity-20 backdrop-blur-sm text-sm font-medium text-green-700 border border-green-300">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <span id="current-datetime" class="font-medium">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update date and time
    function updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        
        const dateTimeElement = document.getElementById('current-datetime');
        if (dateTimeElement) {
            dateTimeElement.textContent = now.toLocaleDateString('en-US', options);
        }
    }
    
    // Update immediately and then every second
    updateDateTime();
    setInterval(updateDateTime, 1000);
});
</script>