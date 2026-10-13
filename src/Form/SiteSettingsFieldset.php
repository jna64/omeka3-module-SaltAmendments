<?php declare(strict_types=1);

namespace SaltAmendments\Form;

use Laminas\Form\Element;
use Laminas\Form\Fieldset;
use Omeka\Form\Element as OmekaElement;

class SiteSettingsFieldset extends Fieldset
{
    protected $label = 'Salt Cookies'; // @translate

    public function init(): void
    {
        $this->add([
            'name' => 'saltcookies_gtag',
            'type' => Element\Text::class,
            'options' => [
                'element_group' => 'privacy',
                'label' => 'zzzz Google Analytics tag', // @translate
                'info' => 'Add your Google Analytics tracking tag.', // @translate
            ],
            'attributes' => [
                'id' => 'saltcookies_gtag',
            ],
        ]);
    }
}
