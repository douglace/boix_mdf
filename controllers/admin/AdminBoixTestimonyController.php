<?php

/**
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
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
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if(!class_exists('BoixTestimony'));
    require_once _PS_MODULE_DIR_.'boix_mdf/classes/BoixTestimony.php';

use Cleandev\BoixMdf\Classes\Testimony;

class AdminBoixTestimonyController extends ModuleAdminController {

    public function __construct()
    {
        $this->table = 'boix_testimony';
        $this->className = 'BoixTestimony';
        $this->lang = true;
        $this->bootstrap = true;

        $this->deleted = false;
        $this->allow_export = true;
        $this->list_id = 'boix_testimony';
        $this->identifier = 'id_boix_testimony';
        $this->_defaultOrderBy = 'id_boix_testimony';
        $this->_defaultOrderWay = 'ASC';
        $this->context = Context::getContext();

        $this->addRowAction('edit');
        $this->addRowAction('delete'); 
        
        $this->_select .="st.name store";
        $this->_join .=" LEFT JOIN `"._DB_PREFIX_."store_lang` st on st.id_store = a.id_store and st.id_lang=".$this->context->language->id;

        parent::__construct();

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?', [], 'Modules.Boixmdf.Adminboixequipecontroller.php')
            )
        );

        $stores = $this->getStores();
        $stores_list = array();
        foreach ($stores as $store) {
            $stores_list[$store['id']] = $store['name'];
        }
        

        $this->fields_list = array(
            'id_boix_testimony'=>array(
                'title' => $this->l('ID', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                'align'=>'center',
                'class'=>'fixed-width-xs'
            ),
            'store'=>array(
                'title'=>$this->l('store', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                'type' => 'select',
                'list' => $stores_list,
                'filter_key' => 'a!id_store',
                'filter_type' => 'int',
                'order_key' => 'id_store'
            ),
            'username'=>array(
                'title'=>$this->l('Nom d\'utilisateur', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                'width'=>'auto'
            ),
            'active' => array(
                'title' => $this->l('Enabled', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                'active' =>'status',
                'type' =>'bool',
                'align' =>'center',
                'class' =>'fixed-width-xs',
                'orderby' => false,
            ),
        );
    }

    public function getStores() {
        $q = new DbQuery();
        $q->select('id_store id, name')
            ->from('store_lang')
            ->where('id_lang='.$this->context->language->id)
        ;

        return Db::getInstance()->executeS($q);
    }

    public function getProducts() {
        $q = new DbQuery();
        $q->select('id_product id, name')
            ->from('product_lang')
            ->where('id_lang='.$this->context->language->id)
        ;

        return Db::getInstance()->executeS($q);
    }

    public function renderForm()
    {
        if (!($testimony = $this->loadObject(true))) {
            return;
        }
        
        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Témoignage', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                'icon' => 'icon-certificate'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Nom d\'utilisateur', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                    'name' => 'username',
                    'col' => 4,
                    'required' => true,
                    'hint' => $this->l('Invalid characters:').' &lt;&gt;;=#{}'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Note', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                    'name' => 'note',
                    'col' => 4,
                    'required' => true,
                    'hint' => $this->l('Invalid characters:').' &lt;&gt;;=#{}'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Store', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                    'name' => 'id_store',
                    'col' => 4,
                    'options'=> array(
                        'query'=> $this->getStores(),
                        'id'=>'id',
                        'name'=>'name',
                    ),
                    'required' => false,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Produit', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                    'name' => 'id_product',
                    'col' => 4,
                    'options'=> array(
                        'query'=> $this->getProducts(),
                        'id'=>'id',
                        'name'=>'name',
                    ),
                    'required' => false,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Commentaire', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                    'name' => 'comment',
                    'lang' => true,
                    'cols' => 60,
                    'rows' => 10,
                    'col' => 6,
                    'hint' => $this->l('Invalid characters:', [], 'Modules.Boixmdf.Adminboixequipecontroller.php').' &lt;&gt;;=#{}'
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                    'name' => 'active',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled', [], 'Modules.Boixmdf.Adminboixequipecontroller.php')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled', [], 'Modules.Boixmdf.Adminboixequipecontroller.php')
                        )
                    )
                )
            )
        );

        if (!($testimony = $this->loadObject(true))) {
            return;
        }


        $this->fields_form['submit'] = array(
            'title' => $this->l('Save', [], 'Modules.Boixmdf.Adminboixequipecontroller.php')
        );

        foreach ($this->_languages as $language) {
            $this->fields_value['comment_'.$language['id_lang']] = htmlentities(Tools::stripslashes($this->getFieldValue(
                $testimony,
                'comment',
                $language['id_lang']
            )), ENT_COMPAT, 'UTF-8');
        }

        return parent::renderForm();
    }

    

    public function l($string, $params = [], $domaine = 'Modules.Boixmdf.Adminboixequipecontroller.php', $local = null){
        if(_PS_VERSION_ >= '1.7'){
            return $this->module->getTranslator()->trans($string, $params, $domaine, $local);
        }else{
            return parent::l($string, null, false, true);
        }
    }
}
