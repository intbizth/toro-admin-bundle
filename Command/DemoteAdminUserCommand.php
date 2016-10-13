<?php

namespace Toro\Bundle\AdminBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\UserBundle\Provider\UserProviderInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DemoteAdminUserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('toro:admin:user:demote')
            ->setDescription('Demotes an administrator account.')
            ->setDefinition([
                new InputArgument('identifier', InputArgument::REQUIRED, 'Username or Email'),
                new InputArgument('roles', InputArgument::IS_ARRAY, 'Security roles', ['ROLE_ADMINISTRATION_ACCESS']),
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

        $user = $this->getUserProvider()->loadUserByUsername($identifier);

        if (null === $user) {
            throw new \InvalidArgumentException(sprintf('Could not find user identified by username or email "%s"', $identifier));
        }

        $roles = array_map('trim', $input->getArgument('roles'));
        $roles = array_unique($roles);

        $olderRoles = $user->getRoles();
        $newRoles = array_diff($olderRoles, $roles);

        $user->setRoles($newRoles);

        $manager->flush();

        $this->getUserProvider()->refreshUser($user);

        $nowRoles = empty($newRoles) ? 'Nothing' : implode(',', $newRoles);

        $output->writeln(sprintf('Demoted user <comment>%s</comment> to: %s', $identifier, $nowRoles));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('identifier')) {
            $identifier = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please enter an username or email:',
                function ($username) {
                    if (empty($username)) {
                        throw new \Exception('Identifier can not be empty');
                    }
                    return $username;
                }
            );

            $input->setArgument('identifier', $identifier);
        }

        if (!$input->getArgument('roles')) {
            $roles = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please enter an roles (Separate by space for many) :',
                function ($roles) {
                    $roles = trim(preg_replace('!\s+!', ' ', $roles));

                    if (empty($roles)) {
                        throw new \Exception('Roles can not be empty');
                    }

                    return explode(' ', $roles);
                }
            );

            $input->setArgument('roles', $roles);
        }
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('sylius.manager.admin_user');
    }

    /**
     * @return UserProviderInterface
     */
    protected function getUserProvider()
    {
        return $this->getContainer()->get('sylius.admin_user.provider.email_or_name_based');
    }
}
