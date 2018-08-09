<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 07.06.18
 * Time: 14:20
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\ComentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{

//    /**
//     * @Route("/login", name="login")
//     */
//    public function indexAction()
//    {
//        $user = new User();
//        $plainPassword = 'ryampass';
//        $encoder = $this->container->get('security.password_encoder');
//        $encoded = $encoder->encodePassword($user, $plainPassword);
//        dump($encoded);
//        return new Response('Hello');
//    }
}