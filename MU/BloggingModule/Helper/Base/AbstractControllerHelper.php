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
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\Component\SortableColumns\SortableColumns;
use Zikula\Core\RouteUrl;
use Zikula\ExtensionsModule\Api\VariableApi;
use MU\BloggingModule\Entity\Factory\BloggingFactory;
use MU\BloggingModule\Helper\FeatureActivationHelper;
use MU\BloggingModule\Helper\ImageHelper;
use MU\BloggingModule\Helper\ModelHelper;

/**
 * Helper base class for controller layer methods.
 */
abstract class AbstractControllerHelper
{
    use TranslatorTrait;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var VariableApi
     */
    protected $variableApi;

    /**
     * @var BloggingFactory
     */
    protected $entityFactory;

    /**
     * @var ModelHelper
     */
    protected $modelHelper;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var FeatureActivationHelper
     */
    protected $featureActivationHelper;

    /**
     * ControllerHelper constructor.
     *
     * @param TranslatorInterface $translator      Translator service instance
     * @param RequestStack        $requestStack    RequestStack service instance
     * @param SessionInterface    $session         Session service instance
     * @param LoggerInterface     $logger          Logger service instance
     * @param FormFactoryInterface $formFactory    FormFactory service instance
     * @param VariableApi         $variableApi     VariableApi service instance
     * @param BloggingFactory $entityFactory BloggingFactory service instance
     * @param ModelHelper         $modelHelper     ModelHelper service instance
     * @param ImageHelper         $imageHelper     ImageHelper service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        RequestStack $requestStack,
        SessionInterface $session,
        LoggerInterface $logger,
        FormFactoryInterface $formFactory,
        VariableApi $variableApi,
        BloggingFactory $entityFactory,
        ModelHelper $modelHelper,
        ImageHelper $imageHelper,
        FeatureActivationHelper $featureActivationHelper
    ) {
        $this->setTranslator($translator);
        $this->request = $requestStack->getCurrentRequest();
        $this->session = $session;
        $this->logger = $logger;
        $this->formFactory = $formFactory;
        $this->variableApi = $variableApi;
        $this->entityFactory = $entityFactory;
        $this->modelHelper = $modelHelper;
        $this->imageHelper = $imageHelper;
        $this->featureActivationHelper = $featureActivationHelper;
    }

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
     * Returns an array of all allowed object types in MUBloggingModule.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, helper, actionHandler, block, contentType, util)
     * @param array  $args    Additional arguments
     *
     * @return array List of allowed object types
     */
    public function getObjectTypes($context = '', $args = [])
    {
        if (!in_array($context, ['controllerAction', 'api', 'helper', 'actionHandler', 'block', 'contentType', 'util'])) {
            $context = 'controllerAction';
        }
    
        $allowedObjectTypes = [];
        $allowedObjectTypes[] = 'post';
        $allowedObjectTypes[] = 'image';
    
        return $allowedObjectTypes;
    }

    /**
     * Returns the default object type in MUBloggingModule.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, helper, actionHandler, block, contentType, util)
     * @param array  $args    Additional arguments
     *
     * @return string The name of the default object type
     */
    public function getDefaultObjectType($context = '', $args = [])
    {
        if (!in_array($context, ['controllerAction', 'api', 'helper', 'actionHandler', 'block', 'contentType', 'util'])) {
            $context = 'controllerAction';
        }
    
        $defaultObjectType = 'post';
    
        return $defaultObjectType;
    }

