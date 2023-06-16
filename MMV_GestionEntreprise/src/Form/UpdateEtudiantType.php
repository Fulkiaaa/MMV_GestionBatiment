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

    class UpdateEtudiantType extends AbstractType {
        
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('prenom', TextType :: class, array('label' => 'Prénom :'))
                    -> add('nom', TextType :: class, array('label' => 'Nom :')) 
                    -> add('specialite', TextType :: class, array('label' => 'Spécialité :'))
                    -> add('Modifier', SubmitType :: class);
        }
    }
?>