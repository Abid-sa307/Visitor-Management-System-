@extends('layouts.sb')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">QR Code Management</h1>
    </div>

    <!-- Company QR Code Section -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @if(isset($isSuperAdmin) && $isSuperAdmin)
                            Company QR Codes
                        @else
                            Your Company QR Code
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($isSuperAdmin) && $isSuperAdmin)
                        <div class="mb-4">
                            <label class="form-label">Select Company</label>
                            <select id="companySelector" class="form-select">
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ $company->id == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->name }} ({{ $comp->branches->count() }} branches)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    
                    <div class="text-center">
                        @if(isset($qrCode) && $qrCode)
                            <div class="mb-3" style="max-width: 300px; margin: 0 auto;">
                                <img src="{{ $qrCode }}" alt="Company QR Code" style="width: 100%; height: auto;" id="qrCodeImage">
                            </div>
                            <div class="mt-3">
                                <p class="mb-1"><strong>Company:</strong> <span id="companyName">{{ $company->name ?? 'N/A' }}</span></p>
                                <p class="mb-1"><strong>Branches:</strong> <span id="branchCount">{{ $company->branches->count() ?? 0 }}</span></p>
                                <p class="mb-1"><strong>Departments:</strong> {{ $company->departments->count() ?? 0 }}</p>
                            </div>
                        <div class="mt-3">
                            <button onclick="window.print()" class="btn btn-primary btn-sm">
                                <i class="fas fa-print fa-sm"></i> Print QR Code
                            </button>
                            <button onclick="downloadQRCode()" class="btn btn-success btn-sm download-btn">
                                <i class="fas fa-download fa-sm"></i> Download
                            </button>
                        </div>
                        
                        <script>
                        @if(isset($isSuperAdmin) && $isSuperAdmin)
                            document.addEventListener('DOMContentLoaded', function() {
                                const companySelector = document.getElementById('companySelector');
                                
                                companySelector.addEventListener('change', function() {
                                    const companyId = this.value;
                                    
                                    // Show loading state
                                    const qrCodeImage = document.getElementById('qrCodeImage');
                                    qrCodeImage.src = '{{ asset("images/loading.gif") }}';
                                    
                                    // Fetch company data
                                    fetch(`/api/company/${companyId}/qr-data`)
                                        .then(response => response.json())
                                        .then(data => {
                                            // Update QR code
                                            qrCodeImage.src = data.qrCode;
                                            
                                            // Update company info
                                            document.getElementById('companyName').textContent = data.company.name;
                                            document.getElementById('branchCount').textContent = data.company.branches_count;
                                            
                                            // Update download/print buttons
                                            document.querySelector('.download-btn').onclick = function() {
                                                window.location.href = `/qr-management/company/${companyId}/download`;
                                            };
                                            
                                            document.querySelector('.print-btn').onclick = function() {
                                                window.print();
                                            };
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('Error loading company data');
                                            qrCodeImage.src = '{{ $qrCode }}'; // Reset to default
                                        });
                                });
                            });
                        @endif

                        function downloadQRCode() {
                            const svg = document.querySelector('.card-body svg');
                            const serializer = new XMLSerializer();
                            let source = serializer.serializeToString(svg);
                            
                            // Add XML declaration and doctype for SVG
                            source = '<?xml version="1.0" standalone="no"?>\r\n' + source;
                            
                            // Convert SVG to Blob
                            const blob = new Blob([source], {type: 'image/svg+xml;charset=utf-8'});
                            const url = URL.createObjectURL(blob);
                            
                            // Create download link
                            const link = document.createElement('a');
                            link.href = url;
                            link.download = '{{ Str::slug($company->name ?? 'company') }}-qrcode.svg';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                            URL.revokeObjectURL(url);
                        }
                        </script>
                    @else
                        <div class="alert alert-warning">No QR code data available.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- QR Scanner Section -->
        <!-- <div class="col-lg-6"> -->
            <!-- <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Scan QR Code</h6>
                </div>
                <div class="card-body">
                    <div id="reader" style="width: 100%;"></div>
                    <div id="scan-result" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div> -->



@push('scripts')
<!-- Include the HTML5 QR Code library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resultContainer = document.getElementById('scan-result');
    
    function onScanSuccess(decodedText, decodedResult) {
        // Handle the scanned code
        console.log(`Code matched = ${decodedText}`, decodedResult);
        
        // Show loading state
        resultContainer.innerHTML = '<div class="alert alert-info">Processing QR code...</div>';
        
        // Send the scanned data to the server
        fetch('{{ route("company.qr.scan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                qr_data: decodedText
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultContainer.innerHTML = `
                    <div class="alert alert-success">
                        <strong>Success!</strong> ${data.message}
                        <div class="mt-2">${JSON.stringify(data.data)}</div>
                    </div>`;
                
                // Optional: Play success sound
                const audio = new Audio('{{ asset("sounds/beep.mp3") }}');
                audio.play().catch(e => console.log('Audio play failed:', e));
                
                // Optional: Continue scanning after 2 seconds
                setTimeout(() => {
                    resultContainer.innerHTML = '';
                }, 5000);
            } else {
                resultContainer.innerHTML = `
                    <div class="alert alert-danger">
                        <strong>Error:</strong> ${data.message || 'An error occurred while processing the QR code.'}
                    </div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultContainer.innerHTML = `
                <div class="alert alert-danger">
                    <strong>Error:</strong> Failed to process QR code. Please try again.
                </div>`;
        });
    }

    function onScanFailure(error) {
        // Handle scan failure (e.g., no QR code found)
        console.warn(`QR error = ${error}`);
    }

    // Initialize the QR scanner
    const html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        },
        /* verbose= */ false
    );
    
    // Start scanning
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    
    // Handle window resize
    window.addEventListener('resize', function() {
        // Re-render scanner on resize to adjust to new container size
        const container = document.getElementById('reader');
        if (container && container.innerHTML === '') {
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }
    });
});
</script>
@endpush

@endsection
