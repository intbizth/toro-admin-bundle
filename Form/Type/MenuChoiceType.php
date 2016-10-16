<?php

namespace Toro\Bundle\AdminBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Toro\Bundle\AdminBundle\Doctrine\ORM\MenuRepositoryInterface;
use Toro\Bundle\AdminBundle\Model\MenuInterface;

class MenuChoiceType extends AbstractType
{
    /**
     * @var MenuRepositoryInterface
     */
    protected $repository;

    /**
     * @param MenuRepositoryInterface $repository
     */
    public function __construct(MenuRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['multiple']) {
            $builder->addModelTransformer(new CollectionToArrayTransformer());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var ChoiceView $choice */
        foreach ($view->vars['choices'] as $choice) {
            $choice->label = str_repeat('— ', $choice->data->getLevel()).$choice->label;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function buildTreeChoices($choices, $level = 0)
    {
        $result = [];

        /** @var MenuInterface $choice */
        foreach ($choices as $choice) {
            $result[] = new ChoiceView(
                str_repeat('-', $level).' '.$choice->getName(),
                $choice->getId(),
                $choice,
                []
            );

            if (!$choice->getChildren()->isEmpty()) {
                $result = array_merge(
                    $result,
                    $this->buildTreeChoices($choice->getChildren(), $level + 1)
                );
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $choiceList = function (Options $options) {
            if (null !== $options['root']) {
                if (is_string($options['root'])) {
                    $menus = $this->repository->findChildrenByRootCode($options['root']);
                } else {
                    $menus = $this->repository->findChildren($options['root']);
                }
            } else {
                $menus = $this->repository->findNodesTreeSorted();
            }

            if (null !== $options['filter']) {
                $menus = array_filter($menus, $options['filter']);
            }

            return new ObjectChoiceList($menus, null, [], null, 'id');
        };

        $resolver
            ->setDefaults([
                'choice_translation_domain' => false,
                'choice_list' => $choiceList,
                'root' => null,
                'filter' => null,
            ])
            ->setAllowedTypes('root', [MenuInterface::class, 'string', 'null'])
            ->setAllowedTypes('filter', ['callable', 'null'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toro_menu_choice';
    }
}
