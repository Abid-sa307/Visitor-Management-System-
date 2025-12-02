// resources/views/components/face-verify-button.blade.php
@props(['visitor'])

<button type="button" 
        class="btn btn-sm btn-outline-primary verify-face" 
        data-visitor-id="{{ $visitor->id }}"
        data-visitor-name="{{ $visitor->name }}"
        data-face-encoding='@json($visitor->face_encoding)'>
    <i class="fas fa-user-check"></i> Verify
</button>

@push('scripts')
<script>
document.querySelectorAll('.verify-face').forEach(button => {
    button.addEventListener('click', async function() {
        const visitorId = this.dataset.visitorId;
        const visitorName = this.dataset.visitorName;
        
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            // Similar capture logic as in face-capture component
            // Then send to server for verification
            
            const response = await fetch(`/visitors/${visitorId}/verify-face`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    face_encoding: capturedDescriptor
                })
            });
            
            const result = await response.json();
            
            if (result.match) {
                alert(`Verified as ${visitorName}! (Distance: ${result.distance.toFixed(4)})`);
            } else {
                alert('Verification failed. Face does not match.');
            }
            
        } catch (error) {
            console.error('Error during face verification:', error);
            alert('Error during face verification');
        }
    });
});
</script>
@endpush