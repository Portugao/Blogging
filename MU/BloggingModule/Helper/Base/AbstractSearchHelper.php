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

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Composite;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\Core\RouteUrl;
use Zikula\PermissionsModule\Api\PermissionApi;
use Zikula\SearchModule\Entity\SearchResultEntity;
use Zikula\SearchModule\SearchableInterface;
use MU\BloggingModule\Entity\Factory\BloggingFactory;
use MU\BloggingModule\Helper\CategoryHelper;
use MU\BloggingModule\Helper\ControllerHelper;
use MU\BloggingModule\Helper\FeatureActivationHelper;

/**
 * Search helper base class.
 */
abstract class AbstractSearchHelper implements SearchableInterface
{
    use TranslatorTrait;
    
    /**
     * @var PermissionApi
     */
    protected $permissionApi;
    
    /**
     * @var EngineInterface
     */
    private $templateEngine;
    
    /**
     * @var SessionInterface
     */
    private $session;
    
    /**
     * @var Request
     */
    private $request;
    
    /**
     * @var BloggingFactory
     */
    private $entityFactory;
    
    /**
     * @var ControllerHelper
     */
    private $controllerHelper;
    
    /**
     * @var FeatureActivationHelper
     */
    private $featureActivationHelper;
    
    /**
     * @var CategoryHelper
     */
    private $categoryHelper;
    
    /**
     * SearchHelper constructor.
     *
     * @param TranslatorInterface $translator   Translator service instance
     * @param PermissionApi    $permissionApi   PermissionApi service instance
     * @param EngineInterface  $templateEngine  Template engine service instance
     * @param SessionInterface $session         Session service instance
     * @param RequestStack     $requestStack    RequestStack service instance
     * @param BloggingFactory $entityFactory EntityFactory service instance
     * @param ControllerHelper $controllerHelper ControllerHelper service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     * @param CategoryHelper   $categoryHelper CategoryHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        PermissionApi $permissionApi,
        EngineInterface $templateEngine,
        SessionInterface $session,
        RequestStack $requestStack,
        BloggingFactory $entityFactory,
        ControllerHelper $controllerHelper,
        FeatureActivationHelper $featureActivationHelper,
        CategoryHelper $categoryHelper
    ) {
        $this->setTranslator($translator);
        $this->permissionApi = $permissionApi;
        $this->templateEngine = $templateEngine;
        $this->session = $session;
        $this->request = $requestStack->getCurrentRequest();
        $this->entityFactory = $entityFactory;
        $this->controllerHelper = $controllerHelper;
        $this->featureActivationHelper = $featureActivationHelper;
        $this->categoryHelper = $categoryHelper;
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
     * @inheritDoc
     */
    public function getOptions($active, $modVars = null)
    {
        if (!$this->permissionApi->hasPermission('MUBloggingModule::', '::', ACCESS_READ)) {
            return '';
        }
    
        $templateParameters = [];
    
        $searchTypes = $this->getSearchTypes();
    
        foreach ($searchTypes as $searchType => $typeInfo) {
            $templateParameters['active_' . $searchType] = true;
        }
    
        return $this->templateEngine->renderResponse('@MUBloggingModule/Search/options.html.twig', $templateParameters)->getContent();
    }
    
