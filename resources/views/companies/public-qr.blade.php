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
                                @isset($branchName)
                                    <span class="d-block h5 mt-2">{{ $branchName }} Branch</span>
                                @endisset
                            </h2>
                            <p class="mb-0 opacity-75">
                                @isset($branchName)
                                    Branch Check-in Portal
                                @else
                                    Visitor Check-in Portal
                                @endisset
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
                                    Scan to Check-in at {{ $branchName }}
                                @else
                                    Scan to Check-in
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
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const size = 1000; // High resolution for print quality
    canvas.width = size;
    canvas.height = size;
    
    const img = new Image();
    const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
    const url = URL.createObjectURL(svgBlob);
    
    img.onload = function() {
        // Create a white background
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Calculate size and position to center the QR code with padding
        const padding = size * 0.1; // 10% padding
        const qrSize = size - (padding * 2);
        
        // Draw the QR code
        ctx.drawImage(img, padding, padding, qrSize, qrSize);
        
        // Add company name below QR code
        ctx.fillStyle = '#000';
        ctx.textAlign = 'center';
        ctx.font = `bold ${size * 0.04}px Arial`;
        ctx.fillText('{{ addslashes($company->name) }}', size / 2, size - (padding * 0.5));
        
        // Create download link
        const link = document.createElement('a');
        const fileName = '{{ isset($branchName) ? Str::slug($company->name . ' ' . $branchName) : Str::slug($company->name) }}-checkin-qr.png';
        link.download = fileName;
        link.href = canvas.toDataURL('image/png');
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
