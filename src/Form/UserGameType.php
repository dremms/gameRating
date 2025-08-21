<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Platform;
use App\Entity\User;
use App\Entity\UserGame;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class UserGameType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['currentUser'];

        $builder
            ->add('game', EntityType::class, [
                'class' => Game::class,
                'choices' => $options['games'],
                'choice_label' => 'title',
                'label' => 'Jeu',
                'placeholder' => 'Choisir un jeu',
                'disabled' => $options['disableGame']
            ])
            ->add('scorePercent', NumberType::class, ['label' => $options['ratingScaleLabel'], 'data' => $options['scorePercent']])
            ->add('completedStory', null, ['label' => 'Histoire Terminée'])
            ->add('completedFull', null, ['label' => '100%'])
            ->add('earlyAccess', null, ['label' => 'Early Access'])
            ->add('hours', IntegerType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'heures',
                'data' => $options['dataHours'] ?? 0,
            ])
            ->add('minutes', IntegerType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Minutes',
                'data' => $options['dataMinutes'] ?? 0,
            ])
            ->add('seconds', IntegerType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Secondes',
                'data' => $options['dataSeconds'] ?? 0,
            ])
            ->add('playStartDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date Début',
                'data' => $options['data']->getPlayStartDate() ?? new \DateTime('now')]
            )
            ->add('playEndDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date Fin',
                'data' => $options['data']->getPlayEndDate() ?? new \DateTime('now')]
            )
            ->add('comment', TextareaType::class, ['label' => 'Commentaire', 'required' => false, 'attr' => ['rows' => 8]])
            ->add('platform', EntityType::class, [
                'class' => Platform::class,
                'choices' => $options['platforms'],
                'choice_value' => fn (?Platform $platform) => $platform ? $platform->getId() : '',
                'choice_label' => fn (Platform $platform) => $platform->getName(),
                'data' => $options['currentPlatform'] ?? null,
                'required' => false,
                'mapped' => false,
                'label' => 'Plateforme',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserGame::class,
            'ratingScaleLabel' => null,
            'currentUser' => null,
            'games' => [],
            'disableGame' => false,
            'dataHours' => null,
            'dataMinutes' => null,
            'dataSeconds' => null,
            'scorePercent' => null,
            'platforms' => null,
            'currentPlatform' => null,
        ]);
    }
}
