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

namespace Cleandev\BoixMdf\Classes;

use Cleandev\BoixMdf\Repository;
use ObjectModel;
use Product;
use Context;
use DbQuery;
use Cart;
use Db;

class BoixStore
{
    /**
     * Lie une boutique à des catégories
     * @param int $id_store
     * @param int[] $categories
     * @return boolean
     */
    public static function attachCategories($id_store, $categories) {
        Db::getInstance()->delete('boix_store_categories', 'id_store='.$id_store);
        if(!empty($categories)){
            $data = array_map(function($a)use($id_store){
                return [
                    'id_store' => $id_store,
                    'id_category' => $a
                ];
            },$categories);
            return Db::getInstance()->insert('boix_store_categories', $data, false, false, Db::INSERT_IGNORE);
        }
        
        return true;
    }

    /**
     * Ajoute les magasin à proximité
     * @param int $id_store
     * @param int[] $nearby
     * @return boolean
     */
    public static function attachStoreNearby($id_store, $nearby) {
        Db::getInstance()->delete('boix_store_nearby', 'id_store='.$id_store);
        if(!empty($nearby)){
            $data = array_map(function($a)use($id_store){
                return [
                    'id_store' => $id_store,
                    'id_store_nearby' => $a
                ];
            },$nearby);
            return Db::getInstance()->insert('boix_store_nearby', $data, false, false, Db::INSERT_IGNORE);
        }
        
        return true;
    }

    /**
     * Retourne tous les catégories d'un magasin
     * @param int|null $id_store
     * @param int|null $id_lang
     * @return []|boolean
     */
    public static function getStoreCategories($id_store, $id_lang = null) {
        $id_lang = $id_lang ? $id_lang : Context::getContext()->language->id;
        $q = new DbQuery();
        $q->select('a.*, b.*')
        ->from('category', 'a')
        ->innerJoin('category_lang', 'b', 'b.id_category=a.id_category')
        ->innerJoin('boix_store_categories', 'bsc', 'bsc.id_category=a.id_category')
        ->where('b.id_lang='.$id_lang)
        ->where('bsc.id_store='.$id_store)
        ;

        return Db::getInstance()->executeS($q);
    }

    /**
     * Retourne tous les magasin à proximité
     * @param int|null $id_store
     * @param int|null $id_lang
     * @return []|boolean
     */
    public static function getStoreNearBy($id_store, $id_lang = null) {
        $id_lang = $id_lang ? $id_lang : Context::getContext()->language->id;
        $q = new DbQuery();
        $q->select('a.*, b.*')
        ->from('store', 'a')
        ->innerJoin('store_lang', 'b', 'b.id_store=a.id_store')
        ->innerJoin('boix_store_nearby', 'bsn', 'bsn.id_store_nearby=a.id_store')
        ->where('b.id_lang='.$id_lang)
        ->where('bsn.id_store='.$id_store)
        ;

        return Db::getInstance()->executeS($q);
    }
}