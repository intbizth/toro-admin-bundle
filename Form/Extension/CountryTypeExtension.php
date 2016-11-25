<?php

namespace Toro\Bundle\AdminBundle\Form\Extension;

use Sylius\Bundle\AddressingBundle\Form\Type\CountryType;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Intl\Intl;

class CountryTypeExtension extends AbstractTypeExtension
{
    /**
     * @var RepositoryInterface
     */
    private $countryRepository;

    /**
     * @param RepositoryInterface $countryRepository
     */
    public function __construct(RepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            // Adding dynamically created isoName field
            $nameOptions = [
                'label' => 'sylius.form.country.name',
            ];

            $country = $event->getData();

            if ($country instanceof CountryInterface && null !== $country->getCode()) {
                $nameOptions['disabled'] = true;
                $nameOptions['choices'] = [
                    $country->getCode() => $this->getCountryName($country->getCode())
                ];
            } else {
                $nameOptions['choices'] = $this->getAvailableCountries();
            }

            $nameOptions['choices_as_values'] = false;

            $form = $event->getForm();
            $form->add('code', 'country', $nameOptions);
        });

        $builder
            ->add('provinces', 'collection', [
                'type' => 'sylius_province',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'button_add_label' => 'sylius.form.country.add_province',
            ])
            ->add('enabled', 'checkbox', [
                'label' => 'Enabled',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return CountryType::class;
    }

    /**
     * @param $code
     *
     * @return null|string
     */
    private function getCountryName($code)
    {
        return Intl::getRegionBundle()->getCountryName($code);
    }

    /**
     * @return array
     */
    private function getAvailableCountries()
    {
        $availableCountries = Intl::getRegionBundle()->getCountryNames();

        /** @var CountryInterface[] $definedCountries */
        $definedCountries = $this->countryRepository->findAll();

        foreach ($definedCountries as $country) {
            unset($availableCountries[$country->getCode()]);
        }

        return $availableCountries;
    }
}
