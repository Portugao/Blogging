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

namespace MU\BloggingModule\Helper\Base;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\PermissionsModule\Api\PermissionApi;
use MU\BloggingModule\Entity\Factory\BloggingFactory;
use MU\BloggingModule\Helper\HookHelper;
use MU\BloggingModule\Helper\WorkflowHelper;

/**
 * Archive helper base class.
 */
abstract class AbstractArchiveHelper
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var PermissionApi
     */
    protected $permissionApi;

    /**
     * @var BloggingFactory
     */
    protected $entityFactory;

    /**
     * @var WorkflowHelper
     */
    protected $workflowHelper;

    /**
     * @var HookHelper
     */
    protected $hookHelper;

    /**
     * ArchiveHelper constructor.
     *
     * @param TranslatorInterface $translator     Translator service instance
     * @param SessionInterface    $session        Session service instance
     * @param LoggerInterface     $logger         Logger service instance
     * @param PermissionApi       $permissionApi  PermissionApi service instance
     * @param BloggingFactory $entityFactory BloggingFactory service instance
     * @param WorkflowHelper      $workflowHelper WorkflowHelper service instance
     * @param HookHelper          $hookHelper     HookHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        SessionInterface $session,
        LoggerInterface $logger,
        PermissionApi $permissionApi,
        BloggingFactory $entityFactory,
        WorkflowHelper $workflowHelper,
        HookHelper $hookHelper)
    {
        $this->translator = $translator;
        $this->session = $session;
        $this->logger = $logger;
        $this->permissionApi = $permissionApi;
        $this->entityFactory = $entityFactory;
        $this->workflowHelper = $workflowHelper;
        $this->hookHelper = $hookHelper;
    }

    /**
     * Moves obsolete data into the archive.
     */
    public function archiveObjects()
    {
        $randProbability = mt_rand(1, 1000);
    
        if ($randProbability < 750) {
            return;
        }
    
        $this->session->set('MUBloggingModuleAutomaticArchiving', true);
    
        // perform update for posts becoming archived
        $logArgs = ['app' => 'MUBloggingModule', 'entity' => 'post'];
        $this->logger->notice('{app}: Automatic archiving for the {entity} entity started.', $logArgs);
        $repository = $this->entityFactory->getRepository('post');
        $repository->archiveObjects($this->permissionApi, $this->session, $this->translator, $this->workflowHelper, $this->hookHelper);
        $this->logger->notice('{app}: Automatic archiving for the {entity} entity completed.', $logArgs);
    
        $this->session->del('MUBloggingModuleAutomaticArchiving');
    }
}