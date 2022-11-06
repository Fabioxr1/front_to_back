<?php
/**
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License ( AFL 3.0 )
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License ( AFL 3.0 )
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_PS_VERSION_')) {
    exit;
}

class Front_to_back extends Module implements PrestaShop\PrestaShop\Core\Module\WidgetInterface
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'front_to_back';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Fabio Perrone';
        $this->need_instance = 0;

        /*
        * Set $this->bootstrap to true if your module is compliant with bootstrap ( PrestaShop 1.6 )
        */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Front To Back Link');
        $this->description = $this->l('This module add a button for direct access to the product modification page in Backoffice');

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
    }

    public function install()
    {
        return parent::install() &&
        $this->registerHook('header') &&
        // $this->registerHook( 'backOfficeHeader' ) &&
        $this->registerHook('displayNav2');
    }

    public function uninstall()
    {
        Configuration::deleteByName('FRONTTOBACKDIRECTORBACK');

        return parent::uninstall();
    }

    public function getContent()
    {
        /*
        * If values have been submitted in the form, process.
        */
        if (((bool) Tools::isSubmit('submitFrontToBackModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitFrontToBackModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
        . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$this->getConfigForm()]);
    }

    protected function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'col' => 6,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-chevron-sign-right"></i>',
                        'desc' => $this->l('Enter Name Directory Backoffice'),
                        'name' => 'FRONTTOBACKDIRECTORBACK',
                        'label' => $this->l('You Directory Backoffice'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    protected function getConfigFormValues()
    {
        return [
            'FRONTTOBACKDIRECTORBACK' => Configuration::get('FRONTTOBACKDIRECTORBACK', null),
        ];
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        // $this->context->controller->addJS( $this->_path.'/views/js/front.js' );
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    public function checkUserEmployee()
    {
        $cookie = new Cookie('psAdmin', '', (int) Configuration::get('PS_COOKIE_LIFETIME_BO'));

        if (isset($cookie->id_employee) && $cookie->id_employee) {
            return true;
        } else {
            return false;
        }
    }

    public function getLinkProductFrontToBack()
    {
        $cookie = new Cookie('psAdmin', '', (int) Configuration::get('PS_COOKIE_LIFETIME_BO'));
        $token = Tools::getAdminToken('AdminProducts' . (int) Tab::getIdFromClassName('AdminProducts') . $cookie->id_employee);
        $productLink = Tools::getHttpHost(true). __PS_BASE_URI__. Configuration::get('FRONTTOBACKDIRECTORBACK', null);
        $productLink .= '/index.php?controller=AdminProducts';
        $productLink .= '&token=' . $token;
        $productLink .= '&id_product=' . (int) Tools::getvalue('id_product');
        $productLink .= '&updateproduct&key_tab=Images&action=Images';

        return $productLink;
    }

    public function renderWidget($hookName, array $configuration)
    {
        if (!$this->checkUserEmployee()) {
            return;
        }

        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

        return $this->fetch('module:' . $this->name . '/views/templates/hook/display.tpl');
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        return [
            'linkBack' => $this->getLinkProductFrontToBack(),
        ];
    }
}
