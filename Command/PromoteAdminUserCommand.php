<?php

namespace Toro\Bundle\AdminBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\UserBundle\Provider\UserProviderInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PromoteAdminUserCommand extends DemoteAdminUserCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('toro:admin:user:promote')
            ->setDescription('Promotes an administrator account.')
            ->setDefinition([
                new InputArgument('identifier', InputArgument::REQUIRED, 'Username or Email'),
                new InputArgument('roles', InputArgument::IS_ARRAY, 'Security roles'),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $identifier = trim($input->getArgument('identifier'));
        $manager = $this->getEntityManager();

        /** @var UserInterface $user */
        $user = $this->getUserProvider()->loadUserByUsername($identifier);

        if (null === $user) {
            throw new \InvalidArgumentException(sprintf('Could not find user identified by username or email "%s"', $identifier));
        }

        $roles = array_map('trim', $input->getArgument('roles'));
        $roles = array_merge($user->getRoles(), $roles);
        $roles = array_unique($roles);

        foreach ($roles as $role) {
            $user->addRole($role);
        }

        $manager->flush();

        $this->getUserProvider()->refreshUser($user);

        $output->writeln(sprintf('Promoted user <comment>%s</comment> to: %s', $identifier, implode(',', $roles)));
    }
}
