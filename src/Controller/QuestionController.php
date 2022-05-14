<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\FormQuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuestionController extends AbstractController
{
    /**
     * Undocumented function
     *
     * @param Request $r
     * @return void
     * @Route("/create",name="create_question")
     * @IsGranted("ROLE_USER")
     */
 public function create(Request $r,EntityManagerInterface $manage){
     $question= new Question();
     /*$formQuestion=$this->createFormBuilder($question)
        ->add('titre',null,["label"=>"Votre question","attr"=>["class"=>"class1","placeholder"=>"Question ?"]])
        ->add('contenu')
        ->add('save',SubmitType::class,["label"=>"Publier ma question"])
        ->getForm();
    */
    $formQuestion=$this->createForm(FormQuestionType::class,$question);
    $formQuestion->handleRequest($r);
    if($formQuestion->isSubmitted() and $formQuestion->isValid()){
        $question->setEtat(1);
        $question->setCreatedAt(new \DateTime());
        $question->setUser($this->getUser());
        $manage->persist($question);
        $manage->flush();
        $this->addFlash('success','Votre question a bien été publiée!');
        return $this->redirectToRoute("create_question");
    }
    return $this->render("question/create.html.twig",['formQuestion'=>$formQuestion->createView()]);
 }
}