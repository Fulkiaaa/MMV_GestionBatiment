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

    class ProfilType extends AbstractType {
        
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('nom', TextType :: class, array('label' => 'Nom du profil :'))
                    -> add('Envoyer', SubmitType :: class);
        }
    }
?>