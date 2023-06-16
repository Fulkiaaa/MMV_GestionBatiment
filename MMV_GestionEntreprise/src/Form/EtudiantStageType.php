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
    use App\Entity\Etudiant;
    use App\Entity\Stage;

class EtudiantStageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder
    ->add('etudiant', EntityType::class, array(
        'class' => Etudiant::class,
        'choice_label' => 'nom',
        'multiple' => false,
        'expanded' => false,
        'label' => 'Nom de l\'étudiant :'))
    ->add('stage', EntityType::class, array(
        'class' => Stage::class,
        'choice_label' => 'code',
        'multiple' => false,
        'expanded' => false,
        'label' => 'Le stage :'))
    ->add('annee', DateType::class, array(
        'label' => 'Choisir la date à laquelle l\'étudiant à éffectuer le stage (la date de début) :'))

    -> add('Envoyer', SubmitType :: class);
    }

}
?>