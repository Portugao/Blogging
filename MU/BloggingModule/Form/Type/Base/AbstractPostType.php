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

namespace MU\BloggingModule\Form\Type\Base;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\ExtensionsModule\Api\VariableApi;
use MU\BloggingModule\Entity\Factory\BloggingFactory;
use MU\BloggingModule\Helper\FeatureActivationHelper;
use MU\BloggingModule\Helper\ListEntriesHelper;
use MU\BloggingModule\Helper\TranslatableHelper;

/**
 * Post editing form type base class.
 */
abstract class AbstractPostType extends AbstractType
{
    use TranslatorTrait;

    /**
     * @var BloggingFactory
     */
    protected $entityFactory;

    /**
     * @var VariableApi
     */
    protected $variableApi;

    /**
     * @var TranslatableHelper
     */
    protected $translatableHelper;

    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    /**
     * @var FeatureActivationHelper
     */
    protected $featureActivationHelper;

    /**
     * PostType constructor.
     *
     * @param TranslatorInterface $translator     Translator service instance
     * @param BloggingFactory        $entityFactory Entity factory service instance
     * @param VariableApi         $variableApi VariableApi service instance
     * @param TranslatableHelper  $translatableHelper TranslatableHelper service instance
     * @param ListEntriesHelper   $listHelper     ListEntriesHelper service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        BloggingFactory $entityFactory,
        VariableApi $variableApi,
        TranslatableHelper $translatableHelper,
        ListEntriesHelper $listHelper,
        FeatureActivationHelper $featureActivationHelper
    ) {
        $this->setTranslator($translator);
        $this->entityFactory = $entityFactory;
        $this->variableApi = $variableApi;
        $this->translatableHelper = $translatableHelper;
        $this->listHelper = $listHelper;
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
        $this->addEntityFields($builder, $options);
        if ($this->featureActivationHelper->isEnabled(FeatureActivationHelper::CATEGORIES, 'post')) {
            $this->addCategoriesField($builder, $options);
        }
        $this->addIncomingRelationshipFields($builder, $options);
        $this->addAdditionalNotificationRemarksField($builder, $options);
        $this->addModerationFields($builder, $options);
        $this->addReturnControlField($builder, $options);
        $this->addSubmitButtons($builder, $options);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $entity = $event->getData();
            foreach (['imageForArticle'] as $uploadFieldName) {
                $entity[$uploadFieldName] = [
                    $uploadFieldName => $entity[$uploadFieldName] instanceof File ? $entity[$uploadFieldName]->getPathname() : null
                ];
            }
        });
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $entity = $event->getData();
            foreach (['imageForArticle'] as $uploadFieldName) {
                if (is_array($entity[$uploadFieldName])) {
                    $entity[$uploadFieldName] = $entity[$uploadFieldName][$uploadFieldName];
                }
            }
        });
    }

    /**
     * Adds basic entity fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addEntityFields(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('title', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Title') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 57,
                'class' => ' bloggertitle',
                'title' => $this->__('Enter the title of the post')
            ],
            'required' => true,
        ]);
        
        $builder->add('permalink', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Permalink') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 100,
                'class' => '',
                'title' => $this->__('Enter the permalink of the post')
            ],
            'required' => true,
        ]);
        
        $builder->add('descriptionForGoogle', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Description for google') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 170,
                'class' => ' bloggerdescription',
                'title' => $this->__('Enter the description for google of the post')
            ],
            'required' => true,
        ]);
        
        $builder->add('summaryOfPost', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'label' => $this->__('Summary of post') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => ' bloggergsummary',
                'title' => $this->__('Enter the summary of post of the post')
            ],
            'required' => true,
        ]);
        
        $builder->add('content', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'label' => $this->__('Content') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => ' bloggercontent',
                'title' => $this->__('Enter the content of the post')
            ],
            'required' => true,
        ]);
        
        $builder->add('advertising', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'label' => $this->__('Advertising') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => ' bloggingadvertising',
                'title' => $this->__('Enter the advertising of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('content2', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'label' => $this->__('Content 2') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => ' bloggercontent2',
                'title' => $this->__('Enter the content 2 of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('advertising2', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'label' => $this->__('Advertising 2') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => ' bloggingadvertising',
                'title' => $this->__('Enter the advertising 2 of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('content3', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'label' => $this->__('Content 3') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => ' bloggercontent3',
                'title' => $this->__('Enter the content 3 of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('advertising3', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'label' => $this->__('Advertising 3') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => ' bloggingadvertising',
                'title' => $this->__('Enter the advertising 3 of the post')
            ],
            'required' => false,
        ]);
        
        if ($this->variableApi->getSystemVar('multilingual') && $this->featureActivationHelper->isEnabled(FeatureActivationHelper::TRANSLATIONS, 'post')) {
            $supportedLanguages = $this->translatableHelper->getSupportedLanguages('post');
            if (is_array($supportedLanguages) && count($supportedLanguages) > 1) {
                $currentLanguage = $this->translatableHelper->getCurrentLanguage();
                $translatableFields = $this->translatableHelper->getTranslatableFields('post');
                $mandatoryFields = $this->translatableHelper->getMandatoryFields('post');
                foreach ($supportedLanguages as $language) {
                    if ($language == $currentLanguage) {
                        continue;
                    }
                    $builder->add('translations' . $language, 'MU\BloggingModule\Form\Type\Field\TranslationType', [
                        'fields' => $translatableFields,
                        'mandatory_fields' => $mandatoryFields[$language],
                        'values' => isset($options['translations'][$language]) ? $options['translations'][$language] : []
                    ]);
                }
            }
        }
        
        $builder->add('imageForArticle', 'MU\BloggingModule\Form\Type\Field\UploadType', [
            'label' => $this->__('Image for article') . ':',
            'attr' => [
                'class' => ' validate-upload',
                'title' => $this->__('Enter the image for article of the post')
            ],
            'required' => false && $options['mode'] == 'create',
            'entity' => $options['entity'],
            'allowed_extensions' => 'gif, jpeg, jpg, png',
            'allowed_size' => ''
        ]);
        
        $listEntries = $this->listHelper->getEntries('post', 'block');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('block', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
            'label' => $this->__('Block') . ':',
            'empty_data' => 'none',
            'attr' => [
                'class' => ' bloggerblock',
                'title' => $this->__('Choose the block')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
        
        $listEntries = $this->listHelper->getEntries('post', 'block2');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('block2', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
            'label' => $this->__('Block 2') . ':',
            'empty_data' => 'none',
            'attr' => [
                'class' => ' bloggerblock2',
                'title' => $this->__('Choose the block 2')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
        
        $listEntries = $this->listHelper->getEntries('post', 'block3');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('block3', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
            'label' => $this->__('Block 3') . ':',
            'empty_data' => 'none',
            'attr' => [
                'class' => ' bloggerblock3',
                'title' => $this->__('Choose the block 3')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
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
        $builder->add('similarArticles', 'MU\BloggingModule\Form\Type\Field\MultiListType', [
            'label' => $this->__('Similar articles') . ':',
            'empty_data' => 'none',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the similar articles')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => true,
            'expanded' => false
        ]);
        
        $builder->add('startDate', 'Symfony\Component\Form\Extension\Core\Type\DateTimeType', [
            'label' => $this->__('Start date') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => ' validate-daterange-post',
                'title' => $this->__('Enter the start date of the post')
            ],
            'required' => false,
            'empty_data' => date('Y-m-d H:i:s'),
            'with_seconds' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text'
        ]);
        
        $builder->add('endDate', 'Symfony\Component\Form\Extension\Core\Type\DateTimeType', [
            'label' => $this->__('End date') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => ' validate-daterange-post',
                'title' => $this->__('Enter the end date of the post')
            ],
            'required' => false,
            'empty_data' => date('Y-m-d H:i:s'),
            'with_seconds' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text'
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
        $builder->add('categories', 'Zikula\CategoriesModule\Form\Type\CategoriesType', [
            'label' => $this->__('Categories') . ':',
            'empty_data' => [],
            'attr' => [
                'class' => 'category-selector'
            ],
            'required' => false,
            'multiple' => true,
            'module' => 'MUBloggingModule',
            'entity' => 'PostEntity',
            'entityCategoryClass' => 'MU\BloggingModule\Entity\PostCategoryEntity'
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
        $queryBuilder = function(EntityRepository $er) {
            // select without joins
            return $er->getListQueryBuilder('', '', false);
        };
        $builder->add('post', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
            'class' => 'MUBloggingModule:PostEntity',
            'choice_label' => 'getTitleFromDisplayPattern',
            'multiple' => false,
            'expanded' => false,
            'query_builder' => $queryBuilder,
            'placeholder' => $this->__('Please choose an option'),
            'required' => false,
            'label' => $this->__('Post'),
            'attr' => [
                'title' => $this->__('Choose the post')
            ]
        ]);
    }

    /**
     * Adds a field for additional notification remarks.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addAdditionalNotificationRemarksField(FormBuilderInterface $builder, array $options)
    {
        $helpText = '';
        if ($options['is_moderator']) {
            $helpText = $this->__('These remarks (like a reason for deny) are not stored, but added to any notification emails send to the creator.');
        } elseif ($options['is_creator']) {
            $helpText = $this->__('These remarks (like questions about conformance) are not stored, but added to any notification emails send to our moderators.');
        }
    
        $builder->add('additionalNotificationRemarks', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'mapped' => false,
            'label' => $this->__('Additional remarks'),
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $helpText
            ],
            'attr' => [
                'title' => $options['mode'] == 'create' ? $this->__('Enter any additions about your content') : $this->__('Enter any additions about your changes')
            ],
            'required' => false,
            'help' => $helpText
        ]);
    }

    /**
     * Adds special fields for moderators.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addModerationFields(FormBuilderInterface $builder, array $options)
    {
        if (!$options['has_moderate_permission']) {
            return;
        }
    
        $builder->add('moderationSpecificCreator', 'MU\BloggingModule\Form\Type\Field\UserType', [
            'mapped' => false,
            'label' => $this->__('Creator') . ':',
            'attr' => [
                'maxlength' => 11,
                'class' => ' validate-digits',
                'title' => $this->__('Here you can choose a user which will be set as creator')
            ],
            'empty_data' => 0,
            'required' => false,
            'help' => $this->__('Here you can choose a user which will be set as creator')
        ]);
        $builder->add('moderationSpecificCreationDate', 'Symfony\Component\Form\Extension\Core\Type\DateTimeType', [
            'mapped' => false,
            'label' => $this->__('Creation date') . ':',
            'attr' => [
                'class' => '',
                'title' => $this->__('Here you can choose a custom creation date')
            ],
            'empty_data' => '',
            'required' => false,
            'with_seconds' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'help' => $this->__('Here you can choose a custom creation date')
        ]);
    }

    /**
     * Adds the return control field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addReturnControlField(FormBuilderInterface $builder, array $options)
    {
        if ($options['mode'] != 'create') {
            return;
        }
        $builder->add('repeatCreation', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
            'mapped' => false,
            'label' => $this->__('Create another item after save'),
            'required' => false
        ]);
    }

    /**
     * Adds submit buttons.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSubmitButtons(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['actions'] as $action) {
            $builder->add($action['id'], 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'label' => $this->__(/** @Ignore */$action['title']),
                'icon' => ($action['id'] == 'delete' ? 'fa-trash-o' : ''),
                'attr' => [
                    'class' => $action['buttonClass'],
                    'title' => $this->__(/** @Ignore */$action['description'])
                ]
            ]);
        }
        $builder->add('reset', 'Symfony\Component\Form\Extension\Core\Type\ResetType', [
            'label' => $this->__('Reset'),
            'icon' => 'fa-refresh',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
        $builder->add('cancel', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
            'label' => $this->__('Cancel'),
            'icon' => 'fa-times',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'mubloggingmodule_post';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                // define class for underlying data (required for embedding forms)
                'data_class' => 'MU\BloggingModule\Entity\PostEntity',
                'empty_data' => function (FormInterface $form) {
                    return $this->entityFactory->createPost();
                },
                'error_mapping' => [
                    'isSimilarArticlesValueAllowed' => 'similarArticles',
                    'imageForArticle' => 'imageForArticle.imageForArticle',
                    'isStartDateBeforeEndDate' => 'startDate',
                ],
                'mode' => 'create',
                'is_moderator' => false,
                'is_creator' => false,
                'actions' => [],
                'has_moderate_permission' => false,
                'translations' => [],
                'filter_by_ownership' => true,
                'inline_usage' => false
            ])
            ->setRequired(['entity', 'mode', 'actions'])
            ->setAllowedTypes([
                'mode' => 'string',
                'is_moderator' => 'bool',
                'is_creator' => 'bool',
                'actions' => 'array',
                'has_moderate_permission' => 'bool',
                'translations' => 'array',
                'filter_by_ownership' => 'bool',
                'inline_usage' => 'bool'
            ])
            ->setAllowedValues([
                'mode' => ['create', 'edit']
            ])
        ;
    }
}
