<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\CoreBundle\Form\Administration;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class SessionType extends AbstractType
{
    private $sessionType;

    public function __construct($sessionType = 'native')
    {
        $this->sessionType = $sessionType;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'session_storage_type',
                'choice',
                array(
                    'choices' => array(
                        'native' => 'files',
                        'claro_pdo' => 'database',
                        'pdo' => 'external_pdo_database'
                    ),
                    'label' => 'storage_type'
                )
            );

        $builder
            ->add(
                'session_db_dsn',
                'text',
                array(
                    'label' => 'DSN',
                    'required' => false,
                    'constraints' => $this->notBlankIfPdo(),
                    'theme_options' => $this->hiddenIfPdo()
                )
            )
            ->add(
                'session_db_user',
                'text',
                array(
                    'label' => 'user',
                    'required' => false,
                    'constraints' => $this->notBlankIfPdo(),
                    'theme_options' => $this->hiddenIfPdo()
                )
            )
            ->add(
                'session_db_password',
                'password',
                array(
                    'label' => 'password',
                    'required' => false,
                    'theme_options' => $this->hiddenIfPdo()
                )
            )
            ->add(
                'session_db_table',
                'text',
                array(
                    'label' => 'db_table',
                    'required' => false,
                    'constraints' => $this->notBlankIfPdo(),
                    'theme_options' => $this->hiddenIfPdo()
                )
            )
            ->add(
                'session_db_id_col',
                'text',
                array(
                    'label' => 'id_col',
                    'required' => false,
                    'constraints' => $this->notBlankIfPdo(),
                    'theme_options' => $this->hiddenIfPdo()
                )
            )
            ->add(
                'session_db_data_col',
                'text',
                array(
                    'label' => 'data_col',
                    'required' => false,
                    'constraints' => $this->notBlankIfPdo(),
                    'theme_options' => $this->hiddenIfPdo()
                )
            )
            ->add(
                'session_db_time_col',
                'text',
                array(
                    'label' => 'time_col',
                    'required' => false,
                    'constraints' => $this->notBlankIfPdo(),
                    'theme_options' => $this->hiddenIfPdo()
                )
            );

        $builder->add(
            'cookie_lifetime',
            'number',
            array(
                'required' => true,
                'label' => 'cookie_lifetime',
                'constraints' => new GreaterThanOrEqual(array('value' => 60))
            )
        );
    }

    public function getName()
    {
        return 'platform_session_form';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('translation_domain' => 'platform'));
    }

    private function notBlankIfPdo()
    {
        if ($this->sessionType === 'pdo') {
            return new NotBlank();
        }

        return array();
    }

    private function hiddenIfPdo()
    {
        if ($this->sessionType === 'pdo') {
            return array('display_row' => false);
        }

        return array();
    }
}
