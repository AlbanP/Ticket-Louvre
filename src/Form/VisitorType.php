<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class VisitorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, array(
                    'label' => 'Surname'))
                ->add('lastName', TextType::class, array(
                    'label' => 'Name','attr' => array(
                        'class' => 'name')))
                ->add('birthday', DateType::class, array(
                    'label' => 'Birthday',
                    'widget' => 'single_text',
                    'invalid_message' => 'Bad date (ex: 1789-07-14)',
                    'attr' => array(
                        'placeholder' => 'yyyy-mm-dd',
                        'class' => 'birthday')))
                ->add('country', CountryType::class, array(
                    'label' => 'Country',
                    'attr' => array(
                        'class' => 'country',
                        'required' => true,)))
                ->add('reduction', CheckboxType::class, array(
                    'required' => false,
                    'label' => 'Reduction'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Visitor'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_visitor';
    }

    public function getName()
    {
        return 'form_visitors';
    }
}
