<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ejjiSamK-5VvxNkRO5ME9N3resEGaDIy',
        ],
    ],
	'bootstrap' => ['gii'],
	'modules' => [
		'gii' => 'yii\gii\Module',
	],
];
