<?php
/**
 * Blogging.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link http://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\BloggingModule\Helper\Base;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Composite;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\Core\RouteUrl;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;
use Zikula\SearchModule\Entity\SearchResultEntity;
use Zikula\SearchModule\SearchableInterface;
use MU\BloggingModule\Entity\Factory\EntityFactory;
use MU\BloggingModule\Helper\CategoryHelper;
use MU\BloggingModule\Helper\ControllerHelper;
use MU\BloggingModule\Helper\EntityDisplayHelper;
use MU\BloggingModule\Helper\FeatureActivationHelper;

/**
 * Search helper base class.
 */
abstract class AbstractSearchHelper implements SearchableInterface
{
    use TranslatorTrait;
    
    /**
     * @var PermissionApiInterface
     */
    protected $permissionApi;
    
    /**
     * @var SessionInterface
     */
    private $session;
    
    /**
     * @var Request
     */
    private $request;
    
    /**
     * @var EntityFactory
     */
    private $entityFactory;
    
    /**
     * @var ControllerHelper
     */
    private $controllerHelper;
    
    /**
     * @var EntityDisplayHelper
     */
    protected $entityDisplayHelper;
    
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
     * @param TranslatorInterface $translator          Translator service instance
     * @param PermissionApiInterface    $permissionApi   PermissionApi service instance
     * @param SessionInterface    $session             Session service instance
     * @param RequestStack        $requestStack        RequestStack service instance
     * @param EntityFactory       $entityFactory       EntityFactory service instance
     * @param ControllerHelper    $controllerHelper    ControllerHelper service instance
     * @param EntityDisplayHelper $entityDisplayHelper EntityDisplayHelper service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     * @param CategoryHelper      $categoryHelper      CategoryHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        PermissionApiInterface $permissionApi,
        SessionInterface $session,
        RequestStack $requestStack,
        EntityFactory $entityFactory,
        ControllerHelper $controllerHelper,
        EntityDisplayHelper $entityDisplayHelper,
        FeatureActivationHelper $featureActivationHelper,
        CategoryHelper $categoryHelper
    ) {
        $this->setTranslator($translator);
        $this->permissionApi = $permissionApi;
        $this->session = $session;
        $this->request = $requestStack->getCurrentRequest();
        $this->entityFactory = $entityFactory;
        $this->controllerHelper = $controllerHelper;
        $this->entityDisplayHelper = $entityDisplayHelper;
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
    public function amendForm(FormBuilderInterface $builder)
    {
        if (!$this->permissionApi->hasPermission('MUBloggingModule::', '::', ACCESS_READ)) {
            return '';
        }
    
        $builder->add('active', HiddenType::class, [
            'data' => true
        ]);
    
        $searchTypes = $this->getSearchTypes();
    
        foreach ($searchTypes as $searchType => $typeInfo) {
            $builder->add('active_' . $searchType, CheckboxType::class, [
                'value' => $typeInfo['value'],
                'label' => $typeInfo['label'],
                'label_attr' => ['class' => 'checkbox-inline'],
                'required' => false
            ]);
        }
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
        $searchTypes = $this->getSearchTypes();
    
        foreach ($searchTypes as $searchTypeCode => $typeInfo) {
            $objectType = $typeInfo['value'];
            $isActivated = false;
            if ($this->request->isMethod('GET')) {
                $isActivated = $this->request->query->get('active_' . $searchTypeCode, false);
            } elseif ($this->request->isMethod('POST')) {
                $isActivated = $this->request->request->get('active_' . $searchTypeCode, false);
            }
            if (!$isActivated) {
                continue;
            }
            $whereArray = [];
            $languageField = null;
            switch ($objectType) {
                case 'post':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.title';
                    $whereArray[] = 'tbl.permalink';
                    $whereArray[] = 'tbl.descriptionForGoogle';
                    $whereArray[] = 'tbl.forWhichLanguage';
                    $whereArray[] = 'tbl.imageForArticle';
                    $whereArray[] = 'tbl.descriptionOfImageForArticle';
                    $whereArray[] = 'tbl.summaryOfPost';
                    $whereArray[] = 'tbl.content';
                    $whereArray[] = 'tbl.content2';
                    $whereArray[] = 'tbl.advertising';
                    $whereArray[] = 'tbl.positionOfAdvertising1';
                    $whereArray[] = 'tbl.positionOfBlock';
                    $whereArray[] = 'tbl.content3';
                    $whereArray[] = 'tbl.content4';
                    $whereArray[] = 'tbl.advertising2';
                    $whereArray[] = 'tbl.positionOfAdvertising2';
                    $whereArray[] = 'tbl.positionOfBlock2';
                    $whereArray[] = 'tbl.content5';
                    $whereArray[] = 'tbl.content6';
                    $whereArray[] = 'tbl.advertising3';
                    $whereArray[] = 'tbl.positionOfAdvertising3';
                    $whereArray[] = 'tbl.positionOfBlock3';
                    $whereArray[] = 'tbl.similarArticles';
                    $whereArray[] = 'tbl.relevantArticles';
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
    
            $descriptionFieldName = $this->entityDisplayHelper->getDescriptionFieldName($objectType);
    
            $entitiesWithDisplayAction = ['post'];
    
            foreach ($entities as $entity) {
                $urlArgs = $entity->createUrlArgs();
                $hasDisplayAction = in_array($objectType, $entitiesWithDisplayAction);
    
                // perform permission check
                if (!$this->permissionApi->hasPermission('MUBloggingModule:' . ucfirst($objectType) . ':', $entity->getKey() . '::', ACCESS_OVERVIEW)) {
                    continue;
                }
    
                if (in_array($objectType, ['post'])) {
                    if ($this->featureActivationHelper->isEnabled(FeatureActivationHelper::CATEGORIES, $objectType)) {
                        if (!$this->categoryHelper->hasPermission($entity)) {
                            continue;
                        }
                    }
                }
    
                $description = !empty($descriptionFieldName) ? $entity[$descriptionFieldName] : '';
                $created = isset($entity['createdDate']) ? $entity['createdDate'] : null;
    
                $urlArgs['_locale'] = (null !== $languageField && !empty($entity[$languageField])) ? $entity[$languageField] : $this->request->getLocale();
    
                $formattedTitle = $this->entityDisplayHelper->getFormattedTitle($entity);
                $displayUrl = $hasDisplayAction ? new RouteUrl('mubloggingmodule_' . strtolower($objectType) . '_display', $urlArgs) : '';
    
                $result = new SearchResultEntity();
                $result->setTitle($formattedTitle)
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
