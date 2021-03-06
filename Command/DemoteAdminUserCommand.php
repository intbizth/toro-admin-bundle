<?php

namespace Toro\Bundle\AdminBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\UserBundle\Provider\UserProviderInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

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

        /** @var UserInterface $user */
        $user = $this->getUserProvider()->loadUserByUsername($identifier);

        if (null === $user) {
            throw new \InvalidArgumentException(sprintf('Could not find user identified by username or email "%s"', $identifier));
        }

        $roles = array_map('trim', $input->getArgument('roles'));
        $roles = array_unique($roles);

        foreach ($roles as $role) {
            $user->removeRole($role);
        }

        $manager->flush();

        $this->getUserProvider()->refreshUser($user);

        $nowRoles = empty($user->getRoles()) ? 'Nothing' : implode(',', $user->getRoles());

        $output->writeln(sprintf('Demoted user <comment>%s</comment> to: %s', $identifier, $nowRoles));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('identifier')) {
            $helper = $this->getHelper('question');
            $question = new Question('Please enter an identifier:', false);
            $question->setNormalizer(function ($value) {
                if (empty($value)) {
                    throw new \Exception('Identifier can not be empty');
                }

                return $value;
            });

            $identifier = $helper->ask($input, $output, $question);
            $input->setArgument('identifier', $identifier);
        }

        if (!$input->getArgument('roles')) {
            $helper = $this->getHelper('question');
            $question = new Question('Please enter roles (separate by space for many):', false);
            $question->setNormalizer(function ($value) {
                $roles = trim(preg_replace('!\s+!', ' ', $value));

                if (empty($roles)) {
                    throw new \Exception('Roles can not be empty');
                }

                return explode(' ', $roles);
            });

            $roles = $helper->ask($input, $output, $question);
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
        return $this->getContainer()->get('sylius.admin_user_provider.email_or_name_based');
    }
}
