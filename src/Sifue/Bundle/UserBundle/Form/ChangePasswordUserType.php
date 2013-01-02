<?php

namespace Sifue\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangePasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', 'repeated', array (
            'type'            => 'password',
            'invalid_message' => '同じパスワードを入れて下さい',
            'options' => array('attr' => array('class' => 'password-field')),
            'required' => true,
            'first_options' => array('label' => 'パスワード'),
            'second_options' => array('label' => 'パスワード確認')
        ))
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
        return 'sifue_bundle_userbundle_changepasswordusertype';
    }
}