    /**
     * Retrieve identifier parameters for a given object type.
     *
     * @param Request $request    The current request
     * @param array   $args       List of arguments used as fallback if request does not contain a field
     * @param string  $objectType Name of treated entity type
     *
     * @return array List of fetched identifiers
     */
    public function retrieveIdentifier(Request $request, array $args, $objectType = '')
    {
        $idFields = $this->entityFactory->getIdFields($objectType);
        $idValues = [];
        $routeParams = $request->get('_route_params', []);
        foreach ($idFields as $idField) {
            $defaultValue = isset($args[$idField]) && is_numeric($args[$idField]) ? $args[$idField] : 0;
            if ($this->entityFactory->hasCompositeKeys($objectType)) {
                // composite key may be alphanumeric
                if (array_key_exists($idField, $routeParams)) {
                    $id = !empty($routeParams[$idField]) ? $routeParams[$idField] : $defaultValue;
                } elseif ($request->query->has($idField)) {
                    $id = $request->query->getAlnum($idField, $defaultValue);
                } else {
                    $id = $defaultValue;
                }
            } else {
                // single identifier
                if (array_key_exists($idField, $routeParams)) {
                    $id = (int) !empty($routeParams[$idField]) ? $routeParams[$idField] : $defaultValue;
                } elseif ($request->query->has($idField)) {
                    $id = $request->query->getInt($idField, $defaultValue);
                } else {
                    $id = $defaultValue;
                }
            }
    
            // fallback if id has not been found yet
            if (!$id && $idField != 'id' && count($idFields) == 1) {
                $defaultValue = isset($args['id']) && is_numeric($args['id']) ? $args['id'] : 0;
                if (array_key_exists('id', $routeParams)) {
                    $id = (int) !empty($routeParams['id']) ? $routeParams['id'] : $defaultValue;
                } elseif ($request->query->has('id')) {
                    $id = (int) $request->query->getInt('id', $defaultValue);
                } else {
                    $id = $defaultValue;
                }
            }
            $idValues[$idField] = $id;
        }
    
        return $idValues;
    }

    /**
     * Checks if all identifiers are set properly.
     *
     * @param array  $idValues List of identifier field values
     *
     * @return boolean Whether all identifiers are set or not
     */
    public function isValidIdentifier(array $idValues)
    {
        if (!count($idValues)) {
            return false;
        }
    
        foreach ($idValues as $idField => $idValue) {
            if (!$idValue) {
                return false;
            }
        }
    
        return true;
    }

    /**
     * Create nice permalinks.
     *
     * @param string $name The given object title
     *
     * @return string processed permalink
     * @deprecated made obsolete by Doctrine extensions
     */
    public function formatPermalink($name)
    {
        $name = str_replace(
            ['�', '�', '�', '�', '�', '�', '�', '.', '?', '"', '/', ':', '�', '�', '�'],
            ['ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss', '', '', '', '-', '-', 'e', 'e', 'a'],
            $name
        );
        $name = preg_replace("#(\s*\/\s*|\s*\+\s*|\s+)#", '-', strtolower($name));
    
        return $name;
    }

