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
if (file_exists(_PS_MODULE_DIR_. 'boix_mdf/vendor/autoload.php')) {
    require_once _PS_MODULE_DIR_.  'boix_mdf/vendor/autoload.php';
}

use Cleandev\BoixMdf\Classes\BoixStore;

class AdminStoresController extends AdminStoresControllerCore {
    protected $nb_image = 5;

    public function renderForm()
    {

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $id_store = Tools::getValue('id_store', 0);

        $stores = $this->getStores($id_store);

        $selected_categories = array_map(function($a){
            return $a['id_category'];
        }, BoixStore::getStoreCategories($id_store));

        $root = Category::getRootCategory();
        $tree = new HelperTreeCategories('id_category'); 
        $tree->setUseCheckBox(true)
            ->setAttribute('id_category', $root->id)
            ->setRootCategory($root->id)
            ->setUseSearch(true)
            ->setSelectedCategories($selected_categories)
            ->setInputName('categories'); //Set the name of input. The option "name" of $fields_form doesn't seem to work with "categories_select" type
        
        
        $this->fields_form_override = array(
            array(
                'type' => 'select',
                'label' => $this->trans('Store nearby', [], 'Modules.Boixmdf.Adminboixequipecontroller.php'),
                'name' => 'stores[]',
                'multiple' => true,
                'class' => 'chosen',
                'col' => 6,
                'options'=> array(
                    'query'=> $stores,
                    'id'=>'id',
                    'name'=>'name',
                ),
                'required' => false,
            ),
            array(
                'type'  => 'categories_select',
                'label' => $this->trans('Catégories', [], 'Modules.Kreabelhome.Admin'),
                'name' => 'categories[]',
                'category_tree'  => $tree->render(),
                'required' => false,
                'hint' => $this->trans('Invalid characters:', [], 'Modules.Kreabelhome.Admin').' &lt;&gt;;=#{}'
            ),
        );

        for($i=1; $i<=$this->nb_image; $i++) {
            $image = _PS_STORE_IMG_DIR_ . $obj->id .'_'.$i. '.jpg';
            $image_url = ImageManager::thumbnail(
                $image,
                $this->table . '_' . (int) $obj->id .'_'.$i. '.' . $this->imageType,
                350,
                $this->imageType,
                true,
                true
            );
            $image_size = file_exists($image) ? filesize($image) / 1000 : false;
            $this->fields_form_override[] = [
                'type' => 'file',
                'label' => $this->trans('Picture suplement '.$i, [], 'Admin.Shopparameters.Feature'),
                'name' => 'image_'.$i,
                'display_image' => true,
                'image' => $image_url ? $image_url : false,
                'size' => $image_size,
                'hint' => $this->trans('Storefront picture.', [], 'Admin.Shopparameters.Help'),
            ];
        }
    
        return parent::renderForm();
    }

    public function getStores($id_store = null) {
        $q = new DbQuery();
        $q->select('id_store id, name')
            ->from('store_lang')
            ->where('id_lang='.$this->context->language->id)
        ;
        if($id_store) {
            $q->where('id_store<>'.$id_store);
        }

        return Db::getInstance()->executeS($q);
    }

    

    public function processAdd()
    {
        $obj = parent::processAdd();
        $this->saveAnotherFields($obj);
        return $obj;
    }

    public function processUpdate()
    {
        $obj = parent::processUpdate();
        $this->saveAnotherFields($obj);
        return $obj;
    }

    public function saveAnotherFields($obj) {
        $id_store = Tools::getValue('id_store');
        if($obj && Validate::isLoadedObject($obj)) {
            $id_store = $obj->id;
        }
        if($id_store) {
            BoixStore::attachStoreNearby($id_store, Tools::getValue('stores', []));
            BoixStore::attachCategories($id_store, Tools::getValue('categories', []));    
        
            for($i=1; $i<=$this->nb_image; $i++) {
                $key = 'image_'.$i;
                if(isset($_FILES[$key]) && isset($_FILES[$key]['name']) && $_FILES[$key]['name']) {
                    $this->uploadImages(
                        $_FILES[$key],
                        $id_store.'_'.$i.'.jpg'
                    );
                }
            }
        }
    }

    public function uploadImages($file, $file_name = ""){
        if($file && isset($file['error']) && $file['error'] == UPLOAD_ERR_OK){
            $tmp = $file['tmp_name'];
            $file_ext= @strtolower(end(explode('.',$file['name'])));
            $extensions= array("jpeg","jpg","png");
            if(in_array( strtolower($file_ext) ,$extensions)=== false){
                $this->errors[]="extension not allowed, please choose a JPEG or PNG file.";
            } else {
                $file_name = !empty($file_name) ? $file_name : $file['name'];
                move_uploaded_file($tmp,_PS_STORE_IMG_DIR_.$file_name);
            }
        } else {
            $this->errors[]="Can't upload file";
        }
    }

    
}