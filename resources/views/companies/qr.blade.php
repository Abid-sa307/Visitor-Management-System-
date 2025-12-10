@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    @if(isset($branch) && $branch)
                        <i class="fas fa-code-branch me-2"></i> {{ $branch->name }}
                        <small class="text-muted ms-2">({{ $company->name }})</small>
                    @else
                        <i class="fas fa-building me-2"></i> {{ $company->name }}
                    @endif
                </h5>
                <div>
                    <a href="{{ route('qr-management.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                    @php
                        $routeName = isset($branch) && $branch ? 'companies.branches.qr.download' : 'companies.qr.download';
                        $routeParams = isset($branch) && $branch ? ['company' => $company, 'branch' => $branch] : $company;
                    @endphp
                    <a href="{{ route($routeName, $routeParams) }}" 
                       class="btn btn-sm btn-primary ms-2">
                        <i class="fas fa-download me-1"></i> Download QR
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body text-center py-5">
            <div class="mb-4">
                {!! $qrCode !!}
            </div>
            
            <h4 class="mb-3">Scan this QR code to check-in visitors</h4>
            
            <div class="input-group mb-4" style="max-width: 500px; margin: 0 auto;">
                <input type="text" class="form-control" value="{{ $url }}" id="qrUrl" readonly>
                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">
                    <i class="fas fa-copy me-1"></i> Copy Link
                </button>
            </div>
            
            <div class="text-muted small">
                <p class="mb-1">Place this QR code at your {{ isset($branch) && $branch ? 'branch' : 'company' }} entrance for visitors to scan</p>
                <p class="mb-0">Visitors will be directed to: <code>{{ parse_url($url, PHP_URL_PATH) }}</code></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(event) {
    const copyText = document.getElementById("qrUrl");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    
    // Show tooltip or alert
    const originalText = event.target.innerHTML;
    event.target.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
    const button = event.target;
    setTimeout(() => {
        button.innerHTML = originalText;
    }, 2000);
}
</script>
@endpush
@endsection