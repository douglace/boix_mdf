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

class BoixTestimony extends ObjectModel
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
     * Produit
     * @param int $id_product
     */
    public $id_product;

    /**
     * Lien du note
     * @param int $note
     */
    public $note;

    /**
     * Nom du témoignant
     * @param string $username
     */
    public $username;

    /**
     * Commentaire
     * @param string $comment
     */
    public $comment;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'boix_testimony',
        'primary' => 'id_boix_testimony',
        'multilang' => true,
        'fields' => array(
            'id_store' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'id_product' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'active' => array(
                'type' => self::TYPE_BOOL
            ),
            'note' => array(
                'type' => self::TYPE_FLOAT,
                'validate' => 'isFloat'
            ),
            'username' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString'
            ),
            'comment' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
                'lang' => true
            )
        ),
    );

    
}
