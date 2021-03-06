<?php
/**
 * Blogging.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link https://ziku.la
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\BloggingModule\Base;

use MU\BloggingModule\Listener\EntityLifecycleListener;

/**
 * Events definition base class.
 */
abstract class AbstractBloggingEvents
{
    /**
     * The mubloggingmodule.itemactionsmenu_pre_configure event is thrown before the item actions
     * menu is built in the menu builder.
     *
     * The event listener receives an
     * MU\BloggingModule\Event\ConfigureItemActionsMenuEvent instance.
     *
     * @see MU\BloggingModule\Menu\MenuBuilder::createItemActionsMenu()
     * @var string
     */
    const MENU_ITEMACTIONS_PRE_CONFIGURE = 'mubloggingmodule.itemactionsmenu_pre_configure';
    
    /**
     * The mubloggingmodule.itemactionsmenu_post_configure event is thrown after the item actions
     * menu has been built in the menu builder.
     *
     * The event listener receives an
     * MU\BloggingModule\Event\ConfigureItemActionsMenuEvent instance.
     *
     * @see MU\BloggingModule\Menu\MenuBuilder::createItemActionsMenu()
     * @var string
     */
    const MENU_ITEMACTIONS_POST_CONFIGURE = 'mubloggingmodule.itemactionsmenu_post_configure';
    /**
     * The mubloggingmodule.post_post_load event is thrown when posts
     * are loaded from the database.
     *
     * The event listener receives an
     * MU\BloggingModule\Event\FilterPostEvent instance.
     *
     * @see EntityLifecycleListener::postLoad()
     * @var string
     */
    const POST_POST_LOAD = 'mubloggingmodule.post_post_load';
    
    /**
     * The mubloggingmodule.post_pre_persist event is thrown before a new post
     * is created in the system.
     *
     * The event listener receives an
     * MU\BloggingModule\Event\FilterPostEvent instance.
     *
     * @see EntityLifecycleListener::prePersist()
     * @var string
     */
    const POST_PRE_PERSIST = 'mubloggingmodule.post_pre_persist';
    
    /**
     * The mubloggingmodule.post_post_persist event is thrown after a new post
     * has been created in the system.
     *
     * The event listener receives an
     * MU\BloggingModule\Event\FilterPostEvent instance.
     *
     * @see EntityLifecycleListener::postPersist()
     * @var string
     */
    const POST_POST_PERSIST = 'mubloggingmodule.post_post_persist';
    
    /**
     * The mubloggingmodule.post_pre_remove event is thrown before an existing post
     * is removed from the system.
     *
     * The event listener receives an
     * MU\BloggingModule\Event\FilterPostEvent instance.
     *
     * @see EntityLifecycleListener::preRemove()
     * @var string
     */
    const POST_PRE_REMOVE = 'mubloggingmodule.post_pre_remove';
    
    /**
     * The mubloggingmodule.post_post_remove event is thrown after an existing post
     * has been removed from the system.
     *
     * The event listener receives an
     * MU\BloggingModule\Event\FilterPostEvent instance.
     *
     * @see EntityLifecycleListener::postRemove()
     * @var string
     */
    const POST_POST_REMOVE = 'mubloggingmodule.post_post_remove';
    
    /**
     * The mubloggingmodule.post_pre_update event is thrown before an existing post
     * is updated in the system.
     *
     * The event listener receives an
     * MU\BloggingModule\Event\FilterPostEvent instance.
     *
     * @see EntityLifecycleListener::preUpdate()
     * @var string
     */
    const POST_PRE_UPDATE = 'mubloggingmodule.post_pre_update';
    
    /**
     * The mubloggingmodule.post_post_update event is thrown after an existing new post
     * has been updated in the system.
     *
     * The event listener receives an
     * MU\BloggingModule\Event\FilterPostEvent instance.
     *
     * @see EntityLifecycleListener::postUpdate()
     * @var string
     */
    const POST_POST_UPDATE = 'mubloggingmodule.post_post_update';
    
}
