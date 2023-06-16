<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;


class UpdateUtilisateurType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('login')
            ->add('Password', PasswordType::class, [
                'constraints' => [
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/[0-9]/',
                        'message' => 'Votre mot de passe doit contenir au moins un chiffre.',
                    ]),
                    new Regex([
                        'pattern' => '/\W/',
                        'message' => 'Votre mot de passe doit contenir au moins un caractère spécial.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Veuillez insérer un nouveau mot de passe',
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => 'ROLE_SUPER_ADMIN',
                    'Enseignant' => 'ROLE_USER'
                ],
                'multiple' => true
            ])
            ->add('Modifier', SubmitType::class);
    }
}
?>