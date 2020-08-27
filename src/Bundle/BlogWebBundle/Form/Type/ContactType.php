<?php

namespace Matok\Bundle\BlogWebBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, [
                'attr' => ['placeholder' => 'Subject'],
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'Email'],
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['placeholder' => 'Your messages...'],
            ])
        ;
    }
}