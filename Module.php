<?php declare(strict_types=1);

namespace SaltAmendments;

use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Validator\Callback;
use Omeka\Module\AbstractModule;
use SaltAmendments\Form\SiteSettingsFieldset;


/**
 * Saltaire Collection Amendments
 *
 * @copyright Saltaire Collection/Saltaire World Heritage Education Association 2026
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html
 */
class Module extends AbstractModule
{
    const NAMESPACE = __NAMESPACE__;

    protected function preInstall(): void
    {
        $services = $this->getServiceLocator();
        $plugins = $services->get('ControllerPluginManager');
        $translate = $plugins->get('translate');

        if (!method_exists($this, 'checkModuleActiveVersion') || !$this->checkModuleActiveVersion('EUCookieBar', '3.4.5')) {
            $message = new \Omeka\Stdlib\Message(
                $translate('The module %1$s should be upgraded to version %2$s or later.'), // @translate
                'EUCookieBar', '3.4.5'
            );
            throw new \Omeka\Module\Exception\ModuleCannotInstallException((string) $message);
        }
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager): void
    {
        $sharedEventManager->attach(
            '*',
            'view.layout',
            [$this, 'handleViewLayout']
        );
        $sharedEventManager->attach(
            \Omeka\Form\SiteSettingsForm::class,
            'form.add_elements',
            [$this, 'addSiteSettings']
        );
        $sharedEventManager->attach(
            \Omeka\Form\SiteSettingsForm::class,
            'form.add_input_filters',
            [$this, 'addSiteSettingsFilters']
        );
    }

    public function addSiteSettings(Event $event)
    {
        $services = $this->getServiceLocator();
        $forms = $services->get('FormElementManager');
        $siteSettings = $services->get('Omeka\Settings\Site');

        $fieldset = $forms->get(SiteSettingsFieldset::class);
#        $fieldset->setName('saltcookies');
        $fieldset->populateValues([
            'saltcookies_gtag' => $siteSettings->get('saltcookies_gtag', ''),
        ]);

        $form = $event->getTarget();
#        $groups['octopusviewer'] = $fieldset->getLabel();
#            $form->setOption('element_groups', $groups);
        foreach ($fieldset->getElements() as $element) {
            $form->add($element);
        }
    }

/*    public function handleSiteSettings(Event $event): void
    {
        $siteSettings = $this->getServiceLocator()->get('Omeka\Settings\Site');
        $form = $event->getTarget();

        // EUCookieBar - add Google Tag field
        $form->add([
            'name' => 'saltcookies_gtag',
            'type' => 'text',
            'options' => [
                'element_group' => 'privacy',
                'label' => 'Google Analytics tag', // @translate
                'info' => 'Add your Google Analytics tracking tag.', // @translate
            ],
            'attributes' => [
                'id' => 'saltcookies_gtag',
                'value' => $siteSettings->get('saltcookies_gtag'),
            ],
        ]);
    }
*/

    public function addSiteSettingsFilters($event)
    {
        $inputFilter = $event->getParam('inputFilter');
 
        // EUCookieBar - Google Analytic code
        if ($inputFilter->has('saltcookies')) {
            $inputFilter = $inputFilter->get('saltcookies');
        }
        $inputFilter->add([
            'name' => 'saltcookies_gtag',
            'allow_empty' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Callback',
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => 'The format of Google Analytics Code is incorrect', // @translate
                        ],
                        'callback' => [$this, 'isValidGtag'],
                    ],
                ],
            ],
        ]);
    }

    public function isValidGtag($gtag)
    {
        return preg_match("/^GA?-[A-Z0-9]{10}$/", $gtag);
    }


    public function handleViewLayout(Event $event): void
    {
        $view = $event->getTarget();
        if (!$view->status()->isSiteRequest()) {
            return;
        }

        $siteSettings = $this->getServiceLocator()->get('Omeka\Settings\Site');

        // EUCookieBar amendment - add integrated Google tag START

        // Analytics only if user is not logged in, and cookie message and gtag have been set
        $user = $this->getServiceLocator()->get('Omeka\AuthenticationService')->getIdentity();
        $message = $siteSettings->get('eucookiebar_message'); 
        $gtag = $siteSettings->get('saltcookies_gtag');
        if (!$user && !empty($message) && !empty($gtag))  {
            $js =<<<EOJ
                $(document).ready(function() { 
                    if ($.isFunction($.cookieBar)) {
                        if ('$gtag' && $.cookieBar('cookies')){
                            window.dataLayer = window.dataLayer || [];
                            function gtag(){dataLayer.push(arguments);}
                            gtag('js', new Date());
                            gtag('config', '$gtag');
                        }
                    }
                });
            EOJ;
            $view->headScript()
                ->appendFile('https://www.googletagmanager.com/gtag/js?id='.$gtag,'', array('async' => 'true'))
                ->appendScript($js);
        }
    }
    // EUCookieBar amendment - add integrated Google tag END
}
