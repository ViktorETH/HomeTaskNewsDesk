<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormTypeInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/registered", name="registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('@App/default/registration.html.twig',
            [
                'register_form' => $form->createView()
            ]
        );
    }
}