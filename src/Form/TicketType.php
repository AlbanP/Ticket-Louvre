<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\VisitorType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('visitors', CollectionType::class, array(
                    'label' => ' ',
                    'entry_type'    => VisitorType::class,
                    'entry_options' => array('label' => false),
                    'allow_add'     => true,
                    'allow_delete'  => true,
                    'translation_domain' => 'messages'
                ))
                ->add('pay', SubmitType::class, array(
                    'label' => 'Valid',
                    'translation_domain' => 'messages'
                ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_ticket';
    }
}
