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

namespace MU\BloggingModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Zikula\Core\Doctrine\EntityAccess;
use MU\BloggingModule\Traits\StandardFieldsTrait;
use MU\BloggingModule\Validator\Constraints as BloggingAssert;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for post entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 *
 * @abstract
 */
abstract class AbstractPostEntity extends EntityAccess implements Translatable
{
    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'post';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     * @BloggingAssert\ListEntry(entityName="post", propertyName="workflowState", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(length=57)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="57")
     * @var string $title
     */
    protected $title = '';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(length=100)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="100")
     * @var string $permalink
     */
    protected $permalink = '';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="description", length=170)
     * @Assert\NotBlank()
     * @Assert\Length(min="155", max="170")
     * @var string $descriptionForGoogle
     */
    protected $descriptionForGoogle = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/\s/", match=false, message="This value must not contain space chars.")
     * @Assert\Length(min="0", max="255")
     * @Assert\Locale()
     * @var string $forWhichLanguage
     */
    protected $forWhichLanguage = '';
    
    /**
     * Image for article meta data array.
     *
     * @ORM\Column(type="array")
     * @Assert\Type(type="array")
     * @var array $imageForArticleMeta
     */
    protected $imageForArticleMeta = [];
    
    /**
     * @ORM\Column(length=255, nullable=true)
     * @Assert\Length(min="0", max="255")
     * @Assert\File(
     *    mimeTypes = {"image/*"}
     * )
     * @Assert\Image(
     * )
     * @var string $imageForArticle
     */
    protected $imageForArticle = null;
    
    /**
     * Full image for article path as url.
     *
     * @Assert\Type(type="string")
     * @var string $imageForArticleUrl
     */
    protected $imageForArticleUrl = '';
    
    /**
     * Will set into the alt tag of this image.
     * @Gedmo\Translatable
     * @ORM\Column(length=255, nullable=true)
     * @Assert\Length(min="0", max="255")
     * @var string $descriptionOfImageForArticle
     */
    protected $descriptionOfImageForArticle = '';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=2000)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="2000")
     * @var text $summaryOfPost
     */
    protected $summaryOfPost = '';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=20000)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="20000")
     * @var text $content
     */
    protected $content = '';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=20000, nullable=true)
     * @Assert\Length(min="0", max="20000")
     * @var text $content2
     */
    protected $content2 = '';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=2000, nullable=true)
     * @Assert\Length(min="0", max="2000")
     * @var text $advertising
     */
    protected $advertising = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @BloggingAssert\ListEntry(entityName="post", propertyName="positionOfAdvertising1", multiple=false)
     * @var string $positionOfAdvertising1
     */
    protected $positionOfAdvertising1 = '1';
    
    /**
     * @ORM\Column(length=255, nullable=true)
     * @BloggingAssert\ListEntry(entityName="post", propertyName="positionOfBlock", multiple=false)
     * @var string $positionOfBlock
     */
    protected $positionOfBlock = 'none';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=20000, nullable=true)
     * @Assert\Length(min="0", max="20000")
     * @var text $content3
     */
    protected $content3 = '';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=20000, nullable=true)
     * @Assert\Length(min="0", max="20000")
     * @var text $content4
     */
    protected $content4 = '';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=2000, nullable=true)
     * @Assert\Length(min="0", max="2000")
     * @var text $advertising2
     */
    protected $advertising2 = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @BloggingAssert\ListEntry(entityName="post", propertyName="positionOfAdvertising2", multiple=false)
     * @var string $positionOfAdvertising2
     */
    protected $positionOfAdvertising2 = 'content3Left';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @BloggingAssert\ListEntry(entityName="post", propertyName="positionOfBlock2", multiple=false)
     * @var string $positionOfBlock2
     */
    protected $positionOfBlock2 = 'none';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=20000, nullable=true)
     * @Assert\Length(min="0", max="20000")
     * @var text $content5
     */
    protected $content5 = '';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=20000, nullable=true)
     * @Assert\Length(min="0", max="20000")
     * @var text $content6
     */
    protected $content6 = '';
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=2000, nullable=true)
     * @Assert\Length(min="0", max="2000")
     * @var text $advertising3
     */
    protected $advertising3 = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @BloggingAssert\ListEntry(entityName="post", propertyName="positionOfAdvertising3", multiple=false)
     * @var string $positionOfAdvertising3
     */
    protected $positionOfAdvertising3 = 'content5Left';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @BloggingAssert\ListEntry(entityName="post", propertyName="positionOfBlock3", multiple=false)
     * @var string $positionOfBlock3
     */
    protected $positionOfBlock3 = 'none';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @BloggingAssert\ListEntry(entityName="post", propertyName="similarArticles", multiple=true)
     * @var string $similarArticles
     */
    protected $similarArticles = 'none';
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     * @var DateTime $startDate
     */
    protected $startDate;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     * @Assert\Expression("!value or value > this.getStartDate()")
     * @var DateTime $endDate
     */
    protected $endDate;
    
    /**
     * @ORM\Column(type="integer")
     * @var integer $parentid
     */
    protected $parentid = 0;
    
    
    /**
     * @Gedmo\Translatable
     * @Gedmo\Slug(fields={"permalink"}, updatable=true, unique=true, separator="-", style="lower")
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Length(min="1", max="255")
     * @var string $slug
     */
    protected $slug;
    
    /**
     * Used locale to override Translation listener's locale.
     * this is not a mapped field of entity metadata, just a simple property.
     *
     * @Assert\Locale()
     * @Gedmo\Locale
     * @var string $locale
     */
    protected $locale;
    
    /**
     * @ORM\OneToMany(targetEntity="\MU\BloggingModule\Entity\PostCategoryEntity", 
     *                mappedBy="entity", cascade={"all"}, 
     *                orphanRemoval=true)
     * @var \MU\BloggingModule\Entity\PostCategoryEntity
     */
    protected $categories = null;
    
    /**
     * Bidirectional - Many posts [posts] are linked by one post [post] (OWNING SIDE).
     *
     * @ORM\ManyToOne(targetEntity="MU\BloggingModule\Entity\PostEntity", inversedBy="posts")
     * @ORM\JoinTable(name="mu_blogging_post",
     *      joinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id" )},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parentid", referencedColumnName="id" )}
     * )
     * @Assert\Type(type="MU\BloggingModule\Entity\PostEntity")
     * @var \MU\BloggingModule\Entity\PostEntity $post
     */
    protected $post;
    
    /**
     * Bidirectional - One post [post] has many posts [posts] (INVERSE SIDE).
     *
     * @ORM\OneToMany(targetEntity="MU\BloggingModule\Entity\PostEntity", mappedBy="post")
     * @ORM\JoinTable(name="mu_blogging_postposts",
     *      joinColumns={@ORM\JoinColumn(name="parentid", referencedColumnName="id" )},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id" )}
     * )
     * @var \MU\BloggingModule\Entity\PostEntity[] $posts
     */
    protected $posts = null;
    
    
    /**
     * PostEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }
    
    /**
     * Returns the _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }
    
    /**
     * Sets the _object type.
     *
     * @param string $_objectType
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        if ($this->_objectType != $_objectType) {
            $this->_objectType = $_objectType;
        }
    }
    
    
    /**
     * Returns the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        if (intval($this->id) !== intval($id)) {
            $this->id = intval($id);
        }
    }
    
    /**
     * Returns the workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }
    
    /**
     * Sets the workflow state.
     *
     * @param string $workflowState
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        if ($this->workflowState !== $workflowState) {
            $this->workflowState = isset($workflowState) ? $workflowState : '';
        }
    }
    
    /**
     * Returns the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets the title.
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title)
    {
        if ($this->title !== $title) {
            $this->title = isset($title) ? $title : '';
        }
    }
    
    /**
     * Returns the permalink.
     *
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }
    
    /**
     * Sets the permalink.
     *
     * @param string $permalink
     *
     * @return void
     */
    public function setPermalink($permalink)
    {
        if ($this->permalink !== $permalink) {
            $this->permalink = isset($permalink) ? $permalink : '';
        }
    }
    
    /**
     * Returns the description for google.
     *
     * @return string
     */
    public function getDescriptionForGoogle()
    {
        return $this->descriptionForGoogle;
    }
    
    /**
     * Sets the description for google.
     *
     * @param string $descriptionForGoogle
     *
     * @return void
     */
    public function setDescriptionForGoogle($descriptionForGoogle)
    {
        if ($this->descriptionForGoogle !== $descriptionForGoogle) {
            $this->descriptionForGoogle = isset($descriptionForGoogle) ? $descriptionForGoogle : '';
        }
    }
    
    /**
     * Returns the for which language.
     *
     * @return string
     */
    public function getForWhichLanguage()
    {
        return $this->forWhichLanguage;
    }
    
    /**
     * Sets the for which language.
     *
     * @param string $forWhichLanguage
     *
     * @return void
     */
    public function setForWhichLanguage($forWhichLanguage)
    {
        if ($this->forWhichLanguage !== $forWhichLanguage) {
            $this->forWhichLanguage = isset($forWhichLanguage) ? $forWhichLanguage : '';
        }
    }
    
    /**
     * Returns the image for article.
     *
     * @return string
     */
    public function getImageForArticle()
    {
        return $this->imageForArticle;
    }
    
    /**
     * Sets the image for article.
     *
     * @param string $imageForArticle
     *
     * @return void
     */
    public function setImageForArticle($imageForArticle)
    {
        if ($this->imageForArticle !== $imageForArticle) {
            $this->imageForArticle = $imageForArticle;
        }
    }
    
    /**
     * Returns the image for article url.
     *
     * @return string
     */
    public function getImageForArticleUrl()
    {
        return $this->imageForArticleUrl;
    }
    
    /**
     * Sets the image for article url.
     *
     * @param string $imageForArticleUrl
     *
     * @return void
     */
    public function setImageForArticleUrl($imageForArticleUrl)
    {
        if ($this->imageForArticleUrl !== $imageForArticleUrl) {
            $this->imageForArticleUrl = $imageForArticleUrl;
        }
    }
    
    /**
     * Returns the image for article meta.
     *
     * @return array
     */
    public function getImageForArticleMeta()
    {
        return $this->imageForArticleMeta;
    }
    
    /**
     * Sets the image for article meta.
     *
     * @param array $imageForArticleMeta
     *
     * @return void
     */
    public function setImageForArticleMeta($imageForArticleMeta = [])
    {
        if ($this->imageForArticleMeta !== $imageForArticleMeta) {
            $this->imageForArticleMeta = $imageForArticleMeta;
        }
    }
    
    /**
     * Returns the description of image for article.
     *
     * @return string
     */
    public function getDescriptionOfImageForArticle()
    {
        return $this->descriptionOfImageForArticle;
    }
    
    /**
     * Sets the description of image for article.
     *
     * @param string $descriptionOfImageForArticle
     *
     * @return void
     */
    public function setDescriptionOfImageForArticle($descriptionOfImageForArticle)
    {
        if ($this->descriptionOfImageForArticle !== $descriptionOfImageForArticle) {
            $this->descriptionOfImageForArticle = $descriptionOfImageForArticle;
        }
    }
    
    /**
     * Returns the summary of post.
     *
     * @return text
     */
    public function getSummaryOfPost()
    {
        return $this->summaryOfPost;
    }
    
    /**
     * Sets the summary of post.
     *
     * @param text $summaryOfPost
     *
     * @return void
     */
    public function setSummaryOfPost($summaryOfPost)
    {
        if ($this->summaryOfPost !== $summaryOfPost) {
            $this->summaryOfPost = isset($summaryOfPost) ? $summaryOfPost : '';
        }
    }
    
    /**
     * Returns the content.
     *
     * @return text
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Sets the content.
     *
     * @param text $content
     *
     * @return void
     */
    public function setContent($content)
    {
        if ($this->content !== $content) {
            $this->content = isset($content) ? $content : '';
        }
    }
    
    /**
     * Returns the content 2.
     *
     * @return text
     */
    public function getContent2()
    {
        return $this->content2;
    }
    
    /**
     * Sets the content 2.
     *
     * @param text $content2
     *
     * @return void
     */
    public function setContent2($content2)
    {
        if ($this->content2 !== $content2) {
            $this->content2 = $content2;
        }
    }
    
    /**
     * Returns the advertising.
     *
     * @return text
     */
    public function getAdvertising()
    {
        return $this->advertising;
    }
    
    /**
     * Sets the advertising.
     *
     * @param text $advertising
     *
     * @return void
     */
    public function setAdvertising($advertising)
    {
        if ($this->advertising !== $advertising) {
            $this->advertising = $advertising;
        }
    }
    
    /**
     * Returns the position of advertising 1.
     *
     * @return string
     */
    public function getPositionOfAdvertising1()
    {
        return $this->positionOfAdvertising1;
    }
    
    /**
     * Sets the position of advertising 1.
     *
     * @param string $positionOfAdvertising1
     *
     * @return void
     */
    public function setPositionOfAdvertising1($positionOfAdvertising1)
    {
        if ($this->positionOfAdvertising1 !== $positionOfAdvertising1) {
            $this->positionOfAdvertising1 = isset($positionOfAdvertising1) ? $positionOfAdvertising1 : '';
        }
    }
    
    /**
     * Returns the position of block.
     *
     * @return string
     */
    public function getPositionOfBlock()
    {
        return $this->positionOfBlock;
    }
    
    /**
     * Sets the position of block.
     *
     * @param string $positionOfBlock
     *
     * @return void
     */
    public function setPositionOfBlock($positionOfBlock)
    {
        if ($this->positionOfBlock !== $positionOfBlock) {
            $this->positionOfBlock = $positionOfBlock;
        }
    }
    
    /**
     * Returns the content 3.
     *
     * @return text
     */
    public function getContent3()
    {
        return $this->content3;
    }
    
    /**
     * Sets the content 3.
     *
     * @param text $content3
     *
     * @return void
     */
    public function setContent3($content3)
    {
        if ($this->content3 !== $content3) {
            $this->content3 = $content3;
        }
    }
    
    /**
     * Returns the content 4.
     *
     * @return text
     */
    public function getContent4()
    {
        return $this->content4;
    }
    
    /**
     * Sets the content 4.
     *
     * @param text $content4
     *
     * @return void
     */
    public function setContent4($content4)
    {
        if ($this->content4 !== $content4) {
            $this->content4 = $content4;
        }
    }
    
    /**
     * Returns the advertising 2.
     *
     * @return text
     */
    public function getAdvertising2()
    {
        return $this->advertising2;
    }
    
    /**
     * Sets the advertising 2.
     *
     * @param text $advertising2
     *
     * @return void
     */
    public function setAdvertising2($advertising2)
    {
        if ($this->advertising2 !== $advertising2) {
            $this->advertising2 = $advertising2;
        }
    }
    
    /**
     * Returns the position of advertising 2.
     *
     * @return string
     */
    public function getPositionOfAdvertising2()
    {
        return $this->positionOfAdvertising2;
    }
    
    /**
     * Sets the position of advertising 2.
     *
     * @param string $positionOfAdvertising2
     *
     * @return void
     */
    public function setPositionOfAdvertising2($positionOfAdvertising2)
    {
        if ($this->positionOfAdvertising2 !== $positionOfAdvertising2) {
            $this->positionOfAdvertising2 = isset($positionOfAdvertising2) ? $positionOfAdvertising2 : '';
        }
    }
    
    /**
     * Returns the position of block 2.
     *
     * @return string
     */
    public function getPositionOfBlock2()
    {
        return $this->positionOfBlock2;
    }
    
    /**
     * Sets the position of block 2.
     *
     * @param string $positionOfBlock2
     *
     * @return void
     */
    public function setPositionOfBlock2($positionOfBlock2)
    {
        if ($this->positionOfBlock2 !== $positionOfBlock2) {
            $this->positionOfBlock2 = isset($positionOfBlock2) ? $positionOfBlock2 : '';
        }
    }
    
    /**
     * Returns the content 5.
     *
     * @return text
     */
    public function getContent5()
    {
        return $this->content5;
    }
    
    /**
     * Sets the content 5.
     *
     * @param text $content5
     *
     * @return void
     */
    public function setContent5($content5)
    {
        if ($this->content5 !== $content5) {
            $this->content5 = $content5;
        }
    }
    
    /**
     * Returns the content 6.
     *
     * @return text
     */
    public function getContent6()
    {
        return $this->content6;
    }
    
    /**
     * Sets the content 6.
     *
     * @param text $content6
     *
     * @return void
     */
    public function setContent6($content6)
    {
        if ($this->content6 !== $content6) {
            $this->content6 = $content6;
        }
    }
    
    /**
     * Returns the advertising 3.
     *
     * @return text
     */
    public function getAdvertising3()
    {
        return $this->advertising3;
    }
    
    /**
     * Sets the advertising 3.
     *
     * @param text $advertising3
     *
     * @return void
     */
    public function setAdvertising3($advertising3)
    {
        if ($this->advertising3 !== $advertising3) {
            $this->advertising3 = $advertising3;
        }
    }
    
    /**
     * Returns the position of advertising 3.
     *
     * @return string
     */
    public function getPositionOfAdvertising3()
    {
        return $this->positionOfAdvertising3;
    }
    
    /**
     * Sets the position of advertising 3.
     *
     * @param string $positionOfAdvertising3
     *
     * @return void
     */
    public function setPositionOfAdvertising3($positionOfAdvertising3)
    {
        if ($this->positionOfAdvertising3 !== $positionOfAdvertising3) {
            $this->positionOfAdvertising3 = isset($positionOfAdvertising3) ? $positionOfAdvertising3 : '';
        }
    }
    
    /**
     * Returns the position of block 3.
     *
     * @return string
     */
    public function getPositionOfBlock3()
    {
        return $this->positionOfBlock3;
    }
    
    /**
     * Sets the position of block 3.
     *
     * @param string $positionOfBlock3
     *
     * @return void
     */
    public function setPositionOfBlock3($positionOfBlock3)
    {
        if ($this->positionOfBlock3 !== $positionOfBlock3) {
            $this->positionOfBlock3 = isset($positionOfBlock3) ? $positionOfBlock3 : '';
        }
    }
    
    /**
     * Returns the similar articles.
     *
     * @return string
     */
    public function getSimilarArticles()
    {
        return $this->similarArticles;
    }
    
    /**
     * Sets the similar articles.
     *
     * @param string $similarArticles
     *
     * @return void
     */
    public function setSimilarArticles($similarArticles)
    {
        if ($this->similarArticles !== $similarArticles) {
            $this->similarArticles = isset($similarArticles) ? $similarArticles : '';
        }
    }
    
    /**
     * Returns the start date.
     *
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }
    
    /**
     * Sets the start date.
     *
     * @param DateTime $startDate
     *
     * @return void
     */
    public function setStartDate($startDate)
    {
        if ($this->startDate !== $startDate) {
            if (is_object($startDate) && $startDate instanceOf \DateTime) {
                $this->startDate = $startDate;
            } elseif (null === $startDate || empty($startDate)) {
                $this->startDate = null;
            } else {
                $this->startDate = new \DateTime($startDate);
            }
        }
    }
    
    /**
     * Returns the end date.
     *
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
    
    /**
     * Sets the end date.
     *
     * @param DateTime $endDate
     *
     * @return void
     */
    public function setEndDate($endDate)
    {
        if ($this->endDate !== $endDate) {
            if (is_object($endDate) && $endDate instanceOf \DateTime) {
                $this->endDate = $endDate;
            } elseif (null === $endDate || empty($endDate)) {
                $this->endDate = null;
            } else {
                $this->endDate = new \DateTime($endDate);
            }
        }
    }
    
    /**
     * Returns the parentid.
     *
     * @return integer
     */
    public function getParentid()
    {
        return $this->parentid;
    }
    
    /**
     * Sets the parentid.
     *
     * @param integer $parentid
     *
     * @return void
     */
    public function setParentid($parentid)
    {
        if (intval($this->parentid) !== intval($parentid)) {
            $this->parentid = intval($parentid);
        }
    }
    
    /**
     * Returns the slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * Sets the slug.
     *
     * @param string $slug
     *
     * @return void
     */
    public function setSlug($slug)
    {
        if ($this->slug != $slug) {
            $this->slug = $slug;
        }
    }
    
    /**
     * Returns the locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
    
    /**
     * Sets the locale.
     *
     * @param string $locale
     *
     * @return void
     */
    public function setLocale($locale)
    {
        if ($this->locale != $locale) {
            $this->locale = $locale;
        }
    }
    
    /**
     * Returns the categories.
     *
     * @return ArrayCollection[]
     */
    public function getCategories()
    {
        return $this->categories;
    }
    
    
    /**
     * Sets the categories.
     *
     * @param ArrayCollection $categories
     *
     * @return void
     */
    public function setCategories(ArrayCollection $categories)
    {
        foreach ($this->categories as $category) {
            if (false === $key = $this->collectionContains($categories, $category)) {
                $this->categories->removeElement($category);
            } else {
                $categories->remove($key);
            }
        }
        foreach ($categories as $category) {
            $this->categories->add($category);
        }
    }
    
    /**
     * Checks if a collection contains an element based only on two criteria (categoryRegistryId, category).
     *
     * @param ArrayCollection $collection
     * @param \MU\BloggingModule\Entity\PostCategoryEntity $element
     *
     * @return bool|int
     */
    private function collectionContains(ArrayCollection $collection, \MU\BloggingModule\Entity\PostCategoryEntity $element)
    {
        foreach ($collection as $key => $category) {
            /** @var \MU\BloggingModule\Entity\PostCategoryEntity $category */
            if ($category->getCategoryRegistryId() == $element->getCategoryRegistryId()
                && $category->getCategory() == $element->getCategory()
            ) {
                return $key;
            }
        }
    
        return false;
    }
    
    /**
     * Returns the post.
     *
     * @return \MU\BloggingModule\Entity\PostEntity
     */
    public function getPost()
    {
        return $this->post;
    }
    
    /**
     * Sets the post.
     *
     * @param \MU\BloggingModule\Entity\PostEntity $post
     *
     * @return void
     */
    public function setPost($post = null)
    {
        $this->post = $post;
    }
    
    /**
     * Returns the posts.
     *
     * @return \MU\BloggingModule\Entity\PostEntity[]
     */
    public function getPosts()
    {
        return $this->posts;
    }
    
    /**
     * Sets the posts.
     *
     * @param \MU\BloggingModule\Entity\PostEntity[] $posts
     *
     * @return void
     */
    public function setPosts($posts)
    {
        foreach ($this->posts as $postSingle) {
            $this->removePosts($postSingle);
        }
        foreach ($posts as $postSingle) {
            $this->addPosts($postSingle);
        }
    }
    
    /**
     * Adds an instance of \MU\BloggingModule\Entity\PostEntity to the list of posts.
     *
     * @param \MU\BloggingModule\Entity\PostEntity $post The instance to be added to the collection
     *
     * @return void
     */
    public function addPosts(\MU\BloggingModule\Entity\PostEntity $post)
    {
        $this->posts->add($post);
        $post->setPost($this);
    }
    
    /**
     * Removes an instance of \MU\BloggingModule\Entity\PostEntity from the list of posts.
     *
     * @param \MU\BloggingModule\Entity\PostEntity $post The instance to be removed from the collection
     *
     * @return void
     */
    public function removePosts(\MU\BloggingModule\Entity\PostEntity $post)
    {
        $this->posts->removeElement($post);
        $post->setPost(null);
    }
    
    
    
    /**
     * Return entity data in JSON format.
     *
     * @return string JSON-encoded data
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
    
    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return array The resulting arguments list
     */
    public function createUrlArgs()
    {
        return [
            'slug' => $this->getSlug()
        ];
    }
    
    /**
     * Returns the primary key.
     *
     * @return integer The identifier
     */
    public function getKey()
    {
        return $this->getId();
    }
    
    /**
     * Determines whether this entity supports hook subscribers or not.
     *
     * @return boolean
     */
    public function supportsHookSubscribers()
    {
        return true;
    }
    
    /**
     * Return lower case name of multiple items needed for hook areas.
     *
     * @return string
     */
    public function getHookAreaPrefix()
    {
        return 'mubloggingmodule.ui_hooks.posts';
    }
    
    /**
     * Returns an array of all related objects that need to be persisted after clone.
     * 
     * @param array $objects The objects are added to this array. Default: []
     * 
     * @return array of entity objects
     */
    public function getRelatedObjectsToPersist(&$objects = []) 
    {
        return [];
    }
    
    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     *
     * @return string The output string for this entity
     */
    public function __toString()
    {
        return 'Post ' . $this->getKey() . ': ' . $this->getTitle();
    }
    
    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     */
    public function __clone()
    {
        // if the entity has no identity do nothing, do NOT throw an exception
        if (!$this->id) {
            return;
        }
    
        // otherwise proceed
    
        // unset identifier
        $this->setId(0);
    
        // reset workflow
        $this->setWorkflowState('initial');
    
        // reset upload fields
        $this->setImageForArticle(null);
        $this->setImageForArticleMeta([]);
        $this->setImageForArticleUrl('');
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    
        // clone categories
        $categories = $this->categories;
        $this->categories = new ArrayCollection();
        foreach ($categories as $c) {
            $newCat = clone $c;
            $this->categories->add($newCat);
            $newCat->setEntity($this);
        }
    }
}
