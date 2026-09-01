{{-- Generic modal shell reused for quick-view, confirmations and AJAX forms. --}}
<div id="dg-modal" class="fixed inset-0 z-[90] hidden" role="dialog" aria-modal="true" aria-labelledby="dg-modal-title">
    <div class="modal-backdrop" data-modal-backdrop></div>
    <div class="fixed inset-0 grid place-items-center overflow-y-auto p-4">
        <div class="modal-box max-w-3xl" data-modal-box>
            <div class="sticky top-0 z-10 flex items-center justify-between gap-3 border-b border-ink-100 bg-white px-5 py-4">
                <h3 id="dg-modal-title" class="truncate text-base font-extrabold text-ink-900"></h3>
                <button type="button" class="btn-icon h-8 w-8" data-modal-close aria-label="بستن">
                    <x-icon name="close" class="h-5 w-5" />
                </button>
            </div>
            <div id="dg-modal-body" class="p-5"></div>
        </div>
    </div>
</div>

{{-- Confirmation dialog used before destructive actions. --}}
<div id="dg-confirm" class="fixed inset-0 z-[95] hidden" role="alertdialog" aria-modal="true">
    <div class="modal-backdrop" data-modal-backdrop></div>
    <div class="fixed inset-0 grid place-items-center p-4">
        <div class="modal-box max-w-sm" data-modal-box>
            <div class="p-6 text-center">
                <span class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-full bg-brand-50 text-brand-500">
                    <x-icon name="alert" class="h-7 w-7" />
                </span>
                <h3 data-confirm-title class="text-base font-extrabold text-ink-900">آیا مطمئن هستید؟</h3>
                <p data-confirm-message class="mt-2 text-sm leading-7 text-ink-500"></p>
            </div>
            <div class="flex gap-2 border-t border-ink-100 bg-ink-50 px-5 py-3.5">
                <button type="button" class="btn-ghost flex-1" data-confirm-cancel>انصراف</button>
                <button type="button" class="btn-primary flex-1" data-confirm-accept>تأیید</button>
            </div>
        </div>
    </div>
</div>
