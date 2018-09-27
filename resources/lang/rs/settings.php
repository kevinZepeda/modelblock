<?php

return [

    'questionnaire' => [
        'saved_changes' => 'Changes were saved',
        'ups' => 'Upssss',
        'share_preview_url' => 'You can share the preview url now',
        'share_upublished' => 'Template is un-published',
        'unpublish' => 'Unpublish',
        'publish' => 'Publish',
        'saved' => 'Saved',
        'save' => 'Save',
        'name' => 'Type here your questionnarie name',
        'preview' => 'Preview',
        'qa_input_message' => 'Are you sure that you would like to delete Questionnaire Input ?',
        'deleted' => 'Questionnaire was deleted',
        'failure' => 'Opps something\' not right:',
        'failure_message' => 'This one might have been remove, refresh and check otherwise contact he administrator'
    ],
    'categories' => [
        'title' => 'Expense Categories',
        'category_name' => 'Category Name',
        'delete_message' => 'Are you sure you would like to delete the category ?',
        'explanation' => '<strong>Any change </strong> will immediately be reflected to the expense slips.',
    ],
    'finance' => [
        'title' => 'Default Invoice Settings',
        'logo' => [
            'title' => 'Logo',
            'click_to_edit' => 'Click on edit to preview',
            'drop_here' => 'Drop your Logo here or click to upload.',
            'allowed_files' => '(only <strong>jpg</strong>, <strong>jpeg</strong>, <strong>png</strong> and <strong>gif</strong> file types are allowed)',
        ],
        'notes' => [
            'title' => 'Notes',
            'click_to_edit' => 'Click to edit default invoice notes',
            'legal_notes' => 'Legal Notes',
            'other_notes' => 'Other Notes',
            'explanation' => '<strong>Changing the notes</strong> will not reflect on already created invoices, drafts and quotes.',
        ],
        'number_format' => [
            'title' => 'Number Format',
            'prefix' => 'Prefix',
            'prefix_not_set' => 'Prefix is not set',
            'and' => 'and',
            'padding_digits' => 'padding digits',
            'invoice_prefix' => 'Invoice Prefix',
            'post_len' => 'Invoice Postfix Length',
            'explanation' => '<strong>Changing the Prefix and Postfix</strong> will not reflect on already issued invoices.'
        ],
        'layout_color' => [
            'title' => 'Layout Color',
            'explanation' => '<strong>Invoice Layout Color</strong> won\'t reflect on already issued invoices.',
        ],
        'currency' => [
            'title' => 'Currency',
            'select_currency' => 'Select a Currency',
            'explanation' => '<strong>Currency</strong> reflects on the invoices and the displayed currency symbol.'
        ],
        'language' => [
            'title' => 'Invoice Language',
            'select_language' => 'Select a Language',
            'explanation' => '<strong>Language</strong> won\'t reflect on already created drafts, invoices and subscriptions.'
        ]
    ],
    'general' => [
        'title' => 'General Settings',
        'system_label' => [
            'title' => 'System Label',
            'system_label' => 'System Label',
            'explanation' => '<strong>Changin the System Label</strong> will reflect immediately and it will be public.',
        ],
        'account_holder' => [
            'title' => 'Account/ Holder',
            'explanation' => '<strong>Changing the Account Details</strong> will not reflect on invoices that were already issued.',
            'input' => [
                'name' => 'Company or Your Name',
                'vat' => 'VAT Number',
                'country' => 'Select a Country',
                'city' => 'City',
                'address' => 'Address',
                'zip' => 'Postal Code',
                'phone' => 'Phone Number',
                'email' => 'Email Address'
            ]
        ],
        'timezone' => [
            'title' => 'Timezone',
            'select_timezone' => 'Select Timezone',
            'explanation' => '<strong>Changing the Timezone</strong> will immediately reflect on your account configurations and subscriptions.',
        ]
    ],
    'manage_columns' => [
        'title' => 'Board Templates',
        'board_name' => 'Board Name',
        'explanation' => '<strong>By editing the column names</strong> you won\'t affect on already created tables.',
    ],
    'manage_users' => [
        'title' => 'Manage Users',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'user' => 'Staff',
        'admin' => 'Administrator',
        'manager' => 'Manager',
        'explanation' => '<strong>Any change </strong> will immediately be reflected to the users account.',
        'delete_user_message' => 'Are you sure you would like to delete the user ?',
        'input' => [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email_address' => 'Email Address',
        ]
    ],
    'new_category' => [
        'title' => 'New Expense Category',
        'explanation' => '<strong>Expense Category</strong> can be used in the expenses section for categorization.',
        'input' => [
            'category_name' => 'Expense Category Name'
        ]
    ],
    'new_board' => [
        'title' => 'New Board Template',
        'explanation' => '<strong>Board Template</strong> gives you an ability to set differnet pre-defined columns set.',
        'input' => [
            'board_template_name' => 'Board Template Name',
        ]
    ],
    'new_user' => [
        'title' => 'New User',
        'input' => [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email_address' => 'Email Address',
            'password' => 'Password'
        ],
        'user_level' => 'User Level',
        'explanation1' => '<strong>Staff User</strong> has full control of his time entries and tasks.',
        'explanation2' => '<strong>Manager User</strong> has full control of all Standard Users, time entries, boards and tasks.',
        'explanation3' => '<strong>Administrator User</strong> has full control of all users, system settings, time entries, boards and tasks.',
    ],
    'email_notifications' => [
        'title' => 'Email Notifications',
        'note' => 'Note',
        'on' => 'On',
        'off' => 'Off',
        'notifications_types' => [
            'NOTE_TYPE_UPDATE'          => 'Type Update',
            'NOTE_SUBJECT_UPDATE'       => 'Subject Update',
            'NOTE_STATE_UPDATE'         => 'State Update',
            'NOTE_PROJECT_UPDATE'       => 'Project Update',
            'NOTE_PRIORITY_UPDATE'      => 'Priority Update',
            'NOTE_OWNER_UPDATE'         => 'Owner Update',
            'NOTE_MANAGER_UPDATE'       => 'Manager Update',
            'NOTE_ESTIMATE_UPDATE'      => 'Estimate Update',
            'NOTE_DESCIPRITON_UPDATE'   => 'Description Update',
            'NOTE_COMMENT_UPDATE'       => 'New Comment'
        ]
    ],
    'profile' => [
        'title' => 'Account Profile',
        'languages' => [
            'ENG' => 'English',
            'DEU' => 'German',
            'SRP' => 'Serbian'
        ],
        'avatar' => [
            'title' => 'Avatar',
            'click_to_edit_preview' => 'Click on edit to preview',
            'dropzone' => 'Drop your Image here or click to upload.',
            'dropzone_restrictions' => '(only <strong>jpg</strong>, <strong>jpeg</strong>, <strong>png</strong> and <strong>gif</strong> file types are allowed)'
        ],
        'full_name' => [
            'title' => 'Full Name',
            'input' => [
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
            ],
            'explanation' => '<strong>Changing you first or last name</strong> will also affect the way your full name is displayed in the historical data.'
        ],
        'email' => [
            'title' => 'Email',
            'message' => '(Only the admin can change the email)'
        ],
        'password' => [
            'title' => 'Password',
            'message' => 'It\'s recommended to change the password every 2 months...',
            'explanation' => '<strong>The Password change</strong> will reflect immediately on your account.',
            'input' => [
                'current_password' => 'Current Password',
                'new_password' => 'New Password',
                're_new_password' => 'Repeat the new Password'
            ]
        ],
        'language' => [
            'title' => 'Language',
            'explanation' => '<strong>Changing the Language</strong> will affect only on the system labels.',
        ],
    ],
    'left_menu' => [
        'user_profile' => 'User Profile',
        'general' => 'General',
        'finance' => 'Finance',
        'expense_category' => 'Expense Categories',
        'new_category' => 'New Category',
        'manage_users' => 'Manage Users',
        'new_user' => 'New User',
        'manage_boards' => 'Manage Boards',
        'new_board' => 'New Board',
        'manage_questionnaries' => 'Manage Questionnaires',
        'new_questionnaries' => 'New Questionnaire',
        'notifications' => 'Notifications'
    ],
    'questionnarie_list' => [
        'col' => [
            'name' => 'Questionarie Name',
            'public' => 'Public',
            'action' => 'Action'
        ],
        'yes' => 'Yes',
        'no' => 'No',
        'button' => [
            'edit' => 'Edit',
            'delete' => 'Delete',
            'preview' => 'Preview'
        ]
    ],
    'button' => [
        'remove' => 'Remove',
        'edit' => 'Edit',
        'close' => 'Close',
        'cancel' => 'Cancel',
        'delete' => 'Delete',
        'create' => 'Create',
        'save' => 'Save',
        'reset' => 'Reset'
    ],
    'column_names' => 'Column Names',
    'error' => 'Error'
];
