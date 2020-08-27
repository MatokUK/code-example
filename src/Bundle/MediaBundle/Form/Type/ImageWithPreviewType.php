<?php

namespace Matok\Bundle\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Matok\Bundle\MediaBundle\Configurator\Configuration;
use Matok\Bundle\MediaBundle\Form\DataTransformer\UploadedFileToIdTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class ImageWithPreviewType extends AbstractType
{
    private $storage;

    public function __construct($storage = null/*, Configuration $configuration*/)
    {
        $this->storage = $storage;
       // $this->storage->setRootDir($configuration->getUploadDirectory());
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('upload', FileType::class)
            ->add('image_id', HiddenType::class)
            ;

        $builder->addModelTransformer(new UploadedFileToIdTransformer($this->storage));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => true,
            'data_class' => null,
            'css_image_wrapper_class' => null,
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['css_wrapper'] = $options['css_image_wrapper_class'];
    }
}