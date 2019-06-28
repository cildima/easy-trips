<?php

namespace App\Admin;


use App\Entity\Country;
use App\Entity\Images;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CountriesAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_sort_order' => 'ASC',
        '_sort_by' => 'title'
    ];

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('General')
            ->with('General')
            ->add('active', CheckboxType::class, [
                'value' => false,
                'required' => false
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'title'
                ]
            ])
            ->add('slug', TextType::class, [
                'attr' => [
                    'class' => 'slug'
                ],
                'help' => '<a class="btn btn-primary checkslug" href="/admin/helpers/checkslug" data-entity="Country">Check</a>'
            ])
            ->add('languageCode', TextType::class)
            ->end()->end();

        $countries = $this->getModelManager()->getEntityManager(Country::class)->getRepository(Country::class)->findByCountriesAsArrayWithTitlesAsKeys();

        $formMapper->tab('Translations')
            ->with('Translations')
            ->add('countries', ChoiceType::class, [
                'choices' => $countries,
                'multiple' => true,
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'chooseLanguage'
                ]
            ])
            ->end()
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('active')
            ->add('title')
            ->add('languageCode')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $this->showMosaicButton(false);

        $listMapper
            ->addIdentifier('title')
            ->add('languageCode')
            ->add('active')
        ;
    }

    protected function configureBatchActions($actions)
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            unset($actions['delete']);
        }
        $actions['disable'] = [
            'ask_confirmation' => true
        ];

        return $actions;
    }
}