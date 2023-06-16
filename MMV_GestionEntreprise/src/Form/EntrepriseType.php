<?php
    namespace App\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;

    class EntrepriseType extends AbstractType {
        
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('nom', TextType :: class, array('label' => 'Nom :'))
                    -> add('rs', TextType :: class, array('label' => 'Raison sociale :', 'required' => false))
                    -> add('adresse', TextType :: class, array('label' => 'Adresse :'))
                    -> add('cp', IntegerType :: class, array('label' => 'Code postal :'))
                    -> add('ville', TextType :: class, array('label' => 'Ville :'))
                    -> add('pays', TextType :: class, array('label' => 'Pays :'))  
                    -> add('specialite', TextareaType :: class, array('label' => 'Spécialité :'))
                    -> add('Envoyer', SubmitType :: class);
        }
    }
?>