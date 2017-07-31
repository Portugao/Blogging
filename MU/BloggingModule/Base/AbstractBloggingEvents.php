<?php
/**
 * Blogging.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\BloggingModule\Base;

/**
 * Events definition base class.
 */
abstract class AbstractBloggingEvents
{
    /**
     * The mubloggingmodule.post_post_load event is thrown when posts
     * are loaded from the database.
     *
     * The event listener receives an
     * MU\BloggingModule\Event\FilterPostEvent instance.
     *
     * @see MU\BloggingModule\Listener\EntityLifecycleListener::postLoad()
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
     * @see MU\BloggingModule\Listener\EntityLifecycleListener::prePersist()
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
     * @see MU\BloggingModule\Listener\EntityLifecycleListener::postPersist()
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
     * @see MU\BloggingModule\Listener\EntityLifecycleListener::preRemove()
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
     * @see MU\BloggingModule\Listener\EntityLifecycleListener::postRemove()
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
     * @see MU\BloggingModule\Listener\EntityLifecycleListener::preUpdate()
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
     * @see MU\BloggingModule\Listener\EntityLifecycleListener::postUpdate()
     * @var string
     */
    const POST_POST_UPDATE = 'mubloggingmodule.post_post_update';
    
}