    /**
     * @inheritDoc
     */
    public function getResults(array $words, $searchType = 'AND', $modVars = null)
    {
        if (!$this->permissionApi->hasPermission('MUBloggingModule::', '::', ACCESS_READ)) {
            return [];
        }
    
        // initialise array for results
        $results = [];
    
        // retrieve list of activated object types
        $searchTypes = isset($modVars['objectTypes']) ? (array)$modVars['objectTypes'] : [];
        if (!is_array($searchTypes) || !count($searchTypes)) {
            if ($this->request->isMethod('GET')) {
                $searchTypes = $this->request->query->get('mUBloggingModuleSearchTypes', []);
            } elseif ($this->request->isMethod('POST')) {
                $searchTypes = $this->request->request->get('mUBloggingModuleSearchTypes', []);
            }
        }
    
        foreach ($searchTypes as $objectType) {
            $whereArray = [];
            $languageField = null;
            switch ($objectType) {
                case 'post':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.title';
                    $whereArray[] = 'tbl.permalink';
                    $whereArray[] = 'tbl.descriptionForGoogle';
                    $whereArray[] = 'tbl.imageForArticle';
                    $whereArray[] = 'tbl.summaryOfPost';
                    $whereArray[] = 'tbl.content';
                    $whereArray[] = 'tbl.block';
                    $whereArray[] = 'tbl.advertising';
                    $whereArray[] = 'tbl.content2';
                    $whereArray[] = 'tbl.advertising2';
                    $whereArray[] = 'tbl.content3';
                    $whereArray[] = 'tbl.advertising3';
                    $whereArray[] = 'tbl.block2';
                    $whereArray[] = 'tbl.block3';
                    $whereArray[] = 'tbl.similarArticles';
                    break;
            }
    
            $repository = $this->entityFactory->getRepository($objectType);
    
            // build the search query without any joins
            $qb = $repository->genericBaseQuery('', '', false);
    
            // build where expression for given search type
            $whereExpr = $this->formatWhere($qb, $words, $whereArray, $searchType);
            $qb->andWhere($whereExpr);
    
            $query = $qb->getQuery();
    
            // set a sensitive limit
            $query->setFirstResult(0)
                  ->setMaxResults(250);
    
            // fetch the results
            $entities = $query->getResult();
    
            if (count($entities) == 0) {
                continue;
            }
    
            $descriptionField = $repository->getDescriptionFieldName();
    
            $entitiesWithDisplayAction = ['post'];
    
            foreach ($entities as $entity) {
                $urlArgs = $entity->createUrlArgs();
                $hasDisplayAction = in_array($objectType, $entitiesWithDisplayAction);
    
                $instanceId = $entity->createCompositeIdentifier();
                // perform permission check
                if (!$this->permissionApi->hasPermission('MUBloggingModule:' . ucfirst($objectType) . ':', $instanceId . '::', ACCESS_OVERVIEW)) {
                    continue;
                }
    
                if (in_array($objectType, ['post'])) {
                    if ($this->featureActivationHelper->isEnabled(FeatureActivationHelper::CATEGORIES, $objectType)) {
                        if (!$this->categoryHelper->hasPermission($entity)) {
                            continue;
                        }
                    }
                }
    
                $description = !empty($descriptionField) ? $entity[$descriptionField] : '';
                $created = isset($entity['createdDate']) ? $entity['createdDate'] : null;
    
                $urlArgs['_locale'] = (null !== $languageField && !empty($entity[$languageField])) ? $entity[$languageField] : $this->request->getLocale();
    
                $displayUrl = $hasDisplayAction ? new RouteUrl('mubloggingmodule_' . $objectType . '_display', $urlArgs) : '';
    
                $result = new SearchResultEntity();
                $result->setTitle($entity->getTitleFromDisplayPattern())
                    ->setText($description)
                    ->setModule('MUBloggingModule')
                    ->setCreated($created)
                    ->setSesid($this->session->getId())
                    ->setUrl($displayUrl);
                $results[] = $result;
            }
        }
    
        return $results;
    }
    
    /**
     * Returns list of supported search types.
     *
     * @return array
     */
    protected function getSearchTypes()
    {
        $searchTypes = [
            'mUBloggingModulePosts' => [
                'value' => 'post',
                'label' => $this->__('Posts')
            ]
        ];
    
        $allowedTypes = $this->controllerHelper->getObjectTypes('helper', ['helper' => 'search', 'action' => 'getSearchTypes']);
        $allowedSearchTypes = [];
        foreach ($searchTypes as $searchType => $typeInfo) {
            if (!in_array($typeInfo['value'], $allowedTypes)) {
                continue;
            }
            $allowedSearchTypes[$searchType] = $typeInfo;
        }
    
        return $allowedSearchTypes;
    }
    
    /**
     * @inheritDoc
     */
    public function getErrors()
    {
        return [];
    }
    
    /**
     * Construct a QueryBuilder Where orX|andX Expr instance.
     *
     * @param QueryBuilder $qb
     * @param array $words the words to query for
     * @param array $fields
     * @param string $searchtype AND|OR|EXACT
     *
     * @return null|Composite
     */
    protected function formatWhere(QueryBuilder $qb, array $words, array $fields, $searchtype = 'AND')
    {
        if (empty($words) || empty($fields)) {
            return null;
        }
    
        $method = ($searchtype == 'OR') ? 'orX' : 'andX';
        /** @var $where Composite */
        $where = $qb->expr()->$method();
        $i = 1;
        foreach ($words as $word) {
            $subWhere = $qb->expr()->orX();
            foreach ($fields as $field) {
                $expr = $qb->expr()->like($field, "?$i");
                $subWhere->add($expr);
                $qb->setParameter($i, '%' . $word . '%');
                $i++;
            }
            $where->add($subWhere);
        }
    
        return $where;
    }
}
