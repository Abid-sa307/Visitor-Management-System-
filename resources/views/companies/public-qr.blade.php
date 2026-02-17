@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 overflow-hidden">
                <!-- Header with Gradient Background -->
                <div class="bg-primary bg-gradient text-white p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <div class="mb-3 mb-md-0">
                            <h2 class="h3 mb-1">{{ $company->name }}
                                @if($branch)
                                    <span class="d-block h5 mt-2">{{ $branch->name }} Branch</span>
                                @endif
                            </h2>
                            <p class="mb-0 opacity-75">
                                @if($branch)
                                    Branch Check-in Portal
                                @else
                                    Visitor Check-in Portal
                                @endif
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light text-primary" onclick="downloadQRCode()">
                                <i class="fas fa-download me-2"></i> Download QR
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="row g-0">
                    <!-- QR Code Section -->
                    <div class="col-md-6 p-4 d-flex flex-column">
                        <div class="text-center mb-4">
                            <div class="d-inline-block p-3 bg-white rounded shadow-sm mb-3">
                                {!! $qrCode !!}
                            </div>
                            <h4 class="mb-3">
                                @isset($branchName)
                                    Scan to Visitor Form at {{ $branchName }}
                                @else
                                    Scan to Visitor Form
                                @endisset
                            </h4>
                            <p class="text-muted">
                                @isset($branchName)
                                    Visitors can scan this code with their phone's camera to check-in at this location
                                @else
                                    Visitors can scan this code with their phone's camera to check-in
                                @endisset
                            </p>
                        </div>
                    </div>
                    
                    <!-- Link Section -->
                    <div class="col-md-6 bg-light p-4">
                        <div class="h-100 d-flex flex-column">
                            <h5 class="mb-3">Share Check-in Link</h5>
                            <div class="mb-3">
                                <label class="form-label">Direct Link</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="qrUrl" value="{{ $url }}" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()" 
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Copy to clipboard">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                <div class="form-text">Share this link directly with visitors</div>
                            </div>
                            
                            <div class="mt-auto pt-3 border-top">
                                <h6 class="mb-3">How to use:</h6>
                                <ol class="small text-muted">
                                    <li class="mb-2">Place the QR code at your entrance or reception</li>
                                    <li class="mb-2">Visitors scan with their phone camera or QR code app</li>
                                    <li class="mb-2">They'll be directed to the check-in form</li>
                                    <li>You'll be notified when visitors check-in</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Options -->
                <div class="card-footer bg-white p-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i> 
                            Need help? Contact support at {{ $company->email ?? 'your support email' }}
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary" onclick="printPage()">
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .bg-gradient {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
    .qr-container {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .step-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background-color: #4e73df;
        color: white;
        border-radius: 50%;
        font-size: 0.75rem;
        margin-right: 0.5rem;
    }
    @media print {
        .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

function copyToClipboard() {
    const copyText = document.getElementById("qrUrl");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    
    // Show tooltip
    const tooltip = bootstrap.Tooltip.getInstance(event.target);
    if (tooltip) {
        const originalTitle = event.target.getAttribute('data-bs-original-title');
        event.target.setAttribute('data-bs-original-title', 'Copied!');
        tooltip.show();
        
        setTimeout(() => {
            event.target.setAttribute('data-bs-original-title', originalTitle);
            tooltip.hide();
        }, 2000);
    }
}

function downloadQRCode() {
    // Get the SVG element
    const svg = document.querySelector('svg');
    const svgData = new XMLSerializer().serializeToString(svg);
    
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
        ctx.fillText('Scan to Visitor Form at ' + {!! json_encode($branch->name) !!}, width / 2, instructionY);
        @else
        ctx.fillText('Scan to Visitor Form', width / 2, instructionY);
        @endif
        
        // URL section
        ctx.fillStyle = '#7f8c8d';
        ctx.font = '45px Arial, sans-serif';
        const urlText = {!! json_encode($url) !!};
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
        ctx.fillText('Visitor Management System (Developed by NNT Softwares)', width / 2, height - 100);
        
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

function printPage() {
    window.print();
}
</script>
@endpush
@endsection
