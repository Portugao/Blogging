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

namespace MU\BloggingModule\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Bundle\FormExtensionBundle\Form\Type\LocaleType;
use Zikula\CategoriesModule\Form\Type\CategoriesType;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\SettingsModule\Api\ApiInterface\LocaleApiInterface;
use MU\BloggingModule\Entity\Factory\EntityFactory;
use MU\BloggingModule\Form\Type\Field\MultiListType;
use MU\BloggingModule\Form\Type\Field\TranslationType;
use MU\BloggingModule\Form\Type\Field\UploadType;
use MU\BloggingModule\Helper\FeatureActivationHelper;
use MU\BloggingModule\Helper\ListEntriesHelper;
use MU\BloggingModule\Helper\TranslatableHelper;
use MU\BloggingModule\Helper\UploadHelper;
use MU\BloggingModule\Traits\ModerationFormFieldsTrait;
use MU\BloggingModule\Traits\WorkflowFormFieldsTrait;

/**
 * Post editing form type base class.
 */
abstract class AbstractPostType extends AbstractType
{
    use TranslatorTrait;
    use ModerationFormFieldsTrait;
    use WorkflowFormFieldsTrait;

    /**
     * @var EntityFactory
     */
    protected $entityFactory;

    /**
     * @var VariableApiInterface
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
     * @var UploadHelper
     */
    protected $uploadHelper;

    /**
     * @var LocaleApiInterface
     */
    protected $localeApi;

    /**
     * @var FeatureActivationHelper
     */
    protected $featureActivationHelper;

