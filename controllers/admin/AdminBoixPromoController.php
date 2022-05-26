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

if(!class_exists('BoixPromo'));
    require_once _PS_MODULE_DIR_.'boix_mdf/classes/BoixPromo.php';

use Cleandev\BoixMdf\Classes\Promo;

class AdminBoixPromoController extends ModuleAdminController {

    public function __construct()
    {
        $this->table = 'boix_promo';
        $this->className = 'BoixPromo';
        $this->lang = true;
        $this->bootstrap = true;

        $this->deleted = false;
        $this->allow_export = true;
        $this->list_id = 'boix_promo';
        $this->identifier = 'id_boix_promo';
        $this->_defaultOrderBy = 'id_boix_promo';
        $this->_defaultOrderWay = 'ASC';
        $this->context = Context::getContext();

        $this->addRowAction('edit');
        $this->addRowAction('delete'); 
        
        $this->fieldImageSettings = array(
            'name' => 'avatar',
            'dir' => 'boix_promo'
        );

        $this->_select .="st.name store";
        $this->_join .=" LEFT JOIN `"._DB_PREFIX_."store_lang` st on st.id_store = a.id_store and st.id_lang=".$this->context->language->id;

        parent::__construct();

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected', [], 'Modules.Boixmdf.Adminboixpromocontroller.php'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?', [], 'Modules.Boixmdf.Adminboixpromocontroller.php')
            )
        );

        $stores = $this->getStores();
        $stores_list = array();
        foreach ($stores as $store) {
            $stores_list[$store['id']] = $store['name'];
        }
        

        $this->fields_list = array(
            'id_boix_promo'=>array(
                'title' => $this->l('ID', [], 'Modules.Boixmdf.Adminboixpromocontroller.php'),
                'align'=>'center',
                'class'=>'fixed-width-xs'
            ),
            'avatar' => array(
                'title' => $this->l('Image', [], 'Modules.Boixmdf.Adminboixpromocontroller.php'),
                'image' => 'boix_promo',
                'orderby' => false,
                'search' => false,
                'align' => 'center',
            ),
            'store'=>array(
                'title'=>$this->l('store', [], 'Modules.Boixmdf.Adminboixpromocontroller.php'),
                'type' => 'select',
                'list' => $stores_list,
                'filter_key' => 'a!id_store',
                'filter_type' => 'int',
                'order_key' => 'id_store'
            ),
            'active' => array(
                'title' => $this->l('Enabled', [], 'Modules.Boixmdf.Adminboixpromocontroller.php'),
                'active' =>'status',
                'type' =>'bool',
                'align' =>'center',
                'class' =>'fixed-width-xs',
                'orderby' => false,
            ),
            'start_date'=>array(
                'title'=>$this->l('Date de début', [], 'Modules.Boixmdf.Adminboixpromocontroller.php'),
                'align' => 'text-left',
                'type' => 'datetime',
                'class' => 'fixed-width-lg',
                'filter_key' => 'a!start_date',
            ),
            'end_date'=>array(
                'title'=>$this->l('Date de fin', [], 'Modules.Boixmdf.Adminboixpromocontroller.php'),
                'align' => 'text-left',
                'type' => 'datetime',
                'class' => 'fixed-width-lg',
                'filter_key' => 'a!end_date',
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

    public function renderForm()
    {
        if (!($promo = $this->loadObject(true))) {
            return;
        }

        $image = Promo::getImgPath(false).DIRECTORY_SEPARATOR.$promo->id.'.jpg';
        $image_url = ImageManager::thumbnail(
            $image,
            $this->table.'_'.(int)$promo->id.'.'.$this->imageType,
            350,
            $this->imageType,
            true,
            true
        );
        
        $image_size = file_exists($image) ? filesize($image) / 1000 : false;
        
        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Promo', [], 'Modules.Boixmdf.Adminboixpromocontroller.php'),
                'icon' => 'icon-certificate'
            ),
            'input' => array(
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Date début', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                    'name' => 'start_date',
                    'col' => 8,
                    'required' => true,
                    'hint' => $this->l('Invalid characters:').' &lt;&gt;;=#{}'
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Date fin', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                    'name' => 'end_date',
                    'col' => 8,
                    'required' => true,
                    'hint' => $this->l('Invalid characters:').' &lt;&gt;;=#{}'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Store', [], 'Modules.Boixmdf.Adminboixpromocontroller.php'),
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
                    'type' => 'textarea',
                    'label' => $this->l('Description', [], 'Modules.Boixmdf.Adminboixpromocontroller.php'),
                    'name' => 'description',
                    'lang' => true,
                    'autoload_rte' => true,
                    'cols' => 60,
                    'rows' => 10,
                    'col' => 8,
                    'hint' => $this->l('Invalid characters:', [], 'Modules.Boixmdf.Adminboixpromocontroller.php').' &lt;&gt;;=#{}'
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Avatar', [], 'Modules.Boixmdf.Adminboixpromocontroller.php'),
                    'name' => 'avatar',
                    'image' => $image_url ? $image_url : false,
                    'size' => $image_size,
                    'display_image' => true,
                    'col' => 8,
                    'hint' => $this->l('Upload a promo logo from your computer.', [], 'Modules.Boixmdf.Adminboixpromocontroller.php')
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable', [], 'Modules.Boixmdf.Adminboixpromocontroller.php'),
                    'name' => 'active',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled', [], 'Modules.Boixmdf.Adminboixpromocontroller.php')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled', [], 'Modules.Boixmdf.Adminboixpromocontroller.php')
                        )
                    )
                )
            )
        );

        if (!($promo = $this->loadObject(true))) {
            return;
        }


        $this->fields_form['submit'] = array(
            'title' => $this->l('Save', [], 'Modules.Boixmdf.Adminboixpromocontroller.php')
        );

        foreach ($this->_languages as $language) {
            $this->fields_value['description_'.$language['id_lang']] = htmlentities(Tools::stripslashes($this->getFieldValue(
                $promo,
                'description',
                $language['id_lang']
            )), ENT_COMPAT, 'UTF-8');
        }

        return parent::renderForm();
    }

    

    public function l($string, $params = [], $domaine = 'Modules.Boixmdf.Adminboixpromocontroller.php', $local = null){
        if(_PS_VERSION_ >= '1.7'){
            return $this->module->getTranslator()->trans($string, $params, $domaine, $local);
        }else{
            return parent::l($string, null, false, true);
        }
    }
}
