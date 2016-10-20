<?php
// src/Blogger/BlogBundle/Controller/PageController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// Importa el nuevo espacio de nombres
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;

/**
*/
class PageController extends Controller {

	public function indexAction() {
        $em = $this->getDoctrine()
                   ->getEntityManager();

        // Recuperar el repositorio y llama al método getLatestBlogs
        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
                    ->getLatestBlogs();

        return $this->render('BloggerBlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs
        ));
    }    

	public function aboutAction() {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
	}

	public function contactAction() {
		$enquiry = new Enquiry();
		$form = $this->createForm(new EnquiryType(), $enquiry);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->handleRequest($request);

			if ($form->isValid()) {
				$transport = \Swift_SmtpTransport::newInstance()
				            ->setUsername('roneypc@hotmail.com')->setPassword('R0b3rt05')
				            ->setHost('smtp-mail.outlook.com')
				            ->setPort(587)->setEncryption('tls');

				$mailer = \Swift_Mailer::newInstance($transport);
				        
				$message = \Swift_Message::newInstance()
				->setSubject('Contact enquiry from symblog')
				->setFrom('roneypc@hotmail.com')
				->setTo($this->container->getParameter('blogger_blog.emails.contact_email'))
            	->setBody($this->renderView('BloggerBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));				
				        
				$result = $mailer->send($message);

				// $this->get('session')->setFlash('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

				// Redirige - Esto es importante para prevenir que el usuario
				// reenvíe el formulario si actualiza la página
				return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));
			}
		}

		return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
		'form' => $form->createView()
		));
    }
}