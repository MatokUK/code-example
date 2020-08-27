<?php

namespace Matok\Bundle\BlogWebBundle\Admin;

use Matok\Bundle\BlogAdminBundle\Form\Type\ArticlePreviewLinkType;
use Matok\Bundle\BlogAdminBundle\Form\Type\SliderCollectionType;
use Matok\Bundle\BlogAdminBundle\Form\Type\SliderEntityType;
use Matok\Bundle\BlogAdminBundle\Repository\ArticleRepository;
use Matok\Bundle\BlogAdminBundle\Slugger\Slugger;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Matok\Bundle\BlogWebBundle\Entity\ArticleStatus;
use Matok\Bundle\MediaBundle\Form\Type\ImageWithPreviewType;
use Matok\Bundle\BlogAdminBundle\Form\Type\ArticlePreviewType;

class ArticleAdmin extends AbstractAdmin
{
    /** @var  ArticleRepository */
    private $repository;

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'articleId',
    );

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('delete')
            ->remove('show')
            ->add('preview', 'preview')
        //    ->add('preview_concept', 'pw-concept/{articleId}')
            ->add('sync', $this->getRouterIdParameter().'/synchronize')
            ->add('update_on_production', $this->getRouterIdParameter().'/update-on-production')
            ->add('create_on_production', $this->getRouterIdParameter().'/create-on-production')
        ;
    }

    public function getActionButtons($action, $object = null)
    {
        $x=  parent::getActionButtons($action, $object);
        if ('edit' == $action) {
            $x['sync'] = ['template' => '@BlogAdmin/Button/x_button.html.twig'];
        }
        return $x;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('serie')
            ->add('tags')
            ->add('slug')
            ->add('perex')
            ->add('content')
            ->add('articleId', null, array('label' => 'admin_id'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('articleId',
                  null,
                  array('label' => 'admin_id', 'read_only' => true))
            ->add('title')
            ->add('perex', null, array('template' => '@blog/Admin/CRUD/list_perex.html.twig'))
            ->add('pinned', null, array('editable' => true))
            ->add('createdAt', null)
            ->add('publishedAt')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                    'production' => array('template' => '@BlogWeb/Admin/CRUD/production.html.twig'),
                )
            ))
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        foreach($this->getSubject()->getTags2() as $t) {
          //  dump($t);
            //dump($t->getTag()->getTitle());
        }
        $articleId = $this->id($this->getSubject());

        $ratio = $this->repository->loadTagRatio($articleId);
        $ratioForm = [];

        foreach($ratio as $r) {
            $ratioForm[$r['tag_id']] = $r['ratio']/10;
        }

        $formMapper
            ->with('Content', array(
                'class'       => 'col-md-8',
                'box_class'   => 'box box-solid box-default',
            ))
                ->add('title')
                ->add('slug',
                    null,
                    array('required' => false)
                )
                ->add('perex')
                ->add('content',
                 //     CKEditorType::class,
                    TextareaType::class,
                      //array('config_name'  => 'bbcode')
                    array('attr' => array('rows' => 25))
                      )
            ->end()
            ->with('Publishing', array(
                    'class'       => 'col-md-4',
                    'box_class'   => 'box box-solid box-default',
                ))
                ->add('status',
                    ChoiceType::class, [
                        'required' => true,
                        'choices' => ArticleStatus::getChoiceList(),
                        'choice_translation_domain' => 'EnumAdminBundle'
                    ]
                )
                ->add('publishedAt',
                      DateTimePickerType::class,
                      array('required' => false)
                )
             //   ->add('previewLink', ArticlePreviewLinkType::class, array('mapped' => false))
//                ->add('preview', ArticlePreviewType::class, array('mapped' => false))
            ->end()
            ->with('Tags', array(
                'class'       => 'col-md-4',
                'box_class'   => 'box box-solid box-default',
            ))
            ->add('tags')
            ->add('tagRatio', SliderCollectionType::class, [
                'mapped' => false,
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'mirror_input' => 'tags',
                'label' => false,
                'data' => $ratioForm])
           //    ->add('tags2', SliderEntityType::class)
               ->add('serie')
               ->add('showFullSerie')
            ->end()
            ->with('Image', array(
                'class'       => 'col-md-4',
                'box_class'   => 'box box-solid box-default',
            ))
                ->add('topImageId',
                    ImageWithPreviewType::class,
                    array('required' => false,
                          'label' => false,
                          'css_image_wrapper_class' => 'image-preview',
                        )
                )
            ->end()
            ->with('Read only information', array(
                'class'       => 'col-md-4',
                'box_class'   => 'box box-solid box-default',
            ))
                ->add('articleId',
                    null,
                    array('label' => 'admin_id', 'required' => false, 'attr' => array('read_only' => true/*, 'disabled' => true*/))
                )
                ->add('createdAt',
                    DateTimePickerType::class,
                    array('required' => false, 'disabled' => true)
                )
                ->add('updatedAt',
                    DateTimePickerType::class,
                    array('required' => false, 'disabled' => true)
                )
                ->add('hash',
                    TextType::class,
                    array('required' => false, 'mapped' => false, 'data' => md5($this->getSubject()->getContent()))
                )
            ->end()
        ;

      //  dump($formMapper->get('topImageId'));
    }

    public function getNewInstance()
    {
        $article = parent::getNewInstance();
        dump($article);
        /*$em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');
        $conceptStatus = $em->getReference('BlogWebBundle:ArticleStatus', );*/
        $article->setStatus(new ArticleStatus(ArticleStatus::CONCEPT));

        return $article;
    }

    public function prePersist($entity)
    {
        $entity->setCreatedAt(new \DateTime());
        $entity->setUpdatedAt(new \DateTime());
        $title = $entity->getTitle();

        if (!empty($title)) {
            $entity->setSlug($this->convertToSlug($title));
        }
    }

    public function preUpdate($entity)
    {
        $entity->setUpdatedAt(new \DateTime());
        $this->removePreviousMedia($entity);
    }


    private function convertToSlug($text)
    {
        return Slugger::toSlug($text);
    }

    private function removePreviousMedia($entity)
    {
        if (null !== $entity->getTopImageId()) {
            $orm = $this->getConfigurationPool()->getContainer()->get('doctrine');
            $unitOfWork = $orm->getEntityManager()->getUnitOfWork();
            $originalData = $unitOfWork->getOriginalEntityData($entity);

            if ($originalData['topImageId'] != $entity->getTopImageId()) {
                $mediaStorage = $this->getConfigurationPool()->getContainer()->get('matok_media.storage');
                $mediaStorage->softDeleteMedia($originalData['topImageId']);
            }
        }
    }

    public function postUpdate($entity)
    {
        $this->persistTagRatio();
    }

    private function persistTagRatio()
    {
        $ratio = $this->getForm()->get('tagRatio')->getData();
        $articleId = $this->id($this->getSubject());

        $this->repository->storeTagRatio($articleId, $ratio);
    }
}
