<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controlador del Blog.
 */
class BlogController extends Controller {
    /**
     * Muestra una entrada del blog
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $blog = $em->getRepository('BloggerBlogBundle:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        $comments = $em->getRepository('BloggerBlogBundle:Comment')
                   ->getCommentsForBlog($blog->getId());
        /*
        $commentsByUser = $em->getRepository('BloggerBlogBundle:Comment')
                   ->getCommentsByUser();

        var_dump($commentsByUser);*/

        return $this->render('BloggerBlogBundle:Blog:show.html.twig', array(
            'blog'      => $blog,
            'comments'  => $comments
        ));
    }
}