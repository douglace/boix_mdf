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

class Equipe
{
    static $table = 'boix_equipe';

    public static function getImgPath($front = false){
        return $front ? Repository::$img_equipe_front : Repository::$img_equipe_dir;
    }

    /**
     * Retourne toutes l'équipes
     * @param int|null $id_store
     * @param int|null $id_lang
     * @return []|boolean
     */
    public static function getEquipes($id_store = null, $id_lang = null) {
        $id_lang = $id_lang ? $id_lang : Context::getContext()->language->id;
        
        $q = new DbQuery();
        $q->select('a.*, b.role')
        ->from(self::$table, 'a')
        ->innerJoin(self::$table.'_lang', 'b', 'b.id_boix_equipe=a.id_boix_equipe')
        ->where('b.id_lang='.$id_lang);

        if($id_store){
            $q->where('a.id_store='.$id_store);
        }
        return Db::getInstance()->executeS($q);
    }

    /**
     * Retourne tous l'équipe d'une boutique
     * @param int $id_store
     * @param int|null $id_lang
     * @return []|boolean
     */
    public static function getEquipesByIdStore($id_store, $id_lang = null) {
        return self::getEquipes($id_store, $id_lang);
    }
}