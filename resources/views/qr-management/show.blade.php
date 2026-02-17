@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-gray-800">
            <i class="fas fa-qrcode me-2"></i>QR Code
        </h1>
        <div>
            <a href="{{ route('qr.index') }}" class="btn btn-secondary btn-sm me-2">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
            <button onclick="downloadQRCode()" class="btn btn-success btn-sm">
                <i class="fas fa-download me-1"></i> Download QR
            </button>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Company:</div>
                        <div class="col-md-8">{{ $company->name }}</div>
                    </div>
                    @if($branch)
                        <div class="branch-details mb-4 p-3 bg-light rounded">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-code-branch me-2 text-primary"></i>
                                <h6 class="m-0 fw-bold">Branch Information</h6>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 fw-semibold">Branch Name:</div>
                                <div class="col-md-8">{{ $branch->name }}</div>
                            </div>
                            @if($branch->email)
                            <div class="row mb-2">
                                <div class="col-md-4 fw-semibold">Email:</div>
                                <div class="col-md-8">
                                    <a href="mailto:{{ $branch->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1"></i>{{ $branch->email }}
                                    </a>
                                </div>
                            </div>
                            @endif
                            @if($branch->phone)
                            <div class="row mb-2">
                                <div class="col-md-4 fw-semibold">Phone:</div>
                                <div class="col-md-8">
                                    <a href="tel:{{ $branch->phone }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>{{ $branch->phone }}
                                    </a>
                                </div>
                            </div>
                            @endif
                            @if($branch->address)
                            <div class="row">
                                <div class="col-md-4 fw-semibold">Address:</div>
                                <div class="col-md-8">
                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i>{{ $branch->address }}
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Contact:</div>
                        <div class="col-md-8">{{ $company->contact_number ?? '—' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 fw-bold">Check-in URL:</div>
                        <div class="col-md-8">
                            <a href="{{ $branch ? route('qr.scan', ['company' => $company, 'branch' => $branch]) : route('qr.scan', $company) }}" 
                               target="_blank" class="text-break">
                                {{ $branch ? route('qr.scan', ['company' => $company, 'branch' => $branch]) : route('qr.scan', $company) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <h5 class="mb-4">Scan this QR Code for Check-in</h5>
                    <div class="d-flex justify-content-center mb-4">
                        <div class="border p-3 bg-white">
                            <img src="{{ $qrCode }}" 
                                alt="QR Code" 
                                class="img-fluid"
                                style="max-width: 300px; width: 100%; height: auto;">
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <p class="mb-2">
                            <strong>{{ $branch ? $branch->name : $company->name }}</strong><br>
                            @if($branch && $branch->address)
                                <small class="text-muted">{{ $branch->address }}</small>
                            @elseif($company->address)
                                <small class="text-muted">{{ $company->address }}</small>
                            @endif
                        </p>
                        @if($branch && $branch->phone)
                            <p class="mb-0">
                                <i class="fas fa-phone me-1"></i> {{ $branch->phone }}
                            </p>
                        @elseif($company->contact_number)
                            <p class="mb-0">
                                <i class="fas fa-phone me-1"></i> {{ $company->contact_number }}
                            </p>
                        @endif
                    <p class="text-muted mb-0">
                        <small>Scan this code with a smartphone camera to access the check-in page.</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .text-break {
        word-break: break-all;
    }
</style>

@push('scripts')
<script>
function downloadQRCode() {
    // Get the SVG element from the base64 image
    const imgElement = document.querySelector('img[alt="QR Code"]');
    const base64Data = imgElement.src;
    
    // Create a temporary image to extract SVG data
    const tempImg = new Image();
    tempImg.crossOrigin = 'anonymous';
    
    // For base64 SVG, we need to decode and parse it
    const base64Content = base64Data.split(',')[1];
    const svgData = atob(base64Content);
    
    // Create a high-resolution canvas for professional output
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    // Set canvas size for high quality print (A4 aspect ratio)
    const width = 2400;
    const height = 3200; // Increased height to accommodate branch details
    canvas.width = width;
    canvas.height = height;
    
    const img = new Image();
    const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
    const url = URL.createObjectURL(svgBlob);
    
    img.onload = function() {
        // Create white background
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, width, height);
        
        // Add subtle border
        ctx.strokeStyle = '#e0e0e0';
        ctx.lineWidth = 4;
        ctx.strokeRect(40, 40, width - 80, height - 80);
        
        // Header section with gradient background
        const headerHeight = 300;
        const gradient = ctx.createLinearGradient(0, 0, width, headerHeight);
        gradient.addColorStop(0, '#4e73df');
        gradient.addColorStop(1, '#224abe');
        ctx.fillStyle = gradient;
        ctx.fillRect(60, 60, width - 120, headerHeight);
        
        // Company name in header
        ctx.fillStyle = '#ffffff';
        ctx.textAlign = 'center';
        ctx.font = 'bold 90px Arial, sans-serif';
        ctx.fillText({!! json_encode($company->name) !!}, width / 2, 180);
        
        @if($branch)
        // Branch name in header
        ctx.font = '60px Arial, sans-serif';
        ctx.fillText({!! json_encode($branch->name) !!} + ' Branch', width / 2, 280);
        @endif
        
        // Branch Contact Details Section (below header)
        let detailsY = headerHeight + 100;
        @if($branch)
        ctx.fillStyle = '#2c3e50';
        ctx.font = 'bold 50px Arial, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('Branch Information', width / 2, detailsY);
        
        ctx.font = '42px Arial, sans-serif';
        ctx.fillStyle = '#555555';
        detailsY += 70;
        
        @if($branch->phone)
        ctx.fillText('📞 ' + {!! json_encode($branch->phone) !!}, width / 2, detailsY);
        detailsY += 60;
        @endif
        
        @if($branch->email)
        ctx.fillText('✉️ ' + {!! json_encode($branch->email) !!}, width / 2, detailsY);
        detailsY += 60;
        @endif
        
        @if($branch->address)
        // Handle long addresses by wrapping text
        const address = {!! json_encode($branch->address) !!};
        const maxWidth = width - 400;
        const words = address.split(' ');
        let line = '';
        let lines = [];
        
        for (let word of words) {
            const testLine = line + word + ' ';
            const metrics = ctx.measureText(testLine);
            if (metrics.width > maxWidth && line !== '') {
                lines.push(line);
                line = word + ' ';
            } else {
                line = testLine;
            }
        }
        lines.push(line);
        
        ctx.fillText('📍 ' + lines[0].trim(), width / 2, detailsY);
        detailsY += 60;
        for (let i = 1; i < lines.length; i++) {
            ctx.fillText('    ' + lines[i].trim(), width / 2, detailsY);
            detailsY += 60;
        }
        @endif
        
        detailsY += 40; // Extra spacing after branch details
        @else
        detailsY = headerHeight + 80; // Less spacing if no branch
        @endif
        
        // QR Code section
        const qrSize = 1200;
        const qrX = (width - qrSize) / 2;
        const qrY = detailsY + 80;
        
        // White background for QR code
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(qrX - 40, qrY - 40, qrSize + 80, qrSize + 80);
        
        // Border around QR code
        ctx.strokeStyle = '#4e73df';
        ctx.lineWidth = 8;
        ctx.strokeRect(qrX - 40, qrY - 40, qrSize + 80, qrSize + 80);
        
        // Draw the QR code
        ctx.drawImage(img, qrX, qrY, qrSize, qrSize);
        
        // "Scan to Check-in" text
        ctx.fillStyle = '#2c3e50';
        ctx.font = 'bold 80px Arial, sans-serif';
        ctx.textAlign = 'center';
        const instructionY = qrY + qrSize + 150;
        @if($branch)
        ctx.fillText('Scan to fill visitor form at ' + {!! json_encode($branch->name) !!}, width / 2, instructionY);
        @else
        ctx.fillText('Scan to fill visitor form', width / 2, instructionY);
        @endif
        
        // URL section
        ctx.fillStyle = '#7f8c8d';
        ctx.font = '45px Arial, sans-serif';
        const urlText = {!! json_encode($branch ? route('qr.scan', ['company' => $company, 'branch' => $branch]) : route('qr.scan', $company)) !!};
        ctx.fillText(urlText, width / 2, instructionY + 100);
        
        // Instructions section
        ctx.fillStyle = '#34495e';
        ctx.font = '50px Arial, sans-serif';
        ctx.textAlign = 'left';
        const instructionsX = 200;
        let instructionsY = instructionY + 250;
        
        ctx.fillText('How to use:', instructionsX, instructionsY);
        
        ctx.font = '45px Arial, sans-serif';
        ctx.fillStyle = '#555555';
        instructionsY += 80;
        ctx.fillText('1. Open your phone camera or QR scanner app', instructionsX + 50, instructionsY);
        instructionsY += 70;
        ctx.fillText('2. Point the camera at this QR code', instructionsX + 50, instructionsY);
        instructionsY += 70;
        ctx.fillText('3. Tap the notification to open the check-in form', instructionsX + 50, instructionsY);
        instructionsY += 70;
        ctx.fillText('4. Fill in your details and submit', instructionsX + 50, instructionsY);
        instructionsY += 70;
        ctx.fillText('5. Wait for approval from the admin', instructionsX + 50, instructionsY);
        
        // Important notice
        instructionsY += 100;
        ctx.fillStyle = '#e74c3c';
        ctx.font = 'bold 48px Arial, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('⚠️ Please wait for approval after submitting your details', width / 2, instructionsY);
        
        // Footer
        ctx.fillStyle = '#95a5a6';
        ctx.font = '40px Arial, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('Visitor Management System', width / 2, height - 100);
        
        // Create download link
        const link = document.createElement('a');
        const fileName = '{{ $branch ? Str::slug($company->name . '-' . $branch->name) : Str::slug($company->name) }}-visitor-checkin-qr.png';
        link.download = fileName;
        link.href = canvas.toDataURL('image/png', 1.0);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Clean up
        URL.revokeObjectURL(url);
    };
    
    img.src = url;
}
</script>
@endpush

@endsection
