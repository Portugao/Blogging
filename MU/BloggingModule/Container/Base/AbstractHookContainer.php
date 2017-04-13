<?php
/**
 * Blogging.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link http://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace MU\BloggingModule\Container\Base;

use Zikula\Bundle\HookBundle\AbstractHookContainer as ZikulaHookContainer;
use Zikula\Bundle\HookBundle\Bundle\SubscriberBundle;

/**
 * Base class for hook container methods.
 */
abstract class AbstractHookContainer extends ZikulaHookContainer
{
    /**
     * Define the hook bundles supported by this module.
     *
     * @return void
     */
    protected function setupHookBundles()
    {
        $bundle = new SubscriberBundle('MUBloggingModule', 'subscriber.mubloggingmodule.ui_hooks.posts', 'ui_hooks', $this->__('mubloggingmodule. Posts Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'mubloggingmodule.ui_hooks.posts.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'mubloggingmodule.ui_hooks.posts.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'mubloggingmodule.ui_hooks.posts.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'mubloggingmodule.ui_hooks.posts.validate_edit');
        // Validate input from an ui delete form.
        $bundle->addEvent('validate_delete', 'mubloggingmodule.ui_hooks.posts.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'mubloggingmodule.ui_hooks.posts.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'mubloggingmodule.ui_hooks.posts.process_delete');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('MUBloggingModule', 'subscriber.mubloggingmodule.filter_hooks.posts', 'filter_hooks', $this->__('mubloggingmodule. Posts Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'mubloggingmodule.filter_hooks.posts.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('MUBloggingModule', 'subscriber.mubloggingmodule.ui_hooks.images', 'ui_hooks', $this->__('mubloggingmodule. Images Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'mubloggingmodule.ui_hooks.images.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'mubloggingmodule.ui_hooks.images.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'mubloggingmodule.ui_hooks.images.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'mubloggingmodule.ui_hooks.images.validate_edit');
        // Validate input from an ui delete form.
        $bundle->addEvent('validate_delete', 'mubloggingmodule.ui_hooks.images.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'mubloggingmodule.ui_hooks.images.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'mubloggingmodule.ui_hooks.images.process_delete');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('MUBloggingModule', 'subscriber.mubloggingmodule.filter_hooks.images', 'filter_hooks', $this->__('mubloggingmodule. Images Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'mubloggingmodule.filter_hooks.images.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        
    }
}
