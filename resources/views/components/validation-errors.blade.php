@if ($errors->any())
    <div class="flex p-4 mb-4 text-sm text-red-700 rounded-base bg-red-50 border border-danger-subtle" role="alert">
        <svg class="w-4 h-4 me-2 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span class="sr-only">Danger</span>
        <div>
            <span class="font-medium">Error Validation:</span>
            <ul class="mt-2 list-disc list-outside space-y-1 ps-2.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
