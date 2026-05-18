<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

<style>
    .dataTables_wrapper {
        padding: 0;
    }

    table.dataTable thead th {
        background-color: #f9fafb;
        color: #374151;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 12px 16px;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }

    table.dataTable tbody td {
        padding: 12px 16px;
        font-size: 0.875rem;
        color: #4b5563;
    }

    table.dataTable tbody tr:hover {
        background-color: #f9fafb;
    }

    .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        margin-left: 0.5rem;
        font-size: 0.875rem;
        background-color: #ffffff;
        color: #374151;
    }

    .dataTables_filter input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }

    .dataTables_length select {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.5rem;
        margin: 0 0.5rem;
        font-size: 0.875rem;
        background-color: #ffffff;
        color: #374151;
    }

    .paginate_button {
        padding: 0.5rem 0.75rem !important;
        margin: 0 0.25rem !important;
        border-radius: 0.375rem !important;
        border: 1px solid #d1d5db !important;
        background-color: white !important;
        color: #374151 !important;
    }

    .paginate_button:hover {
        background-color: #f3f4f6 !important;
        border-color: #9ca3af !important;
    }

    .paginate_button.current {
        background-color: #6366f1 !important;
        color: white !important;
        border-color: #6366f1 !important;
    }

    @media (max-width: 768px) {

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            text-align: left;
            margin-bottom: 0.5rem;
        }

        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 100%;
            max-width: 200px;
        }

        table.dataTable {
            min-width: 800px;
        }
    }

    .dataTables_scrollHead {
        overflow-x: auto !important;
    }

    .dataTables_scrollBody {
        overflow-x: auto !important;
    }

    .dataTables_scroll {
        overflow: hidden;
    }

    .table-scroll-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .dropdown-menu {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 0.25rem;
        background-color: white;
        min-width: 120px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 0.5rem;
        z-index: 1000;
        overflow: hidden;
    }

    .dropdown-content.show {
        display: block;
    }

    .dropdown-content a {
        color: #374151;
        padding: 0.75rem 1rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        transition: background-color 0.2s;
    }

    .dropdown-content a:hover {
        background-color: #f3f4f6;
    }

    .dropdown-content a svg {
        width: 1rem;
        height: 1rem;
    }

    .three-dot-btn {
        padding: 0.5rem;
        background-color: #f3f4f6;
        border-radius: 0.5rem;
        transition: background-color 0.2s;
        cursor: pointer;
        border: none;
    }

    .three-dot-btn:hover {
        background-color: #e5e7eb;
    }

    .three-dot-btn:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5);
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background-color: #e5e7eb;
        border-radius: 9999px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background-color: #10b981;
        transition: width 0.3s ease;
    }

    .progress-bar-fill.incomplete {
        background-color: #6366f1;
    }

    @media (max-width: 640px) {
        .dropdown-content {
            min-width: 100px;
        }
    }
</style>