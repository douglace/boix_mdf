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

class BoixEquipe extends ObjectModel
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
     * deleted
     * @param int $deleted
     */
    public $deleted = 0;

    /**
     * Nom
     * @param int $name
     */
    public $name;

    /**
     * Role
     * @param int $role
     */
    public $role;
    
    /**
     * Date d'ajout
     * @param bool $date_add
     */
    public $date_add;

    /**
     * Date de modification
     * @param string $date_upd
     */
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'boix_equipe',
        'primary' => 'id_boix_equipe',
        'multilang' => true,
        'fields' => array(
            'id_store' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'active' => array(
                'type' => self::TYPE_BOOL
            ),
            'deleted' => array(
                'type' => self::TYPE_BOOL
            ),
            'name' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString'
            ),
            'role' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
                'lang' => true
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE
            ),
            'date_upd' => array(
                'type' => self::TYPE_DATE
            ),
        ),
    );

    
}
