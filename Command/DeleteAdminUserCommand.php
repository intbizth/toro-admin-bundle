<?php

namespace Toro\Bundle\AdminBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\UserBundle\Provider\UserProviderInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DeleteAdminUserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('toro:admin:user:delete')
            ->setDescription('Deletes an administrator account.')
            ->setDefinition([
                new InputArgument('identifier', InputArgument::REQUIRED, 'Username or Email'),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $identifier = $input->getArgument('identifier');
        $manager = $this->getEntityManager();

        $user = $this->getUserProvider()->loadUserByUsername($identifier);

        if (null === $user) {
            throw new \InvalidArgumentException(sprintf('Could not find user identified by username or email "%s"', $identifier));
        }

        $manager->remove($user);
        $manager->flush();

        $output->writeln(sprintf('Deleted user <comment>%s</comment>', $identifier));
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
