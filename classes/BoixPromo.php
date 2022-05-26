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

class BoixPromo extends ObjectModel
{
    /**
     * Magasin
     * @param int $id_store
     */
    public $id_store = 0;

    /**
     * Active
     * @param int $active
     */
    public $active = 1;

    /**
     * Description
     * @param string|string[] $description
     */
    public $description;

    
    /**
     * Date d'ajout
     * @param bool $date_add
     */
    public $date_add;

    /**
     * Date de début
     * @param string $start_date
     */
    public $start_date;

    /**
     * Date de fin
     * @param string $end_date
     */
    public $end_date;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'boix_promo',
        'primary' => 'id_boix_promo',
        'multilang' => true,
        'fields' => array(
            'id_store' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'active' => array(
                'type' => self::TYPE_BOOL
            ),
            'description' => array(
                'type' => self::TYPE_HTML,
                'validate' => 'isCleanHtml',
                'lang' => true
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE
            ),
            'start_date' => array(
                'type' => self::TYPE_DATE
            ),
            'end_date' => array(
                'type' => self::TYPE_DATE
            ),
        ),
    );

    
}
