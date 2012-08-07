<?php

namespace Msi\Bundle\PageBundle\Admin;

use Msi\Bundle\AdminBundle\Admin\Admin;

class PageBlockAdmin extends Admin
{
    public function configure()
    {
        $this->searchFields = array('type', 'name');
        $this->controller = 'MsiPageBundle:PageBlock:';
    }

    public function buildIndexTable($builder)
    {
        $builder
            ->add('enabled', 'boolean')
            ->add('name')
            ->add('setting.name', 'text', array('label' => 'position'))
            ->add('pages', 'collection')
            ->add('updatedAt', 'date')
            ->add('', 'action')
        ;
    }

    public function buildForm($builder)
    {
        $builder->add('name');

        $typeId = $this->getEntity()->getType();
        if ($typeId) {
            $blockType = $this->container->get($typeId);
            $blockType->buildForm($builder);
        } else {
            $builder
                ->add('type', 'choice', array(
                    'choices' => array(
                        'msi_block.block.text.type' => 'Text',
                        'msi_block.block.action.type' => 'Action',
                        'msi_block.block.template.type' => 'Template',
                    ),
                ))
            ;
        }
        $builder->add('pages', 'entity', array('multiple' => true, 'expanded' => true, 'class' => 'MsiPageBundle:Page'));
    }

    public function buildFilterForm($builder)
    {
        $builder->add('pages', 'entity', array(
            'class' => 'MsiPageBundle:Page',
            'label' => ' ',
            'empty_value' => '-- '.$this->container->get('translator')->transchoice('entity.Page', 1).' --',
        ));
    }
}
