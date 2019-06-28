<?php

namespace App\Admin;

use App\Entity\Country;
use RedCode\TreeBundle\Admin\AbstractTreeAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PageAdmin extends AbstractTreeAdmin
{
    public function __construct($code, $class, $baseControllerName, $treeTextField)
    {
        parent::__construct($code, $class, $baseControllerName, $treeTextField);
        $this->listModes['tree'] = [
            'class' => 'fa fa-sitemap fa-fw',
        ];
    }

    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->tab('General')
            ->with('General')
            ->add('active', CheckboxType::class, [
                'value' => false,
                'required' => false
            ])
            ->add('parent', ModelListType::class, [
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
                'help' => '<a class="btn btn-primary checkslug" href="/helpers/checkslug" data-entity="Page">Check</a>'
            ])
            ->end()
            ->end()
        ;

        $countries = $this->getModelManager()->getEntityManager(Country::class)->getRepository(Country::class)->findByCountriesAsArrayWithTitlesAsKeys();

        $formMapper
            ->tab('Translations')
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
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('active');
        $datagridMapper->add('title');
        $datagridMapper->add('translations');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $this->showMosaicButton(false);
        $listMapper
            ->addIdentifier('title')
            ->add('translations' )
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