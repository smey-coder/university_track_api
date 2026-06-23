@extends('layouts.myapp')

@section('title', 'Scan QR Code')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%); min-height: 85vh;">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-5 col-lg-4">

            <div class="card border-0 shadow-sm rounded-4 text-center overflow-hidden">
                
                <div style="height: 6px; background: linear-gradient(90deg, #198754, #20c997);"></div>

                <div class="card-body p-4 p-sm-5">
                    
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle p-3 mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-qr-code-scan fs-3">📱</i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Attendance QR Code</h4>
                        <p class="text-muted small">Scan this QR to mark your attendance</p>
                    </div>

                    <div class="d-inline-block p-4 bg-white border rounded-4 shadow-sm mb-4 bg-light bg-opacity-50">
                        <div class="qr-wrapper p-2 bg-white rounded-3">
                            {!! QrCode::size(220)->generate($session->attendance_code) !!}
                        </div>
                    </div>

                    <div class="bg-light rounded-4 p-3 mb-2 border border-dashed">
                        <span class="text-muted d-block small mb-2 fw-medium text-uppercase tracking-wider">Session Code</span>
                        
                        <div class="d-flex flex-column gap-2">
                            <div class="fs-4 fw-mono text-dark bg-white py-2 px-3 rounded-3 border shadow-sm fw-bold">
                                {{ $session->attendance_code }}
                            </div>
                            
                            <button type="button"
                                    id="btnCopy"
                                    class="btn btn-dark w-100 py-2 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2 transition"
                                    onclick="copyCode()">
                                <span>📋 Copy Code</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
function copyCode() {
    const codeText = "{{ $session->attendance_code }}";
    const btn = document.getElementById('btnCopy');
    
    navigator.clipboard.writeText(codeText).then(() => {
        // Change button state to success
        btn.innerHTML = '<span>✅ Copied!</span>';
        btn.classList.remove('btn-dark');
        btn.classList.add('btn-success');
        
        // Reset button back to original state after 2 seconds
        setTimeout(() => {
            btn.innerHTML = '<span>📋 Copy Code</span>';
            btn.classList.remove('btn-success');
            btn.classList.add('btn-dark');
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}
</script>

<style>
    /* បន្ថែម Font Monospace តិចតួចដើម្បីឱ្យលេខកូដមើលទៅស្អាត */
    .fw-mono {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        letter-spacing: 1px;
    }
    .transition {
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection