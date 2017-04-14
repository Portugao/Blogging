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

/**
 * Replaces a given needle id by the corresponding content.
 *
 * @param array $args Arguments array
 *     int nid The needle id
 *
 * @return string Replaced value for the needle
 */
function MUBloggingModule_needleapi_post_base($args)
{
    // Get arguments from argument array
    $nid = $args['nid'];
    unset($args);

    // cache the results
    static $cache;
    if (!isset($cache)) {
        $cache = [];
    }

    $container = \ServiceUtil::getManager();
    $translator = $container->get('translator.default');

    if (empty($nid)) {
        return '<em>' . htmlspecialchars(__('No correct needle id given.')) . '</em>';
    }

    if (isset($cache[$nid])) {
        // needle is already in cache array
        return $cache[$nid];
    }

    if (!$container->get('kernel')->isBundle('MUBloggingModule')) {
        $cache[$nid] = '<em>' . htmlspecialchars($translator->__f('Module "%moduleName%" is not available.', ['%moduleName%' => MUBloggingModule'])) . '</em>';

        return $cache[$nid];
    }

    // strip application prefix from needle
    $needleId = str_replace('BLOGGING', '', $nid);

    $permissionApi = $container->get('zikula_permissions_module.api.permission');
    $router = $container->getService('router');

    if ($needleId == 'POSTS') {
        if (!$permissionApi->hasPermission('MUBloggingModule:Post:', '::', ACCESS_READ)) {
            $cache[$nid] = '';

            return $cache[$nid];
        }
    }

    $cache[$nid] = '<a href="' . $router->generate('mubloggingmodule_posts_view') . '" title="' . $translator->__('View posts') . '">' . $translator->__('Posts') . '</a>';
    $needleParts = explode('-', $needleId);
    if ($needleParts[0] != 'POST' || count($needleParts) < 2) {
        $cache[$nid] = '';

        return $cache[$nid];
    }

    $entityId = (int)$needleParts[1];

    if (!$permissionApi->hasPermission('MUBloggingModule:Post:', $entityId . '::', ACCESS_READ)) {
        $cache[$nid] = '';

        return $cache[$nid];
    }

    $repository = $container->get('mu_blogging_module.entity_factory')->getRepository('post');
    $entity = $repository->selectById($entityId);
    if (null === $entity) {
        $cache[$nid] = '<em>' . $translator->__f('Post with id %id% could not be found', ['%id%' => $entityId]) . '</em>';

        return $cache[$nid];
    }

    $title = $entity->getTitleFromDisplayPattern();
    $cache[$nid] = '<a href="' . $router->generate('mubloggingmodule_posts_display', ['id' => $entityId]) . '" title="' . str_replace('"', '', $title) . '">' . $title . '</a>';

    return $cache[$nid];
}