    /**
     * Processes the parameters for a view action.
     * This includes handling pagination, quick navigation forms and other aspects.
     *
     * @param string          $objectType         Name of treated entity type
     * @param SortableColumns $sortableColumns    Used SortableColumns instance
     * @param array           $templateParameters Template data
     * @param boolean         $supportsHooks      Whether hooks are supported or not
     *
     * @return array Enriched template parameters used for creating the response
     */
    public function processViewActionParameters($objectType, SortableColumns $sortableColumns, array $templateParameters = [], $supportsHooks = false)
    {
        $contextArgs = ['controller' => $objectType, 'action' => 'view'];
        if (!in_array($objectType, $this->getObjectTypes('controllerAction', $contextArgs))) {
            throw new Exception($this->__('Error! Invalid object type received.'));
        }
    
        $request = $this->request;
        $repository = $this->entityFactory->getRepository($objectType);
        $repository->setRequest($request);
    
        // parameter for used sorting field
        $sort = $request->query->get('sort', '');
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields())) {
            $sort = $repository->getDefaultSortingField();
            $request->query->set('sort', $sort);
            // set default sorting in route parameters (e.g. for the pager)
            $routeParams = $request->attributes->get('_route_params');
            $routeParams['sort'] = $sort;
            $request->attributes->set('_route_params', $routeParams);
        }
    
    
        $showOwnEntries = $request->query->getInt('own', $this->variableApi->get('MUBloggingModule', 'showOnlyOwnEntries', 0));
        $showAllEntries = $request->query->getInt('all', 0);
    
    
        if (true === $supportsHooks) {
            $currentUrlArgs = [];
            if ($showAllEntries == 1) {
                $currentUrlArgs['all'] = 1;
            }
            if ($showOwnEntries == 1) {
                $currentUrlArgs['own'] = 1;
            }
        }
    
        $resultsPerPage = 0;
        if ($showAllEntries != 1) {
            // the number of items displayed on a page for pagination
            $resultsPerPage = $request->query->getInt('num', 0);
            if (in_array($resultsPerPage, [0, 10])) {
                $resultsPerPage = $this->variableApi->get('MUBloggingModule', $objectType . 'EntriesPerPage', 10);
            }
        }
    
        $additionalParameters = $repository->getAdditionalTemplateParameters($this->imageHelper, 'controllerAction', $contextArgs);
    
        $additionalUrlParameters = [
            'all' => $showAllEntries,
            'own' => $showOwnEntries,
            'num' => $resultsPerPage
        ];
        foreach ($additionalParameters as $parameterName => $parameterValue) {
            if (false !== stripos($parameterName, 'thumbRuntimeOptions')) {
                continue;
            }
            $additionalUrlParameters[$parameterName] = $parameterValue;
        }
    
        $templateParameters['all'] = $showAllEntries;
        $templateParameters['own'] = $showOwnEntries;
        $templateParameters['num'] = $resultsPerPage;
        $templateParameters['tpl'] = $request->query->getAlnum('tpl', '');
    
        $quickNavForm = $this->formFactory->create('MU\BloggingModule\Form\Type\QuickNavigation\\' . ucfirst($objectType) . 'QuickNavType', $templateParameters);
        if ($quickNavForm->handleRequest($request) && $quickNavForm->isSubmitted()) {
            $quickNavData = $quickNavForm->getData();
            foreach ($quickNavData as $fieldName => $fieldValue) {
                if ($fieldName == 'routeArea') {
                    continue;
                }
                if ($fieldName == 'all') {
                    $showAllEntries = $additionalUrlParameters['all'] = $templateParameters['all'] = $fieldValue;
                } elseif ($fieldName == 'own') {
                    $showOwnEntries = $additionalUrlParameters['own'] = $templateParameters['own'] = $fieldValue;
                } elseif ($fieldName == 'num') {
                    $resultsPerPage = $additionalUrlParameters['num'] = $fieldValue;
                } else {
                    // set filter as query argument, fetched inside repository
                    $request->query->set($fieldName, $fieldValue);
                }
            }
        }
        $sort = $request->query->get('sort');
        $sortdir = $request->query->get('sortdir');
        $sortableColumns->setOrderBy($sortableColumns->getColumn($sort), strtoupper($sortdir));
        $sortableColumns->setAdditionalUrlParameters($additionalUrlParameters);
        $templateParameters['sort'] = $sort;
        $templateParameters['sortdir'] = $sortdir;
    
        $where = '';
        if ($showAllEntries == 1) {
            // retrieve item list without pagination
            $entities = $repository->selectWhere($where, $sort . ' ' . $sortdir);
        } else {
            // the current offset which is used to calculate the pagination
            $currentPage = $request->query->getInt('pos', 1);
    
            // retrieve item list with pagination
            list($entities, $objectCount) = $repository->selectWherePaginated($where, $sort . ' ' . $sortdir, $currentPage, $resultsPerPage);
    
            $templateParameters['currentPage'] = $currentPage;
            $templateParameters['pager'] = [
                'amountOfItems' => $objectCount,
                'itemsPerPage' => $resultsPerPage
            ];
        }
    
        if (true === $supportsHooks) {
            // build RouteUrl instance for display hooks
            $currentUrlArgs['_locale'] = $request->getLocale();
            $currentUrlObject = new RouteUrl('mubloggingmodule_' . $objectType . '_' . /*$templateParameters['routeArea'] . */'view', $currentUrlArgs);
        }
    
        $templateParameters['items'] = $entities;
        $templateParameters['sort'] = $sort;
        $templateParameters['sortdir'] = $sortdir;
        $templateParameters['num'] = $resultsPerPage;
        if (true === $supportsHooks) {
            $templateParameters['currentUrlObject'] = $currentUrlObject;
        }
        $templateParameters = array_merge($templateParameters, $additionalParameters);
    
        $templateParameters['sort'] = $sortableColumns->generateSortableColumns();
        $templateParameters['quickNavForm'] = $quickNavForm->createView();
    
        $templateParameters['showAllEntries'] = $templateParameters['all'];
        $templateParameters['showOwnEntries'] = $templateParameters['own'];
    
        $templateParameters['featureActivationHelper'] = $this->featureActivationHelper;
        $templateParameters['canBeCreated'] = $this->modelHelper->canBeCreated($objectType);
    
        return $templateParameters;
    }

    /**
     * Processes the parameters for a display action.
     *
     * @param string  $objectType         Name of treated entity type
     * @param array   $templateParameters Template data
     * @param boolean $supportsHooks      Whether hooks are supported or not
     *
     * @return array Enriched template parameters used for creating the response
     */
    public function processDisplayActionParameters($objectType, array $templateParameters = [], $supportsHooks = false)
    {
        $contextArgs = ['controller' => $objectType, 'action' => 'display'];
        if (!in_array($objectType, $this->getObjectTypes('controllerAction', $contextArgs))) {
            throw new Exception($this->__('Error! Invalid object type received.'));
        }
    
        $repository = $this->entityFactory->getRepository($objectType);
        $repository->setRequest($this->request);
        $entity = $templateParameters[$objectType];
    
        if (true === $supportsHooks) {
            // build RouteUrl instance for display hooks
            $currentUrlArgs = $entity->createUrlArgs();
            $currentUrlArgs['_locale'] = $this->request->getLocale();
            $currentUrlObject = new RouteUrl('mubloggingmodule_' . $objectType . '_' . /*$templateParameters['routeArea'] . */'display', $currentUrlArgs);
            $templateParameters['currentUrlObject'] = $currentUrlObject;
        }
    
        $additionalParameters = $repository->getAdditionalTemplateParameters($this->imageHelper, 'controllerAction', $contextArgs);
        $templateParameters = array_merge($templateParameters, $additionalParameters);
        $templateParameters['featureActivationHelper'] = $this->featureActivationHelper;
    
        return $templateParameters;
    }

    /**
     * Processes the parameters for an edit action.
     *
     * @param string  $objectType         Name of treated entity type
     * @param array   $templateParameters Template data
     *
     * @return array Enriched template parameters used for creating the response
     */
    public function processEditActionParameters($objectType, array $templateParameters = [])
    {
        $contextArgs = ['controller' => $objectType, 'action' => 'edit'];
        if (!in_array($objectType, $this->getObjectTypes('controllerAction', $contextArgs))) {
            throw new Exception($this->__('Error! Invalid object type received.'));
        }
    
        $repository = $this->entityFactory->getRepository($objectType);
        $repository->setRequest($this->request);
    
        $additionalParameters = $repository->getAdditionalTemplateParameters($this->imageHelper, 'controllerAction', $contextArgs);
        $templateParameters = array_merge($templateParameters, $additionalParameters);
        $templateParameters['featureActivationHelper'] = $this->featureActivationHelper;
    
        return $templateParameters;
    }

    /**
     * Processes the parameters for a delete action.
     *
     * @param string  $objectType         Name of treated entity type
     * @param array   $templateParameters Template data
     * @param boolean $supportsHooks      Whether hooks are supported or not
     *
     * @return array Enriched template parameters used for creating the response
     */
    public function processDeleteActionParameters($objectType, array $templateParameters = [], $supportsHooks = false)
    {
        $contextArgs = ['controller' => $objectType, 'action' => 'delete'];
        if (!in_array($objectType, $this->getObjectTypes('controllerAction', $contextArgs))) {
            throw new Exception($this->__('Error! Invalid object type received.'));
        }
    
        $repository = $this->entityFactory->getRepository($objectType);
        $repository->setRequest($this->request);
    
        $additionalParameters = $repository->getAdditionalTemplateParameters($this->imageHelper, 'controllerAction', $contextArgs);
        $templateParameters = array_merge($templateParameters, $additionalParameters);
    
        return $templateParameters;
    }
}
