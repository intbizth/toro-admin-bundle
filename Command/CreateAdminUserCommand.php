<?php

namespace Toro\Bundle\AdminBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Toro\Bundle\AdminBundle\Model\AdminUserInterface;

class CreateAdminUserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('toro:admin:user:create')
            ->setDescription('Creates a new admin user account.')
            ->setDefinition([
                new InputArgument('email', InputArgument::REQUIRED, 'Email'),
                new InputArgument('password', InputArgument::REQUIRED, 'Password'),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = $this->createUser($email, $password);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $output->writeln(sprintf('Created user <comment>%s</comment>', $email));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        if (!$input->getArgument('email')) {
            $question = new Question('Please enter an email:', false);
            $question->setNormalizer(function ($value) {
                if (empty($value)) {
                    throw new \Exception('Email can not be empty');
                }

                return $value;
            });

            $email = $helper->ask($input, $output, $question);
            $input->setArgument('email', $email);
        }

        if ($this->getUserRepository()->findOneBy(['email' => $input->getArgument('email')])) {
            throw new \Exception('This email already exist.');
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please enter password:', false);
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $question->setNormalizer(function ($value) {
                if (empty($value)) {
                    throw new \Exception('password can not be empty');
                }

                return $value;
            });

            $password = $helper->ask($input, $output, $question);
            $input->setArgument('password', $password);
        }
    }

    protected function createUser($email, $password, array $securityRoles = [ AdminUserInterface::DEFAULT_ADMIN_ROLE ])
    {
        /** @var UserInterface $user */
        $user = $this->getUserFactory()->createNew();
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->enable();

        foreach ($securityRoles as $role) {
            $user->addRole($role);
        }

        return $user;
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return FactoryInterface
     */
    protected function getUserFactory()
    {
        return $this->getContainer()->get('sylius.factory.admin_user');
    }

    /**
     * @return RepositoryInterface
     */
    protected function getUserRepository()
    {
        return $this->getContainer()->get('sylius.repository.admin_user');
    }
}
