<?php
    namespace App\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use App\Entity\Profil;
    use App\Entity\EmployeProfil;
    use App\Entity\Employe;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\Form\Extension\Core\Type\DateTypeInterface;

class EmployeProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder
    ->add('profil', EntityType::class, array(
        'class' => Profil::class,
        'choice_label' => 'nom',
        'multiple' => false,
        'expanded' => false,
        'label' => 'Profil :'))
    ->add('employe', EntityType::class, array(
        'class' => Employe::class,
        'choice_label' => 'nom',
        'multiple' => false,
        'expanded' => false,
        'label' => 'Employé :'))
    ->add('annee', DateType::class, array(
        'label' => 'Choisir la date à laquelle l\'employé a tenue le profil :'))

    -> add('Envoyer', SubmitType :: class);
    }

}
?>