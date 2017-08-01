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

namespace MU\BloggingModule\Form\Type;

use MU\BloggingModule\Form\Type\Base\AbstractPostType;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Zikula\Bundle\FormExtensionBundle\Form\Type\LocaleType;
use MU\BloggingModule\Form\Type\Field\MultiListType;
use MU\BloggingModule\Form\Type\Field\TranslationType;
use MU\BloggingModule\Form\Type\Field\UploadType;
use MU\BloggingModule\Helper\FeatureActivationHelper;

/**
 * Post editing form type implementation class.
 */
class PostType extends AbstractPostType
{
    /**
     * Adds basic entity fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addEntityFields(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('title', TextType::class, [
            'label' => $this->__('Title') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 57,
                'class' => ' bloggertitle',
                'title' => $this->__('Enter the title of the post')
            ],
            'required' => true,
        ]);
        
        $builder->add('permalink', TextType::class, [
            'label' => $this->__('Permalink') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 100,
                'class' => '',
                'title' => $this->__('Enter the permalink of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('descriptionForGoogle', TextType::class, [
            'label' => $this->__('Description for google') . ':',
            'help' => $this->__f('Note: this value must have a minimum length of %amount% characters.', ['%amount%' => 155]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 170,
                'class' => ' bloggerdescription',
                'title' => $this->__('Enter the description for google of the post')
            ],
            'required' => true,
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
                'title' => $this->__('Enter the description of image for article of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('summaryOfPost', TextareaType::class, [
            'label' => $this->__('Summary of post') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 2000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => ' bloggergsummary',
                'title' => $this->__('Enter the summary of post of the post')
            ],
            'required' => true,
        ]);
        
        $builder->add('content', TextareaType::class, [
            'label' => $this->__('Content') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => ' bloggercontent',
                'title' => $this->__('Enter the content of the post')
            ],
            'required' => true,
        ]);
        
        $builder->add('content2', TextareaType::class, [
            'label' => $this->__('Content 2') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => ' bloggercontent2',
                'title' => $this->__('Enter the content 2 of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('advertising', TextareaType::class, [
            'label' => $this->__('Advertising') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 2000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => ' bloggingadvertising',
                'title' => $this->__('Enter the advertising of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('content3', TextareaType::class, [
            'label' => $this->__('Content 3') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => ' bloggercontent3',
                'title' => $this->__('Enter the content 3 of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('content4', TextareaType::class, [
            'label' => $this->__('Content 4') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => ' bloggercontent4',
                'title' => $this->__('Enter the content 4 of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('advertising2', TextareaType::class, [
            'label' => $this->__('Advertising 2') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 2000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => ' bloggingadvertising',
                'title' => $this->__('Enter the advertising 2 of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('content5', TextareaType::class, [
            'label' => $this->__('Content 5') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => ' bloggercontent5',
                'title' => $this->__('Enter the content 5 of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('content6', TextareaType::class, [
            'label' => $this->__('Content 6') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 20000]),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => ' bloggercontent6',
                'title' => $this->__('Enter the content 6 of the post')
            ],
            'required' => false,
        ]);
        
        $builder->add('advertising3', TextareaType::class, [
            'label' => $this->__('Advertising 3') . ':',
            'help' => $this->__f('Note: this value must not exceed %amount% characters.', ['%amount%' => 2000]),
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
                'class' => ' validate-nospace',
                'title' => $this->__('Choose the for which language of the post')
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $this->localeApi->getSupportedLocaleNames(),
            'choices_as_values' => true
        ]);
        
        $builder->add('imageForArticle', UploadType::class, [
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
        
        $listEntries = $this->listHelper->getEntries('post', 'positionOfAdvertising1');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('positionOfAdvertising1', ChoiceType::class, [
            'label' => $this->__('Position of advertising 1') . ':',
            'empty_data' => '1',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the position of advertising 1')
            ],
            'required' => true,
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
            'label' => $this->__('Position of block') . ':',
            'empty_data' => 'none',
            'attr' => [
                'class' => ' bloggerblock',
                'title' => $this->__('Choose the position of block')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
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
            'label' => $this->__('Position of advertising 2') . ':',
            'empty_data' => 'content3Left',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the position of advertising 2')
            ],
            'required' => true,
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
            'label' => $this->__('Position of block 2') . ':',
            'empty_data' => 'none',
            'attr' => [
                'class' => ' bloggerblock2',
                'title' => $this->__('Choose the position of block 2')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
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
            'label' => $this->__('Position of advertising 3') . ':',
            'empty_data' => 'content5Left',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the position of advertising 3')
            ],
            'required' => true,
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
            'label' => $this->__('Position of block 3') . ':',
            'empty_data' => 'none',
            'attr' => [
                'class' => ' bloggerblock3',
                'title' => $this->__('Choose the position of block 3')
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
        //$postRepository = $this->entityFactory->getRepository('Post');
        //$listEntries = $postRepository->findAll();
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['id'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('similarArticles', MultiListType::class, [
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
        
        $builder->add('startDate', DateTimeType::class, [
            'label' => $this->__('Start date') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => ' validate-daterange-post',
                'title' => $this->__('Enter the start date of the post')
            ],
            'required' => false,
            'empty_data' => null,
            'with_seconds' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text'
        ]);
        
        $builder->add('endDate', DateTimeType::class, [
            'label' => $this->__('End date') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => ' validate-daterange-post',
                'title' => $this->__('Enter the end date of the post')
            ],
            'required' => false,
            'empty_data' => null,
            'with_seconds' => true,
            'date_widget' => 'single_text',
            'time_widget' => 'single_text'
        ]);
        
        $builder->add('relevantArticles', TextType::class, [
            'label' => $this->__('Relevant articles') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the relevant articles of the post')
            ],
            'required' => false,
        ]);
    }
}
