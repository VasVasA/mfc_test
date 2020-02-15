<?php

namespace App\Form;

use App\Entity\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class WorkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add( 'title', TextType::class, ['label' =>'Заголовок'])
            //->add('content',TextareaType::class,['label' =>'Описание проблемы'])
            ->add('status',ChoiceType::class, array('choices'  => array('Новая' => "Новая", 'В работе' => "В работе", 'Завершена' => "Завершена", "Отменена"=>"Отменена"),'label'=>"Статус: "))
            //->add('image',TextType::class, ['label' =>'Изображения'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Request::class,
        ]);
    }
}
