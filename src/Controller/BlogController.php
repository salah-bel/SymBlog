<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ArticleType;



use App\Entity\Article;
use App\Repository\ArticleRepository;

class BlogController extends AbstractController
{
    
   
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles

        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('blog/home.html.twig');
    }
        

    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    
    public function form(Request $request, Article $article=null ){

        if(!$article){      //SI L'ARTICLE=NULL CREE MOI UN ARTICLE    
           $article = new Article;
       }

    //    $form = $this->createFormBuilder($article)
    //                  ->add('title')
    //                  ->add('content')   
    //                  ->add('image')
    //                  ->getForm();
        $form = $this->createForm(ArticleType::class, $article);


        // ANALYSE DE LA REQUETTE avec la methode handelrequest
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            if(!$article->getId()){     //SI L'ARTICLE N'A PAS D'ID C.A.D T ENTRAIN DE FAIRE UN UPDATE ET NON PAS UN CREATE
                                        // ALORS RAJOUTE LA DATE LORS DE LA CREATION /PUISQUE LE FORMULAIRE SERT AU DEUX
                $article->setCreatedAt(new \DateTime);
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($article);        
            $manager->flush();

            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);

        }

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }


    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    // DEUXIEME METHODE :INJECTION DE DEPONDENCE DANS LE FUNCTION
    // SHOW(ARTICLE)-- IL SAURA TROUVER L'ARTICLE A TRAVERS L'ID PASSé
    public function show(ArticleRepository $repo,$id){
        $article = $repo->find($id);
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ] );
    }
}
