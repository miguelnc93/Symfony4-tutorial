<?php
/**
 * Created by PhpStorm.
 * User: UserName
 * Date: 8/28/2018
 * Time: 6:13 PM
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class BlogController
{

    private $twig;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(\Twig_Environment $twig, SessionInterface $session, RouterInterface $router)
    {
        $this->twig = $twig;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @Route("/testing", name="blog_index")
     */
    public function index()
    {
        $html = $this->twig->render(
            'blog/index.html.twig',
            [
                'posts' => $this->session->get('posts')
            ]
        );
        return new Response($html);
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add(){
        $posts = $this->session->get('posts');
        $posts[uniqid()] = [
            'title' => 'A random title'.rand(1, 500),
            'text' => 'Some random text nr '.rand(1, 500),
            'date' => new \DateTime(),
        ];
        //save the posts into session
        $this->session->set('posts', $posts);

        return new RedirectResponse($this->router->generate('blog_index'));
    }

    /**
     * @Route("/show/{id}/{title}", name="blog_show")
     */
    public function show($id,$title){
        $posts = $this->session->get('posts');

        // or ||
        if(!$posts || !isset($posts[$id]))
        {
            throw new NotFoundHttpException('Post not found');
        }

        $html = $this->twig->render(
            'blog/post.html.twig',
            [
                'id' => $id,
                'post' => $posts[$id],
                'title' => $title
            ]
        );
        return new Response($html);
    }
}