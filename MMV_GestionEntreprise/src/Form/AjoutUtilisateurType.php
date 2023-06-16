<?php

    namespace App\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\TelType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use App\Entity\Entreprise;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;


    class AjoutUtilisateurType extends AbstractType {
        
        public function buildForm(FormBuilderInterface $builder, array $options):void
        {
            $builder->add('login')
            ->add('Password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer un mot de passe',
                    ]),
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
            ])
                    ->add('roles', ChoiceType::class, [
                        'choices' => [
                            'Administrateur' => 'ROLE_SUPER_ADMIN', 
                            'Enseignant' => 'ROLE_USER'
                        ],
                            'multiple' => true
                        ])
                    -> add('Ajouter', SubmitType :: class);
        }
    }
?>