<?php

namespace Matok\Bundle\MediaBundle\Form\Type;

use Matok\Bundle\MediaBundle\Form\DataTransformer\ImageCenterPointTransformer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImageCenterPickerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('centerX', TextType::class)
            ->add('centerY', TextType::class)
            ;
        //$builder->addModelTransformer(new ImageCenterPointTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'image_id' => null,
            'width' => 0,
            'height' => 0,
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['image_id'] = $options['image_id'];
        $view->vars['original_width'] = $options['width'];
        $view->vars['original_height'] = $options['height'];
    }
}