<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $R,EntityManagerInterface $manage, UserPasswordHasherInterface $hasher): Response
    {   
        $user= new User();
        $RF=$this->createForm(RegisterFormType::class,$user);
        $RF->handleRequest($R);
        if($RF->isSubmitted() and $RF->isValid()){
                $pw=$hasher->hashPassword($user,$user->getPassword());
                $user->setPassword($pw);
                $manage->persist($user);
                $manage->flush();
        }

        return $this->render('user/register.html.twig', ['RegisterForm'=>$RF->createView()]);
    }
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $AU){
        $error= $AU->getLastAuthenticationError();
        $username = $AU->getLastUsername();
        return $this->render('user/login.html.twig', [
            'error'=> $error,
            'username'=>$username
        ]);

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(AuthenticationUtils $AU){
        

    }
}
