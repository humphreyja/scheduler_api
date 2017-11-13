<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Users\Employee;
use AppBundle\Entity\Users\Manager;

/**
 * Used for creating shifts.  Runs validations and formats.
 */
class ShiftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Set datetime formats
        $dateTimeTypeOptions = [
            'widget' => 'single_text',
            'date_format' => 'D, d M Y'
        ];

        // Build form
        $builder
            ->add('manager_id', EntityType::class, [
                'class' => Manager::class,
                'property_path'  => 'manager'
            ])
            ->add('employee_id', EntityType::class, [
                'class' => Employee::class,
                'property_path' => 'employee'
            ])
            ->add('break', NumberType::class)
            ->add('start_time', DateTimeType::class, $dateTimeTypeOptions)
            ->add('end_time', DateTimeType::class, $dateTimeTypeOptions);
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // Set config.
        // For some reason CSRF and extra fields config did not work here and I
        // had to add it to the createForm method instead
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Shift',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }
    
    public function getName()
    {
        return 'shift';
    }
}
