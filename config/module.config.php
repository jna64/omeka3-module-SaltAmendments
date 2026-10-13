<?php declare(strict_types=1);
namespace SaltAmendments;

return [
    'form_elements' => [
        'invokables' => [
            Form\SiteSettingsFieldset::class => Form\SiteSettingsFieldset::class, # TODO MOVE TO SUBDIR
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'saltcookies' => [
        'site_settings' => [],
    ],
];
