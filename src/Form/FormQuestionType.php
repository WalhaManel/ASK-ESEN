<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FormQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',null,
            ["label"=>"Votre question",
            "help"=>"Soyez précis et imaginez que vous posez une question à une autre personne",
            "help_attr"=>["class"=>"fs-13 pb-3 lh-20"],
            "label_attr"=>["class"=>"fs-14 text-black fw-medium mb-0"],
            "attr"=>["class"=>"form-control form--control","placeholder"=>"Question?"]
            ])
            ->add('contenu',TextareaType::class,
            ["label"=>"Contenu de votre question",
            "label_attr"=>["class"=>"fs-14 text-black fw-medium mb-0"],
            "attr"=>["class"=>"form-control form--control user-text-editor"]
            ])
            ->add('save',SubmitType::class,["label"=>"Publier ma question"])
            //->add('etat')
            //->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
