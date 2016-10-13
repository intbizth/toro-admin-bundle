<?php

namespace Toro\Bundle\AdminBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Toro\Bundle\AdminBundle\Form\ChoiceList\EntityLoader;

/**
 * Generic use for Ajax auto-complete
 */
class ChoiceSelfResizeType extends AbstractType
{
    /**
     * @var RepositoryInterface|EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param RepositoryInterface|EntityRepository $repository
     * @param string $name
     */
    public function __construct(RepositoryInterface $repository, $name = null)
    {
        $this->repository = $repository;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EntityLoader $loader */
        $loader = $options['loader'];
        $identifier = $options['identifier'];

        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) use ($loader) {
                if ($event->getData()) {
                    $loader->setIds($event->getData());
                }
            })
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($loader, $identifier) {
                if ($data = $event->getData()) {
                    $accessor = PropertyAccess::createPropertyAccessor();
                    $loader->setIds($accessor->getValue($data, $identifier));
                }
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver
            ->setDefaults([
                'identifier' => 'id',
                'class' => $this->repository->getClassName(),
                'loader' => new EntityLoader($this->repository)
            ])
            ->setAllowedTypes('identifier', 'string')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'entity';
    }
}
