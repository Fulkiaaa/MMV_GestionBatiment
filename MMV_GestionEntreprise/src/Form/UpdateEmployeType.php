<?php
    namespace App\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use App\Entity\Entreprise;

    class UpdateEmployeType extends AbstractType {
        
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('nom', TextType :: class, array('label' => 'Nom :'))
                    -> add('prenom', TextType :: class, array('label' => 'Prénom :'))
                    -> add('fonction', TextType :: class, array('label' => 'Fonction :'))
                    -> add('mail', TextType :: class, array('label' => 'Mail :'))
                    -> add('tel', TextType :: class, array('label' => 'Téléphone :'))
                    -> add('entreprise', EntityType::class, array(
                        'class' => Entreprise::class,
                        'choice_label' => 'nom',
                        'multiple' => false,
                        'expanded' => false,
                        'label' => 'Entreprise :'))
                    -> add('Modifier', SubmitType :: class);
        }
    }
?>