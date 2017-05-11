<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Conference;
use AppBundle\Entity\Lecture;
use AppBundle\Entity\Speaker;
use AppBundle\Form\ConferenceType;
use AppBundle\Form\LectureType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class ConferenceController extends Controller
{
    /**
     * @Route("/conferences", name="conferences_all")
     * @Method("GET")
     * @return Response
     */
    public function showAll()
    {
        $conferences = $this->getDoctrine()
            ->getRepository('AppBundle:Conference')
            ->findAll();

        return $this->render('conference/all.html.twig', array('conferences' => $conferences));
    }

    /**
     * @Route("/conference/{id}", name="conference")
     * @Method("GET")
     * @param Conference $conference
     * @return Response
     */
    public function showSpecific(Conference $conference)
    {
        $thisConference = $this->getDoctrine()
            ->getRepository('AppBundle:Conference')
            ->find($conference);

        if($thisConference->getStatus() == 0
            && $thisConference->getOwner()->getId() != $this->getUser()->getId()
            && !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute("conferences_all");
        }

        return $this->render('conference/specific.html.twig', array('conference' => $thisConference));
    }

    /**
     * @Route("/conferences/add", name="conference_add")
     * @Method("GET")
     */
    public function addAction()
    {
        $form = $this->createForm(ConferenceType::class);

        return $this->render("conference/new.html.twig", array('new_form' => $form->createView()));
    }

    /**
     * @Route("/conferences/add", name="conference_add_process")
     * @Method("POST")
     *
     * @param Request $request
     * @return Response
     */
    public function addActionProcess(Request $request)
    {
        $conference = new Conference();

        $conference->setOwner($this->getUser());

        $form = $this->createForm(
            ConferenceType::class,
            $conference
        );

        $form->handleRequest($request);

        if($form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($conference);
            $em->flush();

            $lecture1 = new Lecture();

            $lecture1->setTitle('First lecture');
            $lecture1->setStart('13:37');
            $lecture1->setEnd('14:37');
            $lecture1->setActive(0);

            $conference->getLectures()->add($lecture1);

            $lecture2 = new Lecture();

            $lecture2->setTitle('Second lecture');
            $lecture2->setStart('13:37');
            $lecture2->setEnd('14:37');
            $lecture2->setActive(0);

            $conference->getLectures()->add($lecture2);

            $lecture3 = new Lecture();

            $lecture3->setTitle('Third lecture');
            $lecture3->setStart('13:37');
            $lecture3->setEnd('14:37');
            $lecture3->setActive(0);

            $conference->getLectures()->add($lecture3);

            $speaker1 = new Speaker();

            $speaker1->setName('Example speaker');
            $speaker1->setConfirmed("0");

            $conference->getSpeakers()->add($speaker1);

            $em->persist($conference);
            $em->flush();

            return $this->redirectToRoute("conferences_all");
        }

        return $this->render("conference/new.html.twig",
            [
               'new_form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/conferences/edit/{id}", name="conference_edit")
     * @Method("GET")
     * @param Conference $conference
     * @return Response
     */
    public function editAction(Conference $conference)
    {
        $em = $this->getDoctrine()->getManager();
        $viewConference = $em->getRepository('AppBundle:Conference')->find($conference);

        if($viewConference->getOwner()->getId() != $this->getUser()->getId()
            && !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute("conferences_all");
        }

        $form = $this->createForm(ConferenceType::class, $conference);

        return $this->render("conference/edit.html.twig", array('edit_form' => $form->createView()));
    }

    /**
     * @Route("/conferences/edit/{id}", name="conference_edit_process")
     * @Method("POST")
     *
     * @param Conference $conference
     * @param Request $request
     * @return Response
     */
    public function editActionProcess(Conference $conference, Request $request)
    {

        $form = $this->createForm(
            ConferenceType::class,
            $conference
        );

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($conference);
            $em->flush();

            return $this->redirectToRoute("conferences_all");
        }

        return $this->render("conference/edit.html.twig",
            [
                'edit_form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/conferences/delete/{id}", name="conference_delete_process")
     * @Method("POST")
     *
     * @param Conference $conference
     * @return Response
     */
    public function deleteActionProcess(Conference $conference)
    {
        $em = $this->getDoctrine()->getManager();

        $viewConference = $em->getRepository('AppBundle:Conference')->find($conference);

        if($viewConference->getOwner()->getId() != $this->getUser()->getId()
            && !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute("conferences_all");
        }

        $em->remove($conference);
        $em->flush();

        return $this->redirectToRoute("conferences_all");
    }
}
