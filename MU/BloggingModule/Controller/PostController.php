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

namespace MU\BloggingModule\Controller;

use MU\BloggingModule\Controller\Base\AbstractPostController;

use RuntimeException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zikula\ThemeModule\Engine\Annotation\Theme;
use MU\BloggingModule\Entity\PostEntity;

use Symfony\Component\HttpFoundation\RedirectResponse;
use MU\BloggingModule\Helper\FeatureActivationHelper;

/**
 * Post controller class providing navigation and interaction functionality.
 */
class PostController extends AbstractPostController
{
    /**
     * @inheritDoc
     *
     * @Route("/admin/posts",
     *        methods = {"GET"}
     * )
     * @Cache(expires="+7 days", public=true)
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function adminIndexAction(Request $request)
    {
        return parent::adminIndexAction($request);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/posts",
     *        methods = {"GET"}
     * )
     * @Cache(expires="+7 days", public=true)
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }
    /**
     * @inheritDoc
     *
     * @Route("/admin/posts/view/{sort}/{sortdir}/{pos}/{num}.{_format}",
     *        requirements = {"sortdir" = "asc|desc|ASC|DESC", "pos" = "\d+", "num" = "\d+", "_format" = "html|rss"},
     *        defaults = {"sort" = "", "sortdir" = "asc", "pos" = 1, "num" = 10, "_format" = "html"},
     *        methods = {"GET"}
     * )
     * @Cache(expires="+2 hours", public=false)
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     * @param string $sort         Sorting field
     * @param string $sortdir      Sorting direction
     * @param int    $pos          Current pager position
     * @param int    $num          Amount of entries to display
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function adminViewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return parent::adminViewAction($request, $sort, $sortdir, $pos, $num);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/posts/view/{sort}/{sortdir}/{pos}/{num}.{_format}",
     *        requirements = {"sortdir" = "asc|desc|ASC|DESC", "pos" = "\d+", "num" = "\d+", "_format" = "html|rss"},
     *        defaults = {"sort" = "", "sortdir" = "asc", "pos" = 1, "num" = 10, "_format" = "html"},
     *        methods = {"GET"}
     * )
     * @Cache(expires="+2 hours", public=false)
     *
     * @param Request $request Current request instance
     * @param string $sort         Sorting field
     * @param string $sortdir      Sorting direction
     * @param int    $pos          Current pager position
     * @param int    $num          Amount of entries to display
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function viewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return parent::viewAction($request, $sort, $sortdir, $pos, $num);
    }
    /**
     * @inheritDoc
     *
     * @Route("/admin/post/edit/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"id" = "0", "_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @Cache(expires="+30 minutes", public=false)
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by form handler if post to be edited isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function adminEditAction(Request $request)
    {
        return parent::adminEditAction($request);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/post/edit/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"id" = "0", "_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @Cache(expires="+30 minutes", public=false)
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by form handler if post to be edited isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function editAction(Request $request)
    {
        return parent::editAction($request);
    }
    /**
     * @inheritDoc
     *
     * @Route("/admin/post/delete/{slug}.{_format}",
     *        requirements = {"slug" = "[^/.]+", "_format" = "html"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @ParamConverter("post", class="MUBloggingModule:PostEntity", options = {"repository_method" = "selectBySlug", "mapping": {"slug": "slugTitle"}, "map_method_signature" = true})
     * @Cache(lastModified="post.getUpdatedDate()", ETag="'Post' ~ post.getid() ~ post.getUpdatedDate().format('U')")
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     * @param PostEntity $post Treated post instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if post to be deleted isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function adminDeleteAction(Request $request, PostEntity $post)
    {
        return parent::adminDeleteAction($request, $post);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/post/delete/{slug}.{_format}",
     *        requirements = {"slug" = "[^/.]+", "_format" = "html"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @ParamConverter("post", class="MUBloggingModule:PostEntity", options = {"repository_method" = "selectBySlug", "mapping": {"slug": "slugTitle"}, "map_method_signature" = true})
     * @Cache(lastModified="post.getUpdatedDate()", ETag="'Post' ~ post.getid() ~ post.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param PostEntity $post Treated post instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if post to be deleted isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function deleteAction(Request $request, PostEntity $post)
    {
        return parent::deleteAction($request, $post);
    }
    /**
     * @inheritDoc
     *
     * @Route("/admin/post/{slug}.{_format}",
     *        requirements = {"slug" = "[^/.]+", "_format" = "html"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET"}
     * )
     * @ParamConverter("post", class="MUBloggingModule:PostEntity", options = {"repository_method" = "selectBySlug", "mapping": {"slug": "slugTitle"}, "map_method_signature" = true})
     * @Cache(lastModified="post.getUpdatedDate()", ETag="'Post' ~ post.getid() ~ post.getUpdatedDate().format('U')")
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     * @param PostEntity $post Treated post instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if post to be displayed isn't found
     */
    public function adminDisplayAction(Request $request, PostEntity $post)
    {
        return parent::adminDisplayAction($request, $post);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/post/{slug}.{_format}",
     *        requirements = {"slug" = "[^/.]+", "_format" = "html"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET"}
     * )
     * @ParamConverter("post", class="MUBloggingModule:PostEntity", options = {"repository_method" = "selectBySlug", "mapping": {"slug": "slugTitle"}, "map_method_signature" = true})
     * @Cache(lastModified="post.getUpdatedDate()", ETag="'Post' ~ post.getid() ~ post.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param PostEntity $post Treated post instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if post to be displayed isn't found
     */
    public function displayAction(Request $request, PostEntity $post)
    {
        return parent::displayAction($request, $post);
    }

    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @Route("/admin/posts/handleSelectedEntries",
     *        methods = {"POST"}
     * )
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     *
     * @return RedirectResponse
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function adminHandleSelectedEntriesAction(Request $request)
    {
        return parent::adminHandleSelectedEntriesAction($request);
    }
    
    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @Route("/posts/handleSelectedEntries",
     *        methods = {"POST"}
     * )
     *
     * @param Request $request Current request instance
     *
     * @return RedirectResponse
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function handleSelectedEntriesAction(Request $request)
    {
        return parent::handleSelectedEntriesAction($request);
    }
    
    /**
     * This method includes the common implementation code for adminDisplay() and display().
     */
    protected function displayInternal(Request $request, PostEntity $post, $isAdmin = false)
    {
    	// parameter specifying which type of objects we are treating
    	$objectType = 'post';
    	$permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_READ;
    	if (!$this->hasPermission('MUBloggingModule:' . ucfirst($objectType) . ':', '::', $permLevel)) {
    		throw new AccessDeniedException();
    	}
    	// create identifier for permission check
    	$instanceId = $post->getKey();
    	if (!$this->hasPermission('MUBloggingModule:' . ucfirst($objectType) . ':', $instanceId . '::', $permLevel)) {
    		throw new AccessDeniedException();
    	}
    
    	$templateParameters = [
    			'routeArea' => $isAdmin ? 'admin' : '',
    			$objectType => $post
    	];
    
    	$featureActivationHelper = $this->get('mu_blogging_module.feature_activation_helper');
    	if ($featureActivationHelper->isEnabled(FeatureActivationHelper::CATEGORIES, $objectType)) {
    		if (!$this->get('mu_blogging_module.category_helper')->hasPermission($post)) {
    			throw new AccessDeniedException();
    		}
    	}
    
    	$controllerHelper = $this->get('mu_blogging_module.controller_helper');
    	$templateParameters = $controllerHelper->processDisplayActionParameters($objectType, $templateParameters, true);
    	 
    	$entityFactory = $this->container->get('mu_blogging_module.entity_factory');
    	$repository = $entityFactory->getRepository('post');
    
    	$articles = $post['relevantArticles'];
    	if ($articles != '') {
    		$relevantArticles = array();
    		$relevantArticlesArray = explode(',', $articles);
    		foreach ($relevantArticlesArray as $post) {
    			$thisPost = $repository->find($post);
    			$relevantArticles[] = $thisPost;
    		}
    		$templateParameters['relevantPosts'] = $relevantArticles;
    	}
    
    	// fetch and return the appropriate template
    	$response = $this->get('mu_blogging_module.view_helper')->processTemplate($objectType, 'display', $templateParameters);
    
    	return $response;
    }

    // feel free to add your own controller methods here
}
