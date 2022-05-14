<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Question;
use App\Form\ReponseFormType;
use App\Repository\ReponseRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(EntityManagerInterface $manager): Response
    {   $QR=$manager->getRepository(Question::class);
        $q=$QR->findby(
            [],
            ['createdAt'=> "DESC"]
        );
        return $this->render('accueil/index.html.twig',['questions'=>$q]);

    }

    /**
     * Afficher les détails d'une questions
     */
    public function show0(string $slug ,int $id,QuestionRepository $QE):Response
    {
        $q= $QE->find($id);
        return $this->render("accueil/detail.html.twig",['question'=>$q,'states'=>$q::$states]);
    }


    /**
     * Afficher les détails d'une questions avec doctrine
     * 
     * @param Question $question 
     * @return Response 
     * 
     * @Route("/{slug<[a-z\-0-9]+>}/{id<\d+>}",name="show_question")
     */
    public function show(?Question $q,ReponseRepository $rp,Request $r,EntityManagerInterface $manage):Response
    {   
        if($q == null){
            throw $this->createNotFoundException("La question demandée n'existe pas !");
        }
       
        $rep=$q->getReponses();
        $Reponse = new Reponse();
        $repform= $this->createForm(ReponseFormType::class,$Reponse);
        $repform->handleRequest($r);
        if($repform->isSubmitted() and $repform->isValid()){
            $Reponse->setCreatedAt(new \DateTimeImmutable());
            $Reponse->setQuestion($q);
            $Reponse->setUser($this->getUser());
            $manage->persist($Reponse);
        $manage->flush();
        $this->addFlash('success','Votre reponse a bien été publiée!');
        return $this->redirectToRoute("accueil/detail.html.twig",['question'=>$q,'states'=>Question::$states,'reponses'=>$rep,'repform'=>$repform->createView()]);
        
        }
       return $this->render("accueil/detail.html.twig",['question'=>$q,'states'=>Question::$states,'reponses'=>$rep,'repform'=>$repform->createView()]);
    }
}
