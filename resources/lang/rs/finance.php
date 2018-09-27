<?php

return [

    'no_customer' => 'No Customer',
    'select_customer' => 'Select a Customer...',
    'select_or_search_invoice' => 'Select or Search for a Invoice...',
    'select_invoice_status' => 'Select a Status',
    'invoice_delete_message' => 'Are you sure that you would like to delete the invoice ?',
    'notifications' => [
        'error' => 'Error',
        'sent' => 'Sent',
        'invoice_deleted' => 'Invoice Deleted',
        'subscription_activated ' => 'Subscription is activated',
        'subscription_deactivated' => 'Subscription is de-activated',
        'state_updated' => 'Invoice State Updated',
    ],
    'archive' => [
        'col' => [
            'invoice_no' => 'Invoice No.',
            'customer' => 'Customer Name',
            'status' => 'Status',
            'totals' => 'Totals',
            'action' => 'Action'
        ],
        'button' => [
            'preview' => 'Preview',
            'download' => 'Download',
            'unarchive' => 'Un-archive',
            'clone_draft' => 'Clone as New Draft',
            'delete_permanently' => 'Delete permanently'
        ]
    ],
    'draft' => [
        'col' => [
            'customer' => 'Customer Name',
            'invoice_date' => 'Invoice Date',
            'totals' => 'Totals',
            'action' => 'Action'
        ],
        'button' => [
            'edit' => 'Edit',
            'download' => 'Download',
            'unarchive' => 'Un-archive',
            'clone_draft' => 'Clone as New Draft',
            'delete_permanently' => 'Delete permanently'
        ],
        'not_assigned' => 'Not Assigned',
        'no_date' => 'No Date'
    ],
    'subscription' => [
        'col' => [
            'customer' => 'Customer Name',
            'invoice_date' => 'Invoice Date',
            'totals' => 'Totals',
            'action' => 'Action'
        ],
        'button' => [
            'edit' => 'Edit',
            'download' => 'Download',
            'unarchive' => 'Un-archive',
            'clone_subscription' => 'Clone as New Subscription',
            'delete_permanently' => 'Delete permanently'
        ],
        'not_assigned' => 'Not Assigned',
        'no_date' => 'No Date'
    ],
    'expense' => [
        'title' => 'EXPENSE SLIP',
        'col' => [
            'category' => 'Category',
            'expense_date' => 'Expense Date',
            'totals' => 'Totals',
            'action' => 'Action',
        ],
        'button' => [
            'edit' => 'Edit',
            'download' => 'Download',
            'delete_permanently' => 'Delete permanently',
        ],
        'uncategorized' => 'Uncategorized',
        'not_assigned' => 'Not Assigned',
        'no_date' => 'No Date',
        'expense_date' => 'Expense Date:',
        'expense_category' => 'Expense Category',
        'slip' => [
            'col' => [
                'units' => 'Units',
                'product_service' => 'Product / Service',
                'description' => 'Description',
                'price' => 'Price'
            ],
            'qty' => 'Qty',
            'product_service' => 'Enter the service / product here',
            'description' => 'Enter the service / product description here',
            'price' => 'Price per unit',
            'add_item' => 'Add Item',
            'expense_notes' => 'Expense Notes:',
            'subtotals' => 'Subtotals',
            'line_description' => 'Line Description',
            'discount_credit' => 'Discount % or credit',
            'new_pre_tax' => 'New pre-tax line',
            'taxable' => 'Taxable',
            'total_due' => 'Total Due',
            'tax_credit' => 'Tax % or credit',
            'new_tax_line' => 'New tax line',
            'expense_currency' => 'Expense Currency'
        ],
    ],
    'invoice' => [
        'col' => [
            'invoice_no' => 'Invoice No.',
            'customer' => 'Customer Name',
            'status' => 'Status',
            'totals' => 'Totals',
            'action' => 'Action',
        ],
        'button' => [
            'preview' => 'Preview',
            'download' => 'Download',
            'archive' => 'Send to Archive',
            'clone_as_draft' => 'Clone as New Draft',
            'delete_permanently' => 'Delete permanently',
        ],
    ],
    'quote' => [
        'title' => 'QUOTE',
        'col' => [
            'invoice_no' => 'Invoice No.',
            'customer' => 'Customer Name',
            'status' => 'Status',
            'quote_date' => 'Quote Date',
            'totals' => 'Totals',
            'action' => 'Action'
        ],
        'button' => [
            'preview' => 'Preview',
            'download' => 'Download',
            'archive' => 'Send to Archive',
            'clone_as_draft' => 'Clone as New Draft',
            'delete_permanently' => 'Delete permanently'
        ],
        'uncategorized' => 'Uncategorized',
        'not_assigned' => 'Not Assigned',
        'no_date' => 'No Date',
    ],

    'invoice_date' => 'Invoice Date:',
    'due_date' => 'Due Date:',
    'new_invoicing_date' => 'Next Invoicing Date:',
    'due_days' => 'Invoice Due Days:',
    'subscription_end' => 'Subscription End',
    'billing_occurance' => 'Billing Occurance:',
    'invoicing_currency' => 'Invoicing Currency',
    'quote_date' => 'Quote Date:',
    'select_date' => 'Select a date',
    'enter_days' => 'Enter Days',
    'our_info' => 'Our Information',
    'phone' => 'Phone:',
    'bill_to' => 'Bill To',
    'quote_to' => 'Quote To',
    'vat' => 'VAT:',

    'slip' => [
        'col' => [
            'units' => 'Units',
            'product_service' => 'Product / Service',
            'description' => 'Description',
            'price' => 'Price'
        ],
        'button' => [
            'download' => 'Download',
            'save' => 'Save',
            'resend' => 'Re-Send',
            'preview' => 'Preview',
            'activate_subscription' => 'Activate Subscription',
            'de_activate_subscription' => 'De-Activate Subscription',
            'issue_send' => 'Issue & Send'
        ],
        'qty' => 'Qty',
        'product_service' => 'Enter the service / product here',
        'description' => 'Enter the service / product description here',
        'price' => 'Price per unit',
        'add_item' => 'Add Item',
        'expense_notes' => 'Expense Notes:',
        'subtotals' => 'Subtotals',
        'legal_notes' => 'Legan Notes:',
        'legal_notes_text' => '(appears below the totals on the invoice)',
        'amount' => 'Amount:',
        'notes' => 'Notes:',
        'line_description' => 'Line Description',
        'discount_credit' => 'Discount % or credit',
        'new_pre_tax' => 'New pre-tax line',
        'taxable' => 'Taxable',
        'total_due' => 'Total Due',
        'tax_credit' => 'Tax % or credit',
        'new_tax_line' => 'New tax line',
        'expense_currency' => 'Expense Currency'
    ],
    'side_menu' =>[
        'invoices' => 'Invoices',
        'draft_invoices' => 'Drafts Invoices',
        'new_invoice' => 'New Invoice',
        'invoice_archive' => 'Invoices Archive',
        'subscriptions' => 'Subscriptions',
        'new_subscription' => 'New Subscription',
        'quotes' => 'Quotes',
        'new_quote' => 'New Quote',
        'expenses' => 'Expenses',
        'new_expense' => 'New Expense',
    ],
    'invoice_state' => [
        'COLLECT' => 'Collection',
        'STORNO'  => 'Canceled',
        'CREDIT'  => 'Credit Note',
        'DEBT'    => 'Debt',
        'FRAUD'   => 'Fraud',
        'PAID'    => 'Paid',
        'UNPAID'  => 'Unpaid',
    ]
];
