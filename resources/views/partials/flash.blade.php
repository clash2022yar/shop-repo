{{-- Server-side flash messages (page loads). AJAX uses the toast system. --}}
@foreach(['success' => 'check-circle', 'error' => 'x-circle', 'warning' => 'alert', 'info' => 'info'] as $type => $icon)
    @if(session($type))
        @php
            $tones = [
                'success' => 'bg-success-50 text-success-700 ring-success-100',
                'error'   => 'bg-brand-50 text-brand-700 ring-brand-100',
                'warning' => 'bg-warning-50 text-warning-600 ring-warning-100',
                'info'    => 'bg-info-50 text-info-600 ring-info-100',
            ];
        @endphp
        <div class="mt-4 flex items-start gap-3 rounded-card px-4 py-3 text-sm font-medium ring-1 animate-fade-down {{ $tones[$type] }}"
             role="status">
            <x-icon :name="$icon" class="mt-0.5 h-5 w-5 shrink-0" />
            <p class="flex-1 leading-6">{{ session($type) }}</p>
            <button type="button" class="shrink-0 opacity-60 transition-opacity hover:opacity-100"
                    onclick="this.closest('div').remove()" aria-label="بستن">
                <x-icon name="close" class="h-4 w-4" />
            </button>
        </div>
    @endif
@endforeach

@if($errors->any() && !request()->ajax())
    <div class="mt-4 rounded-card bg-brand-50 px-4 py-3 text-sm ring-1 ring-brand-100 animate-fade-down">
        <p class="mb-1 flex items-center gap-2 font-bold text-brand-700">
            <x-icon name="alert" class="h-5 w-5" /> لطفاً خطاهای زیر را برطرف کنید:
        </p>
        <ul class="list-inside list-disc space-y-0.5 ps-6 text-2xs text-brand-600">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif
