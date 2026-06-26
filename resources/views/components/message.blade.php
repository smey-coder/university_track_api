@if(Session::has('success'))
<div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4">
    <div class="font-semibold text-green-700">
        ✅ Success
    </div>
    <div class="text-green-600">
        {{ Session::get('success') }}
    </div>
</div>
@endif

@if(Session::has('error'))
<div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4">
    <div class="font-semibold text-red-700">
        ❌ Error
    </div>
    <div class="text-red-600">
        {{ Session::get('error') }}
    </div>
</div>
@endif

@if(Session::has('warning'))
<div class="mb-4 rounded-xl border border-yellow-200 bg-yellow-50 p-4">
    <div class="font-semibold text-yellow-700">
        ⚠️ Warning
    </div>
    <div class="text-yellow-600">
        {{ Session::get('warning') }}
    </div>
</div>
@endif

@if(Session::has('info'))
<div class="mb-4 rounded-xl border border-blue-200 bg-blue-50 p-4">
    <div class="font-semibold text-blue-700">
        ℹ️ Information
    </div>
    <div class="text-blue-600">
        {{ Session::get('info') }}
    </div>
</div>
@endif