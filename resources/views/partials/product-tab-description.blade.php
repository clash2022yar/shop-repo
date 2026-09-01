<div class="animate-fade-in">
    <h2 class="mb-4 text-base font-extrabold text-ink-900">معرفی {{ $product->name }}</h2>

    @if($product->short_description)
        <p class="mb-4 rounded-card bg-ink-50 p-4 text-[0.8125rem] leading-8 text-ink-700">
            {{ $product->short_description }}
        </p>
    @endif

    @if($product->description)
        <div class="max-w-none space-y-4 text-[0.8125rem] leading-8 text-ink-700">
            @foreach(preg_split('/\n\s*\n/', trim($product->description)) as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>
    @else
        <p class="text-sm text-ink-500">توضیحات تکمیلی برای این کالا هنوز ثبت نشده است.</p>
    @endif

    @if(filled($product->highlights))
        <div class="mt-6">
            <h3 class="mb-3 text-sm font-bold text-ink-900">نکات مهم درباره این کالا</h3>
            <ul class="grid gap-2.5 sm:grid-cols-2">
                @foreach($product->highlights as $highlight)
                    <li class="flex items-start gap-2 rounded-field bg-ink-50 p-3 text-2xs leading-6 text-ink-700">
                        <x-icon name="check-circle" class="mt-0.5 h-4 w-4 shrink-0 text-success-500" />
                        {{ $highlight }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