    /**
     * PostType constructor.
     *
     * @param TranslatorInterface $translator     Translator service instance
     * @param EntityFactory $entityFactory EntityFactory service instance
     * @param VariableApiInterface $variableApi VariableApi service instance
     * @param TranslatableHelper $translatableHelper TranslatableHelper service instance
     * @param ListEntriesHelper $listHelper ListEntriesHelper service instance
     * @param UploadHelper $uploadHelper UploadHelper service instance
     * @param LocaleApiInterface $localeApi LocaleApi service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        EntityFactory $entityFactory,
        VariableApiInterface $variableApi,
        TranslatableHelper $translatableHelper,
        ListEntriesHelper $listHelper,
        UploadHelper $uploadHelper,
        LocaleApiInterface $localeApi,
        FeatureActivationHelper $featureActivationHelper
    ) {
        $this->setTranslator($translator);
        $this->entityFactory = $entityFactory;
        $this->variableApi = $variableApi;
        $this->translatableHelper = $translatableHelper;
        $this->listHelper = $listHelper;
        $this->uploadHelper = $uploadHelper;
        $this->localeApi = $localeApi;
        $this->featureActivationHelper = $featureActivationHelper;
    }

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(TranslatorInterface $translator)
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
        $this->addAdditionalNotificationRemarksField($builder, $options);
        $this->addModerationFields($builder, $options);
        $this->addSubmitButtons($builder, $options);
    }

    /**
     * Adds basic entity fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addEntityFields(FormBuilderInterface $builder, array $options = [])
    {
        
        $builder->add('title', TextType::class, [
            'label' => $this->__('Title') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 57,
                'class' => 'bloggertitle',
                'title' => $this->__('Enter the title of the post.')
            ],
            'required' => true,
        ]);
        
        $builder->add('permalink', TextType::class, [
            'label' => $this->__('Permalink') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('If you leave this empty, the url of this item will be build with the title.')
            ],
            'help' => $this->__('If you leave this empty, the url of this item will be build with the title.'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 100,
                'class' => '',
                'title' => $this->__('Enter the permalink of the post.')
            ],
            'required' => true,
        ]);
        
        $builder->add('descriptionForGoogle', TextType::class, [
            'label' => $this->__('Description for google') . ':',
            'help' => $this->__f('Note: this value must have a minimum length of %amount% characters.', ['%amount%' => 150]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 170,
                'class' => 'blogger-description',
                'title' => $this->__('Enter the description for google of the post.')
            ],
            'required' => false,
        ]);
        
        $builder->add('textForSimilar', TextType::class, [
            'label' => $this->__('Text for similar') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the text for similar of the post.')
            ],
            'required' => false,
        ]);
        
        $builder->add('textForRelevant', TextType::class, [
            'label' => $this->__('Text for relevant') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the text for relevant of the post.')
            ],
            'required' => false,
        ]);
        
        $builder->add('descriptionOfImageForArticle', TextType::class, [
            'label' => $this->__('Description of image for article') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Will set into the alt tag of this image.')
            ],
            'help' => $this->__('Will set into the alt tag of this image.'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the description of image for article of the post.')
            ],
            'required' => false,
        ]);
        
        $builder->add('summaryOfPost', TextareaType::class, [
            'label' => $this->__('Summary of post') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 2000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => 'bloggergsummary',
                'title' => $this->__('Enter the summary of post of the post.')
            ],
            'required' => true,
        ]);
        
        $builder->add('content', TextareaType::class, [
            'label' => $this->__('Content') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => 'bloggercontent',
                'title' => $this->__('Enter the content of the post.')
            ],
            'required' => true,
        ]);
        
        $builder->add('content2', TextareaType::class, [
            'label' => $this->__('Content 2') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => 'bloggercontent2',
                'title' => $this->__('Enter the content 2 of the post.')
            ],
            'required' => false,
        ]);
        
        $builder->add('advertising', TextareaType::class, [
            'label' => $this->__('Advertising') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 2000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => 'bloggingadvertising',
                'title' => $this->__('Enter the advertising of the post.')
            ],
            'required' => false,
        ]);
        
        $builder->add('content3', TextareaType::class, [
            'label' => $this->__('Content 3') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => 'bloggercontent3',
                'title' => $this->__('Enter the content 3 of the post.')
            ],
            'required' => false,
        ]);
        
        $builder->add('content4', TextareaType::class, [
            'label' => $this->__('Content 4') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => 'bloggercontent4',
                'title' => $this->__('Enter the content 4 of the post.')
            ],
            'required' => false,
        ]);
        
        $builder->add('advertising2', TextareaType::class, [
            'label' => $this->__('Advertising 2') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 2000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => 'bloggingadvertising',
                'title' => $this->__('Enter the advertising 2 of the post.')
            ],
            'required' => false,
        ]);
        
        $builder->add('content5', TextareaType::class, [
            'label' => $this->__('Content 5') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => 'bloggercontent5',
                'title' => $this->__('Enter the content 5 of the post.')
            ],
            'required' => false,
        ]);
        
        $builder->add('content6', TextareaType::class, [
            'label' => $this->__('Content 6') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => 'bloggercontent6',
                'title' => $this->__('Enter the content 6 of the post.')
            ],
            'required' => false,
        ]);
        
        $builder->add('advertising3', TextareaType::class, [
            'label' => $this->__('Advertising 3') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 2000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => 'bloggingadvertising',
                'title' => $this->__('Enter the advertising 3 of the post.')
            ],
            'required' => false,
        ]);
        $helpText = $this->__('You can input a custom permalink for the post or let this field free to create one automatically.');
        if ('create' != $options['mode']) {
            $helpText = '';
        }
        $builder->add('slug', TextType::class, [
            'label' => $this->__('Permalink') . ':',
            'required' => 'create' != $options['mode'],
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => 'validate-unique',
                'title' => $helpText
            ],
            'help' => $helpText
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
                    $builder->add('translations' . $language, TranslationType::class, [
                        'fields' => $translatableFields,
                        'mandatory_fields' => $mandatoryFields[$language],
                        'values' => isset($options['translations'][$language]) ? $options['translations'][$language] : []
                    ]);
                }
            }
        }
        
        $builder->add('forWhichLanguage', LocaleType::class, [
            'label' => $this->__('For which language') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Choose the for which language of the post.')
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $this->localeApi->getSupportedLocaleNames(),
        ]);
        
        $builder->add('imageForArticle', UploadType::class, [
            'label' => $this->__('Image for article') . ':',
            'attr' => [
                'class' => ' validate-upload',
                'title' => $this->__('Enter the image for article of the post.')
            ],
            'required' => false && $options['mode'] == 'create',
            'entity' => $options['entity'],
            'allowed_extensions' => implode(', ', $this->uploadHelper->getAllowedFileExtensions('post', 'imageForArticle')),
            'allowed_size' => ''
        ]);
        
        $listEntries = $this->listHelper->getEntries('post', 'positionOfAdvertising1');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('positionOfAdvertising1', ChoiceType::class, [
            'label' => $this->__('Position of advertising 1') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the position of advertising 1.')
            ],
            'required' => true,
            'choices' => $choices,
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
            'label' => $this->__('Position of block') . ':',
            'empty_data' => 'none',
            'attr' => [
                'class' => 'bloggerblock',
                'title' => $this->__('Choose the position of block.')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
            'choices' => $choices,
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
            'label' => $this->__('Position of advertising 2') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the position of advertising 2.')
            ],
            'required' => true,
            'choices' => $choices,
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
            'label' => $this->__('Position of block 2') . ':',
            'empty_data' => 'none',
            'attr' => [
                'class' => 'bloggerblock2',
                'title' => $this->__('Choose the position of block 2.')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
            'choices' => $choices,
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
            'label' => $this->__('Position of advertising 3') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the position of advertising 3.')
            ],
            'required' => true,
            'choices' => $choices,
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
            'label' => $this->__('Position of block 3') . ':',
            'empty_data' => 'none',
            'attr' => [
                'class' => 'bloggerblock3',
                'title' => $this->__('Choose the position of block 3.')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
            'choices' => $choices,
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
            'label' => $this->__('Similar articles') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Choose articles with similar issues.')
            ],
            'help' => $this->__('Choose articles with similar issues.'),
            'empty_data' => null,
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the similar articles.')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
            'choices' => $choices,
            'choice_attr' => $choiceAttributes,
            'multiple' => true,
            'expanded' => false
        ]);
        
        $builder->add('startDate', DateTimeType::class, [
            'label' => $this->__('Start date') . ':',
            'attr' => [
                'class' => ' validate-daterange-post',
                'title' => $this->__('Enter the start date of the post.')
            ],
            'required' => false,
            'empty_data' => '',
            'with_seconds' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text'
        ]);
        
        $builder->add('endDate', DateTimeType::class, [
            'label' => $this->__('End date') . ':',
            'attr' => [
                'class' => ' validate-daterange-post',
                'title' => $this->__('Enter the end date of the post.')
            ],
            'required' => false,
            'empty_data' => '',
            'with_seconds' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text'
        ]);
        
        $builder->add('relevantArticles', TextType::class, [
            'label' => $this->__('Relevant articles') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Comma seperated without space.
                Here you can enter the id"s of articles in the same series.')
            ],
            'help' => $this->__('Comma seperated without space.
            Here you can enter the id"s of articles in the same series.'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the relevant articles of the post.')
            ],
            'required' => false,
        ]);
    }

    /**
     * Adds a categories field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addCategoriesField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('categories', CategoriesType::class, [
            'label' => $this->__('Categories') . ':',
            'empty_data' => [],
            'attr' => [
                'class' => 'category-selector'
            ],
            'required' => false,
            'multiple' => true,
            'module' => 'MUBloggingModule',
            'entity' => 'PostEntity',
            'entityCategoryClass' => 'MU\BloggingModule\Entity\PostCategoryEntity',
            'showRegistryLabels' => true
        ]);
    }

    /**
     * Adds submit buttons.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSubmitButtons(FormBuilderInterface $builder, array $options = [])
    {
        foreach ($options['actions'] as $action) {
            $builder->add($action['id'], SubmitType::class, [
                'label' => $action['title'],
                'icon' => ($action['id'] == 'delete' ? 'fa-trash-o' : ''),
                'attr' => [
                    'class' => $action['buttonClass']
                ]
            ]);
            if ($options['mode'] == 'create' && $action['id'] == 'submit') {
                // add additional button to submit item and return to create form
                $builder->add('submitrepeat', SubmitType::class, [
                    'label' => $this->__('Submit and repeat'),
                    'icon' => 'fa-repeat',
                    'attr' => [
                        'class' => $action['buttonClass']
                    ]
                ]);
            }
        }
        $builder->add('reset', ResetType::class, [
            'label' => $this->__('Reset'),
            'icon' => 'fa-refresh',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
        $builder->add('cancel', SubmitType::class, [
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
                'allow_moderation_specific_creator' => false,
                'allow_moderation_specific_creation_date' => false,
                'translations' => [],
            ])
            ->setRequired(['entity', 'mode', 'actions'])
            ->setAllowedTypes('mode', 'string')
            ->setAllowedTypes('is_moderator', 'bool')
            ->setAllowedTypes('is_creator', 'bool')
            ->setAllowedTypes('actions', 'array')
            ->setAllowedTypes('has_moderate_permission', 'bool')
            ->setAllowedTypes('allow_moderation_specific_creator', 'bool')
            ->setAllowedTypes('allow_moderation_specific_creation_date', 'bool')
            ->setAllowedTypes('translations', 'array')
            ->setAllowedValues('mode', ['create', 'edit'])
        ;
    }
}
