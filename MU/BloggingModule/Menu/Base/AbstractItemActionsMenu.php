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

namespace MU\BloggingModule\Menu\Base;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Zikula\Common\Translator\TranslatorTrait;
use MU\BloggingModule\Entity\PostEntity;
use MU\BloggingModule\Entity\ImageEntity;

/**
 * This is the item actions menu implementation class.
 */
class AbstractItemActionsMenu implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use TranslatorTrait;

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }

    /**
     * Builds the menu.
     *
     * @param FactoryInterface $factory Menu factory
     * @param array            $options Additional options
     *
     * @return MenuItem The assembled menu
     */
    public function menu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('itemActions');
        if (!isset($options['entity']) || !isset($options['area']) || !isset($options['context'])) {
            return $menu;
        }

        $this->setTranslator($this->container->get('translator.default'));

        $entity = $options['entity'];
        $routeArea = $options['area'];
        $context = $options['context'];

        $permissionApi = $this->container->get('zikula_permissions_module.api.permission');
        $currentUserApi = $this->container->get('zikula_users_module.current_user');
        $menu->setChildrenAttribute('class', 'list-inline');

        $currentUserId = $currentUserApi->isLoggedIn() ? $currentUserApi->get('uid') : 1;
        if ($entity instanceof PostEntity) {
            $component = 'MUBloggingModule:Post:';
            $instance = $entity['id'] . '::';
            $routePrefix = 'mubloggingmodule_post_';
            $isOwner = $currentUserId > 0 && null !== $entity->getCreatedBy() && $currentUserId == $entity->getCreatedBy()->getUid();
        
            if ($routeArea == 'admin') {
                $menu->addChild($this->__('Preview'), [
                    'route' => $routePrefix . 'display',
                    'routeParameters' => $entity->createUrlArgs()
                ])->setAttribute('icon', 'fa fa-search-plus');
                $menu[$this->__('Preview')]->setLinkAttribute('target', '_blank');
                $menu[$this->__('Preview')]->setLinkAttribute('title', $this->__('Open preview page'));
            }
            if ($context != 'display') {
                $menu->addChild($this->__('Details'), [
                    'route' => $routePrefix . $routeArea . 'display',
                    'routeParameters' => $entity->createUrlArgs()
                ])->setAttribute('icon', 'fa fa-eye');
                $menu[$this->__('Details')]->setLinkAttribute('title', str_replace('"', '', $entity->getTitleFromDisplayPattern()));
            }
            if ($permissionApi->hasPermission($component, $instance, ACCESS_EDIT)) {
                $menu->addChild($this->__('Edit'), [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => $entity->createUrlArgs()
                ])->setAttribute('icon', 'fa fa-pencil-square-o');
                $menu[$this->__('Edit')]->setLinkAttribute('title', $this->__('Edit this post'));
                $menu->addChild($this->__('Reuse'), [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => ['astemplate' => $entity['id'], 'slug' => $entity['slug']]
                ])->setAttribute('icon', 'fa fa-files-o');
                $menu[$this->__('Reuse')]->setLinkAttribute('title', $this->__('Reuse for new post'));
            }
            if ($permissionApi->hasPermission($component, $instance, ACCESS_DELETE)) {
                $menu->addChild($this->__('Delete'), [
                    'route' => $routePrefix . $routeArea . 'delete',
                    'routeParameters' => $entity->createUrlArgs()
                ])->setAttribute('icon', 'fa fa-trash-o');
                $menu[$this->__('Delete')]->setLinkAttribute('title', $this->__('Delete this post'));
            }
            if ($context == 'display') {
                $title = $this->__('Back to overview');
                $menu->addChild($title, [
                    'route' => $routePrefix . $routeArea . 'view'
                ])->setAttribute('icon', 'fa fa-reply');
                $menu[$title]->setLinkAttribute('title', $title);
            }
            
            // more actions for adding new related items
            
            $relatedComponent = 'MUBloggingModule:Post:';
            $relatedInstance = $entity['id'] . '::';
            if ($isOwner || $permissionApi->hasPermission($relatedComponent, $relatedInstance, ACCESS_COMMENT)) {
                $title = $this->__('Create post');
                $menu->addChild($title, [
                    'route' => 'mubloggingmodule_post_' . $routeArea . 'edit',
                    'routeParameters' => ['post' => $entity['id']]
                ])->setAttribute('icon', 'fa fa-plus');
                $menu[$title]->setLinkAttribute('title', $title);
            }
            
            $relatedComponent = 'MUBloggingModule:Image:';
            $relatedInstance = $entity['id'] . '::';
            if ($isOwner || $permissionApi->hasPermission($relatedComponent, $relatedInstance, ACCESS_EDIT)) {
                $title = $this->__('Create image');
                $menu->addChild($title, [
                    'route' => 'mubloggingmodule_image_' . $routeArea . 'edit',
                    'routeParameters' => ['post' => $entity['id']]
                ])->setAttribute('icon', 'fa fa-plus');
                $menu[$title]->setLinkAttribute('title', $title);
            }
        }
        if ($entity instanceof ImageEntity) {
            $component = 'MUBloggingModule:Image:';
            $instance = $entity['id'] . '::';
            $routePrefix = 'mubloggingmodule_image_';
            $isOwner = $currentUserId > 0 && null !== $entity->getCreatedBy() && $currentUserId == $entity->getCreatedBy()->getUid();
        
            if ($routeArea == 'admin') {
                $menu->addChild($this->__('Preview'), [
                    'route' => $routePrefix . 'display',
                    'routeParameters' => $entity->createUrlArgs()
                ])->setAttribute('icon', 'fa fa-search-plus');
                $menu[$this->__('Preview')]->setLinkAttribute('target', '_blank');
                $menu[$this->__('Preview')]->setLinkAttribute('title', $this->__('Open preview page'));
            }
            if ($context != 'display') {
                $menu->addChild($this->__('Details'), [
                    'route' => $routePrefix . $routeArea . 'display',
                    'routeParameters' => $entity->createUrlArgs()
                ])->setAttribute('icon', 'fa fa-eye');
                $menu[$this->__('Details')]->setLinkAttribute('title', str_replace('"', '', $entity->getTitleFromDisplayPattern()));
            }
            if ($permissionApi->hasPermission($component, $instance, ACCESS_EDIT)) {
                $menu->addChild($this->__('Edit'), [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => $entity->createUrlArgs()
                ])->setAttribute('icon', 'fa fa-pencil-square-o');
                $menu[$this->__('Edit')]->setLinkAttribute('title', $this->__('Edit this image'));
                $menu->addChild($this->__('Reuse'), [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => ['astemplate' => $entity['id']]
                ])->setAttribute('icon', 'fa fa-files-o');
                $menu[$this->__('Reuse')]->setLinkAttribute('title', $this->__('Reuse for new image'));
            }
            if ($permissionApi->hasPermission($component, $instance, ACCESS_DELETE)) {
                $menu->addChild($this->__('Delete'), [
                    'route' => $routePrefix . $routeArea . 'delete',
                    'routeParameters' => $entity->createUrlArgs()
                ])->setAttribute('icon', 'fa fa-trash-o');
                $menu[$this->__('Delete')]->setLinkAttribute('title', $this->__('Delete this image'));
            }
            if ($context == 'display') {
                $title = $this->__('Back to overview');
                $menu->addChild($title, [
                    'route' => $routePrefix . $routeArea . 'view'
                ])->setAttribute('icon', 'fa fa-reply');
                $menu[$title]->setLinkAttribute('title', $title);
            }
        }

        return $menu;
    }
}
