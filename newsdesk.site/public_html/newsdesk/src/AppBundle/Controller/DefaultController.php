<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
use AppBundle\Form\CommentType;
use AppBundle\Form\SearchForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    /**
     * @return Category[]
     */
    public function indexCategories()
    {
        $categories = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->findBy([], ['name' => 'ASC']);

        return $categories;
    }

    public function indexCategory($category)
    {
        foreach ($this->indexCategories() as $value) {
            if ($category == $value->getName()) {
                $category = $this
                    ->getDoctrine()
                    ->getRepository('AppBundle:Article')
                    ->findOneBy(["name" => $category]);
            }
        }
        return $category;
    }

    /**
     * @return Article[]
     */
    public function indexAllArticles()
    {
        $articles = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->findAll();
        return $articles;
    }

    /**
     * @param $id
     * @return Article[]
     */
    public function indexArticles($id)
    {
        foreach ($this->indexCategories() as $value) {
            if ($id == $value->getId()) {
                $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->findByCategory($id);
            }
        }
        return $articles;
    }

    /**
     * @param $article_id
     * @return null|object
     */
    public function indexArticle($article_id)
    {
        foreach ($this->indexAllArticles() as $value) {
            if ($article_id == $value->getId()) {
                $article = $this->getDoctrine()->getRepository('AppBundle:Article')->findOneBy(["article" => $article_id]);
            }
        }
        return $article;
    }

    public function getlistArticle()
    {
        $articleRepo = $this->getDoctrine()->getRepository('AppBundle:Article');
        $mainArticles = $articleRepo->findAll();
        return $mainArticles;
    }

    public function searchAction(Request $request)
    {
        $pageRepo = $this->getDoctrine()->getRepository('AppBundle:Article');
        $searchForm = $this->createForm(SearchForm::class);
        $searchForm->handleRequest($request);
        $pages = null;
        if($searchForm->isSubmitted())
        {
            $data = $searchForm->getData();
            $pages = $pageRepo->findByWord($data['search']);
        }
        return $pages;
    }

    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function indexAction()
    {
        $pageRepo = $this->getDoctrine()->getRepository('AppBundle:Article');

        $daysArticles = $pageRepo->findTodaysNewsAction();

        return $this->render('@App/default/homeMain.html.twig',
            [
                'categories' => $this->indexCategories(),
                'mainArticles' => $this->getlistArticle(),
                'daysArticles' => $daysArticles
            ]
        );
    }


    /**
     * @Route("/{category}/{id}", name="category_item")
     * @param $category
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function showAction($category, $id, Request $request)
    {
        $articleRepo = $this->getDoctrine()->getRepository('AppBundle:Article');
        $page = $request->query->get('page') ? $request->query->get('page') : 1;
        $limit = 3;
        $articles = $articleRepo->searchArticles($page, $limit, $id);

        $paginator =
            [
                'page' => $page,
                'total' => $articleRepo->countPage(),
                'limit' => $limit
            ]
        ;

        return $this->render('@App/default/category.html.twig',
            [
                "categories" => $this->indexCategories(),
                "category" => $category,
                "id" => $id,
//                "articles" => $this->indexArticles($id),
                "articles" => $articles,
                "paginator" => $paginator
            ]
        );
    }

    /**
     * @Route("/{category}/{id}/{item}", name="article_item", )
     * @param Category $category
     * @param $id
     * @param $item
     * @param Request $request
     * @return Response
     */
    public function articleAction($category, $id, $item, Request $request)
    {
        $pageRepo = $this->getDoctrine()->getRepository('AppBundle:Article');

        $daysArticles = $pageRepo->findTodaysNewsAction();

        $article = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->findOneBy(["title" => $item]);
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $form = $this->createForm(CommentType::class);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData();
            $article->addComment($comment);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_item',
                [
                    "category" => $category,
                    "id" => $id,
                    "article" => $article,
                    "item" => $item,
                    "comment" => $comment
                ]);
        } else {
            return $this->render('@App/categories/article.html.twig',
                [
                    "categories" => $this->indexCategories(),
                    "category" => $category,
                    "daysArticles" => $daysArticles,
                    "id" => $id,
                    "article" => $article,
                    "item" => $item,
                    'mainArticles' => $this->getlistArticle(),
                    "comment_form" => $form->createView()
                ]);
        }
    }

    /**
     * @Route("/todays", name="todays_articles")
     */
    public function articlesTodaysAction()
    {
        $articles = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->findTodaysNewsAction();

        dump($articles);

        return $this->render('@App/default/dayNews.html.twig',
            [
                'categories' => $this->indexCategories(),
                'daysArticles' => $articles,
            ]
        );
    }


    /**
     * @Route("/search", name="search_articles")
     * @param Request $request
     * @return Response
     */
    public function searchArticles(Request $request)
    {

        $pageRepo = $this->getDoctrine()->getRepository('AppBundle:Article');
        $searchForm = $this->createForm(SearchForm::class);
        $searchForm->handleRequest($request);
        $pages = null;
        if($searchForm->isSubmitted())
        {
            $data = $searchForm->getData();
            $pages = $pageRepo->findByWord($data['search']);
        }

        return $this->render('@App/default/search.html.twig',
            [
                'categories' => $this->indexCategories(),
                'articles' => $this->searchAction($request),
                'pages' => $pages,
                'search' => $searchForm->createView()
            ]);
    }


}