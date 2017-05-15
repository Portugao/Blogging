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

namespace MU\BloggingModule\Form\Type\QuickNavigation\Base;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\Bundle\FormExtensionBundle\Form\Type\LocaleType;
use Zikula\CategoriesModule\Form\Type\CategoriesType;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\SettingsModule\Api\ApiInterface\LocaleApiInterface;
use MU\BloggingModule\Form\Type\Field\MultiListType;
use MU\BloggingModule\Helper\EntityDisplayHelper;
use MU\BloggingModule\Helper\FeatureActivationHelper;
use MU\BloggingModule\Helper\ListEntriesHelper;

/**
 * Post quick navigation form type base class.
 */
abstract class AbstractPostQuickNavType extends AbstractType
{
    use TranslatorTrait;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EntityDisplayHelper
     */
    protected $entityDisplayHelper;

    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    /**
     * @var LocaleApiInterface
     */
    protected $localeApi;

    /**
     * @var FeatureActivationHelper
     */
    protected $featureActivationHelper;

    /**
     * PostQuickNavType constructor.
     *
     * @param TranslatorInterface $translator   Translator service instance
    * @param RequestStack        $requestStack RequestStack service instance
    * @param EntityDisplayHelper $entityDisplayHelper EntityDisplayHelper service instance
     * @param ListEntriesHelper   $listHelper   ListEntriesHelper service instance
     * @param LocaleApiInterface  $localeApi    LocaleApi service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        RequestStack $requestStack,
        EntityDisplayHelper $entityDisplayHelper,
        ListEntriesHelper $listHelper,
        LocaleApiInterface $localeApi,
        FeatureActivationHelper $featureActivationHelper
    ) {
        $this->setTranslator($translator);
        $this->request = $requestStack->getCurrentRequest();
        $this->entityDisplayHelper = $entityDisplayHelper;
        $this->listHelper = $listHelper;
        $this->localeApi = $localeApi;
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
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('all', HiddenType::class)
            ->add('own', HiddenType::class)
            ->add('tpl', HiddenType::class)
        ;

        if ($this->featureActivationHelper->isEnabled(FeatureActivationHelper::CATEGORIES, 'post')) {
            $this->addCategoriesField($builder, $options);
        }
        $this->addIncomingRelationshipFields($builder, $options);
        $this->addListFields($builder, $options);
        $this->addLocaleFields($builder, $options);
        $this->addSearchField($builder, $options);
        $this->addSortingFields($builder, $options);
        $this->addAmountField($builder, $options);
        $builder->add('updateview', SubmitType::class, [
            'label' => $this->__('OK'),
            'attr' => [
                'class' => 'btn btn-default btn-sm'
            ]
        ]);
    }

    /**
     * Adds a categories field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addCategoriesField(FormBuilderInterface $builder, array $options)
    {
        $objectType = 'post';
    
        $builder->add('categories', CategoriesType::class, [
            'label' => $this->__('Categories'),
            'empty_data' => [],
            'attr' => [
                'class' => 'input-sm category-selector',
                'title' => $this->__('This is an optional filter.')
            ],
            'help' => $this->__('This is an optional filter.'),
            'required' => false,
            'multiple' => true,
            'module' => 'MUBloggingModule',
            'entity' => ucfirst($objectType) . 'Entity',
            'entityCategoryClass' => 'MU\BloggingModule\Entity\\' . ucfirst($objectType) . 'CategoryEntity'
        ]);
    }

    /**
     * Adds fields for incoming relationships.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addIncomingRelationshipFields(FormBuilderInterface $builder, array $options)
    {
        $mainSearchTerm = '';
        if ($this->request->query->has('q')) {
            // remove current search argument from request to avoid filtering related items
            $mainSearchTerm = $this->request->query->get('q');
            $this->request->query->remove('q');
        }
    
        $entityDisplayHelper = $this->entityDisplayHelper;
        $choiceLabelClosure = function ($entity) use ($entityDisplayHelper) {
            return $entityDisplayHelper->getFormattedTitle($entity);
        };
        $builder->add('post', EntityType::class, [
            'class' => 'MUBloggingModule:PostEntity',
            'choice_label' => $choiceLabelClosure,
            'placeholder' => $this->__('All'),
            'required' => false,
            'label' => $this->__('Post'),
            'attr' => [
                'class' => 'input-sm'
            ]
        ]);
    
        if ($mainSearchTerm != '') {
            // readd current search argument
            $this->request->query->set('q', $mainSearchTerm);
        }
    }

    /**
     * Adds list fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addListFields(FormBuilderInterface $builder, array $options)
    {
        $listEntries = $this->listHelper->getEntries('post', 'workflowState');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('workflowState', ChoiceType::class, [
            'label' => $this->__('State'),
            'attr' => [
                'class' => 'input-sm'
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
        $listEntries = $this->listHelper->getEntries('post', 'positionOfAdvertising1');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('positionOfAdvertising1', ChoiceType::class, [
            'label' => $this->__('Position of advertising 1'),
            'attr' => [
                'class' => 'input-sm'
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
        $listEntries = $this->listHelper->getEntries('post', 'positionOfBlock');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('positionOfBlock', ChoiceType::class, [
            'label' => $this->__('Position of block'),
            'attr' => [
                'class' => 'input-sm'
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
        $listEntries = $this->listHelper->getEntries('post', 'positionOfAdvertising2');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('positionOfAdvertising2', ChoiceType::class, [
            'label' => $this->__('Position of advertising 2'),
            'attr' => [
                'class' => 'input-sm'
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
        $listEntries = $this->listHelper->getEntries('post', 'positionOfBlock2');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('positionOfBlock2', ChoiceType::class, [
            'label' => $this->__('Position of block 2'),
            'attr' => [
                'class' => 'input-sm'
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
        $listEntries = $this->listHelper->getEntries('post', 'positionOfAdvertising3');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('positionOfAdvertising3', ChoiceType::class, [
            'label' => $this->__('Position of advertising 3'),
            'attr' => [
                'class' => 'input-sm'
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
        $listEntries = $this->listHelper->getEntries('post', 'positionOfBlock3');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('positionOfBlock3', ChoiceType::class, [
            'label' => $this->__('Position of block 3'),
            'attr' => [
                'class' => 'input-sm'
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
        $listEntries = $this->listHelper->getEntries('post', 'similarArticles');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('similarArticles', MultiListType::class, [
            'label' => $this->__('Similar articles'),
            'attr' => [
                'class' => 'input-sm'
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => true,
            'expanded' => false
        ]);
    }

    /**
     * Adds locale fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addLocaleFields(FormBuilderInterface $builder, array $options)
    {
        $builder->add('forWhichLanguage', LocaleType::class, [
            'label' => $this->__('For which language'),
            'attr' => [
                'class' => 'input-sm'
            ],
            'required' => false,
            'choices' => $this->localeApi->getSupportedLocaleNames(),
            'choices_as_values' => true
        ]);
    }

    /**
     * Adds a search field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSearchField(FormBuilderInterface $builder, array $options)
    {
        $builder->add('q', SearchType::class, [
            'label' => $this->__('Search'),
            'attr' => [
                'maxlength' => 255,
                'class' => 'input-sm'
            ],
            'required' => false
        ]);
    }


    /**
     * Adds sorting fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSortingFields(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sort', ChoiceType::class, [
                'label' => $this->__('Sort by'),
                'attr' => [
                    'class' => 'input-sm'
                ],
                'choices' =>             [
                    $this->__('Workflow state') => 'workflowState',
                    $this->__('Title') => 'title',
                    $this->__('Description for google') => 'descriptionForGoogle',
                    $this->__('For which language') => 'forWhichLanguage',
                    $this->__('Image for article') => 'imageForArticle',
                    $this->__('Description of image for article') => 'descriptionOfImageForArticle',
                    $this->__('Summary of post') => 'summaryOfPost',
                    $this->__('Advertising') => 'advertising',
                    $this->__('Position of block') => 'positionOfBlock',
                    $this->__('Advertising 2') => 'advertising2',
                    $this->__('Position of block 2') => 'positionOfBlock2',
                    $this->__('Advertising 3') => 'advertising3',
                    $this->__('Position of block 3') => 'positionOfBlock3',
                    $this->__('Similar articles') => 'similarArticles',
                    $this->__('Creation date') => 'createdDate',
                    $this->__('Creator') => 'createdBy',
                    $this->__('Update date') => 'updatedDate',
                    $this->__('Updater') => 'updatedBy'
                ],
                'choices_as_values' => true,
                'required' => true,
                'expanded' => false
            ])
            ->add('sortdir', ChoiceType::class, [
                'label' => $this->__('Sort direction'),
                'empty_data' => 'asc',
                'attr' => [
                    'class' => 'input-sm'
                ],
                'choices' => [
                    $this->__('Ascending') => 'asc',
                    $this->__('Descending') => 'desc'
                ],
                'choices_as_values' => true,
                'required' => true,
                'expanded' => false
            ])
        ;
    }

    /**
     * Adds a page size field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addAmountField(FormBuilderInterface $builder, array $options)
    {
        $builder->add('num', ChoiceType::class, [
            'label' => $this->__('Page size'),
            'empty_data' => 20,
            'attr' => [
                'class' => 'input-sm text-right'
            ],
            'choices' => [
                $this->__('5') => 5,
                $this->__('10') => 10,
                $this->__('15') => 15,
                $this->__('20') => 20,
                $this->__('30') => 30,
                $this->__('50') => 50,
                $this->__('100') => 100
            ],
            'choices_as_values' => true,
            'required' => false,
            'expanded' => false
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'mubloggingmodule_postquicknav';
    }
}
