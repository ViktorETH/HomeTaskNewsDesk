<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 02.06.18
 * Time: 0:28
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $categories = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->findBy([], ['name' => 'ASC']);

        return $categories;
    }

}