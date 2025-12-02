<!-- resources/views/components/face-capture.blade.php -->
<div>
    <video id="video" width="320" height="240" autoplay class="d-none"></video>
    <canvas id="canvas" width="320" height="240" class="d-none"></canvas>
    
    <div class="mb-3">
        <button id="startCamera" class="btn btn-primary">Start Camera</button>
        <button id="capture" class="btn btn-success" disabled>Capture</button>
    </div>
    
    <div id="preview" class="mb-3"></div>
    <input type="hidden" name="face_encoding" id="face_encoding">
    
    @push('scripts')
    <script src="{{ asset('js/face-api.min.js') }}"></script>
    <script>
        let stream;
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const startBtn = document.getElementById('startCamera');
        const captureBtn = document.getElementById('capture');
        const preview = document.getElementById('preview');
        
        startBtn.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                video.classList.remove('d-none');
                captureBtn.disabled = false;
                startBtn.disabled = true;
                
                // Load face-api models
                await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
                await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
                await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
            } catch (err) {
                console.error("Error accessing camera:", err);
            }
        });
        
        captureBtn.addEventListener('click', async () => {
            const detection = await faceapi.detectSingleFace(
                video, 
                new faceapi.TinyFaceDetectorOptions()
            ).withFaceLandmarks().withFaceDescriptor();
            
            if (detection) {
                const descriptor = Array.from(detection.descriptor);
                document.getElementById('face_encoding').value = JSON.stringify(descriptor);
                
                // Show preview
                canvas.getContext('2d').drawImage(video, 0, 0, 320, 240);
                preview.innerHTML = '<img src="' + canvas.toDataURL() + '" class="img-fluid">';
            }
        });
    </script>
    @endpush
</div>