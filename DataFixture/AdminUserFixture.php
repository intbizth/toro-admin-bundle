<?php

namespace Toro\Bundle\AdminBundle\DataFixture;

use Symfony\Cmf\Bundle\MediaBundle\ImageInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Toro\Bundle\AdminBundle\Model\AdminUserInterface;
use Toro\Bundle\FixtureBundle\DataFixture\AbstractResourceFixture;
use Toro\Bundle\FixtureBundle\DataFixture\Uploader\ImageUploadHelper;

final class AdminUserFixture extends AbstractResourceFixture
{
    /**
     * @var ImageUploadHelper
     */
    private $uploadFileHelper;

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'admin_user';
    }

    /**
     * @param ImageUploadHelper $uploadFileHelper
     */
    public function setUploadFileHelper(ImageUploadHelper $uploadFileHelper)
    {
        $this->uploadFileHelper = $uploadFileHelper;
    }

    /**
     * @return ImageInterface
     */
    private function uploadProfilePicture($file)
    {
        $exts = explode('.', $file);
        $image = $this->uploadFileHelper->upload($file);
        $image->setName(sprintf('sys-%s.%s', uniqid(), $exts[count($exts)-1]));

        return $image;
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $options): void
    {
        $options = $this->optionsResolver->resolve($options);

        foreach ($options['custom'] as $key => $resourceOptions) {
            /** @var AdminUserInterface $resource */
            $resource = $this->exampleFactory->create($key, $resourceOptions);

            $this->objectManager->persist($resource);
            $this->objectManager->flush();

            if ($resourceOptions['profile_picture']) {
                $resource->setProfilePicture($this->uploadProfilePicture($resourceOptions['profile_picture']));
            }
        }

        $this->objectManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('email')->cannotBeEmpty()->end()
                ->scalarNode('username')->cannotBeEmpty()->end()
                ->booleanNode('enabled')->end()
                ->booleanNode('api')->defaultFalse()->end()
                ->booleanNode('root')->defaultFalse()->end()
                ->variableNode('roles')->defaultValue([])->end()
                ->scalarNode('password')->cannotBeEmpty()->end()
                ->scalarNode('first_name')->cannotBeEmpty()->end()
                ->scalarNode('last_name')->cannotBeEmpty()->end()
                ->scalarNode('locale_code')->defaultValue('th_TH')->cannotBeEmpty()->end()
                ->scalarNode('profile_picture')->defaultNull()->end()
        ;
    }
}
