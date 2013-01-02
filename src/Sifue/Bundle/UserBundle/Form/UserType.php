<?php

namespace Sifue\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password', 'password')
            ->add('email', 'email')
            ->add('is_active')
            ->add('department')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sifue\Bundle\DomainBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'sifue_bundle_userbundle_usertype';
    }
}
