<?php

return [

    'notfound' => [
        'title' => 'Upsss something\'s wrong',
        'message' => 'Sorry but the Form you are looking for was not found or it was moved,
        please contact the Manager or the Administrator. Thank you!'
    ],
    'request' => [
        'validation' => [
            'failure' => 'Uppss something\'s missing, please fill in all required fields below',
            'captcha_failure' => 'and make sure to confirm that you are not a robot',
            'required_field' => 'This field is required'
        ],
        'enter' => 'Enter...',
        'submit' => 'Submit Form',
    ],
    'thankyou' => [
        'title' => 'We have received your form',
        'message' => 'Thank you for submitting the form, we will do a detailed review and get back to you ASAP ! Thank you!'
    ],
    'review' => [
        'no_input' => 'No Input.',
        'assign' => 'Assign to Customer',
        'go_to_customer' => 'Go to Customer',
        'mark_reviewed' => 'Mark as Reviewed',
        'reviewed_by' => 'Reviewed by',
        'on' => 'On',
        'widget' => [
            'title' => 'Assign Customer to Questionnarie',
            'close' => 'Close',
            'select_customer' => 'Select a Customer',
            'assign' => 'Assign'
        ]
    ],
    'pending_review' => [
        'title' => 'Pending Review Questionnaries',
        'col' => [
            'date' => 'Date',
            'questionnarie_name' => 'Questionarie Name',
            'in_relation' => 'In Relation',
            'status' => 'Status',
            'action' => 'Action'
        ],
        'button' => [
            'review' => 'Review',
            'delete' => 'Delete',
            'go_to_customer' => 'Go to Customer'
        ],
        'status' => [
            'REVIEWED' => 'Reviewed',
            'SUBMITTED' => 'Submited',
            'PENDING' => 'Pending'
        ],
        'delete_message' => 'Are you sure that you would like to delete Questionnarie Input ?',
        'deleted_message' =>  'Questionnaire was deleted',
        'error' => 'Opps something\' not right:',
        'error_message' => 'This one might have been remove, refresh and check otherwise contact the administrator',

    ]
];
