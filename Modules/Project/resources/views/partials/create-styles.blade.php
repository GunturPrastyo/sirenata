<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* Select2 Custom Styling - Single Select */
    .select2-container--default .select2-selection--single {
        height: 42px;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px;
        color: #374151;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }

    .select2-dropdown {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #6366f1;
    }

    /* Select2 Multiple Selection Styling - IMPROVED */
    .select2-container--default .select2-selection--multiple {
        min-height: 42px;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.25rem 0.5rem;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }

    /* FIXED: Choice items with better layout */
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #6366f1;
        border: none;
        border-radius: 0.375rem;
        padding: 0.25rem 1.75rem 0.25rem 1.25rem;
        /* Extra right padding for remove button */
        color: white;
        font-size: 0.875rem;
        position: relative;
        margin-right: 0.375rem;
        margin-bottom: 0.25rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 200px;
        display: inline-flex;
        align-items: center;
        line-height: 1.4;
    }

    /* FIXED: Remove button positioned absolutely on the right */
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        position: absolute;
        right: 0.25rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.25rem;
        height: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: bold;
        line-height: 1;
        cursor: pointer;
        border-radius: 0.125rem;
        transition: all 0.2s ease;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fca5a5;
        background-color: rgba(255, 255, 255, 0.1);
    }

    /* Ensure text doesn't overflow */
    .select2-container--default .select2-selection--multiple .select2-selection__choice span {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        padding-right: 0.5rem;
    }

    /* Search field styling */
    .select2-container--default .select2-search--inline .select2-search__field {
        margin-top: 0.25rem;
        margin-left: 0.25rem;
        min-width: 100px;
        height: 26px;
    }

    /* For longer text in choices */
    .select2-container--default .select2-selection--multiple .select2-selection__choice:hover {
        overflow: visible;
        white-space: normal;
        z-index: 10;
    }
</style>