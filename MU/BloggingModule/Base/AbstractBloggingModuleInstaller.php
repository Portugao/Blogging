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

use Doctrine\DBAL\Connection;
use RuntimeException;
use Zikula\Core\AbstractExtensionInstaller;
use Zikula\CategoriesModule\Entity\CategoryRegistryEntity;

/**
 * Installer base class.
 */
abstract class AbstractBloggingModuleInstaller extends AbstractExtensionInstaller
{
    /**
     * Install the MUBloggingModule application.
     *
     * @return boolean True on success, or false
     *
     * @throws RuntimeException Thrown if database tables can not be created or another error occurs
     */
    public function install()
    {
        $logger = $this->container->get('logger');
        $userName = $this->container->get('zikula_users_module.current_user')->get('uname');
    
        // Check if upload directories exist and if needed create them
        try {
            $container = $this->container;
            $uploadHelper = new \MU\BloggingModule\Helper\UploadHelper(
                $container->get('translator.default'),
                $container->get('filesystem'),
                $container->get('session'),
                $container->get('logger'),
                $container->get('zikula_users_module.current_user'),
                $container->get('zikula_extensions_module.api.variable'),
                $container->getParameter('datadir')
            );
            $uploadHelper->checkAndCreateAllUploadFolders();
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            $logger->error('{app}: User {user} could not create upload folders during installation. Error details: {errorMessage}.', ['app' => 'MUBloggingModule', 'user' => $userName, 'errorMessage' => $exception->getMessage()]);
        
            return false;
        }
        // create all tables from according entity definitions
        try {
            $this->schemaTool->create($this->listEntityClasses());
        } catch (\Exception $exception) {
            $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
            $logger->error('{app}: Could not create the database tables during installation. Error details: {errorMessage}.', ['app' => 'MUBloggingModule', 'errorMessage' => $exception->getMessage()]);
    
            return false;
        }
    
        // set up all our vars with initial values
        $this->setVar('postEntriesPerPage', 10);
        $this->setVar('linkOwnPostsOnAccountPage', true);
        $this->setVar('showOnlyOwnEntries', false);
        $this->setVar('filterDataByLocale', false);
        $this->setVar('enableShrinkingForPostImageForArticle', false);
        $this->setVar('shrinkWidthPostImageForArticle', 800);
        $this->setVar('shrinkHeightPostImageForArticle', 600);
        $this->setVar('thumbnailModePostImageForArticle', 'inset');
        $this->setVar('thumbnailWidthPostImageForArticleView', 32);
        $this->setVar('thumbnailHeightPostImageForArticleView', 24);
        $this->setVar('thumbnailWidthPostImageForArticleDisplay', 240);
        $this->setVar('thumbnailHeightPostImageForArticleDisplay', 180);
        $this->setVar('thumbnailWidthPostImageForArticleEdit', 240);
        $this->setVar('thumbnailHeightPostImageForArticleEdit', 180);
        $this->setVar('moderationGroupForPosts', 2);
        $this->setVar('allowModerationSpecificCreatorForPost', false);
        $this->setVar('allowModerationSpecificCreationDateForPost', false);
        $this->setVar('enabledFinderTypes', 'post');
    
        // add default entry for category registry (property named Main)
        $categoryHelper = new \MU\BloggingModule\Helper\CategoryHelper(
            $this->container->get('translator.default'),
            $this->container->get('request_stack'),
            $logger,
            $this->container->get('zikula_users_module.current_user'),
            $this->container->get('zikula_categories_module.category_registry_repository'),
            $this->container->get('zikula_categories_module.api.category_permission')
        );
        $categoryGlobal = $this->container->get('zikula_categories_module.category_repository')->findOneBy(['name' => 'Global']);
        if ($categoryGlobal) {
            $categoryRegistryIdsPerEntity = [];
            $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
    
            $registry = new CategoryRegistryEntity();
            $registry->setModname('MUBloggingModule');
            $registry->setEntityname('PostEntity');
            $registry->setProperty($categoryHelper->getPrimaryProperty('Post'));
            $registry->setCategory($categoryGlobal);
    
            try {
                $entityManager->persist($registry);
                $entityManager->flush();
            } catch (\Exception $exception) {
                $this->addFlash('warning', $this->__f('Error! Could not create a category registry for the %entity% entity. If you want to use categorisation, register at least one registry in the Categories administration.', ['%entity%' => 'post']));
                $logger->error('{app}: User {user} could not create a category registry for {entities} during installation. Error details: {errorMessage}.', ['app' => 'MUBloggingModule', 'user' => $userName, 'entities' => 'posts', 'errorMessage' => $exception->getMessage()]);
            }
            $categoryRegistryIdsPerEntity['post'] = $registry->getId();
        }
    
        // initialisation successful
        return true;
    }
    
    /**
     * Upgrade the MUBloggingModule application from an older version.
     *
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param integer $oldVersion Version to upgrade from
     *
     * @return boolean True on success, false otherwise
     *
     * @throws RuntimeException Thrown if database tables can not be updated
     */
    public function upgrade($oldVersion)
    {
    /*
        $logger = $this->container->get('logger');
    
        // Upgrade dependent on old version number
        switch ($oldVersion) {
            case '1.0.0':
                // do something
                // ...
                // update the database schema
                try {
                    $this->schemaTool->update($this->listEntityClasses());
                } catch (\Exception $exception) {
                    $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
                    $logger->error('{app}: Could not update the database tables during the upgrade. Error details: {errorMessage}.', ['app' => 'MUBloggingModule', 'errorMessage' => $exception->getMessage()]);
    
                    return false;
                }
        }
    */
    
        // update successful
        return true;
    }
    
    /**
     * Uninstall MUBloggingModule.
     *
     * @return boolean True on success, false otherwise
     *
     * @throws RuntimeException Thrown if database tables or stored workflows can not be removed
     */
    public function uninstall()
    {
        $logger = $this->container->get('logger');
    
        try {
            $this->schemaTool->drop($this->listEntityClasses());
        } catch (\Exception $exception) {
            $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
            $logger->error('{app}: Could not remove the database tables during uninstallation. Error details: {errorMessage}.', ['app' => 'MUBloggingModule', 'errorMessage' => $exception->getMessage()]);
    
            return false;
        }
    
        // remove all module vars
        $this->delVars();
    
        // remove category registry entries
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $registries = $this->container->get('zikula_categories_module.category_registry_repository')->findBy(['modname' => 'MUBloggingModule']);
        foreach ($registries as $registry) {
            $entityManager->remove($registry);
        }
        $entityManager->flush();
    
        // remind user about upload folders not being deleted
        $uploadPath = $this->container->getParameter('datadir') . '/MUBloggingModule/';
        $this->addFlash('status', $this->__f('The upload directories at "%path%" can be removed manually.', ['%path%' => $uploadPath]));
    
        // uninstallation successful
        return true;
    }
    
    /**
     * Build array with all entity classes for MUBloggingModule.
     *
     * @return string[] List of class names
     */
    protected function listEntityClasses()
    {
        $classNames = [];
        $classNames[] = 'MU\BloggingModule\Entity\PostEntity';
        $classNames[] = 'MU\BloggingModule\Entity\PostTranslationEntity';
        $classNames[] = 'MU\BloggingModule\Entity\PostCategoryEntity';
    
        return $classNames;
    }
}
