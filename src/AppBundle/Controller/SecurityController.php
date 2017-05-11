<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends Controller
{
    /**
     * Get user info.
     * @Route("/profile", name="user_profile")
     */
    public function profileAction()
    {
        if($this->getUser()->getId()){

            $em = $this->getDoctrine()->getEntityManager();
            $conn = $em->getConnection();

            $myId = $this->getUser()->getId();
            $myUsername = $this->getUser()->getUsername();

            // speakers

            $query_speakers = "SELECT speaker.id, speaker.name, speaker.confirmed, speakers.conference_id, speakers.speaker_id, conference.id FROM speaker INNER JOIN speakers ON speakers.speaker_id = speaker.id INNER JOIN conference ON conference.id = speakers.conference_id WHERE name = '$myUsername' AND confirmed = '1'";

            $stmt1 = $conn->prepare($query_speakers);
            $stmt1->execute();

            $speakers = $stmt1->fetchAll();

            // visits

            $query_visits = "SELECT lecture_visits.conference_id, lecture_visits.lecture_id, lecture_visits.must_visit, lecture.title FROM lecture_visits INNER JOIN lecture ON lecture.id = lecture_visits.lecture_id WHERE lecture_visits.visitor_id = '$myId'";

            $stmt2 = $conn->prepare($query_visits);
            $stmt2->execute();

            $visits = $stmt2->fetchAll();

            return $this->render('default/profile.html.twig', array(
                'user'      => $this->getUser(),
                'speakers' => $speakers,
                'visits' => $visits,
            ));
        } else {
            return $this->redirectToRoute('user_login');
        }
    }

    /**
     * @Route("/login", name="user_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {

        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return [
            'last_username' => $lastUsername,
            'error' => $error
        ];
    }

    /**
     * @Route("/login_check", name="user_check")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function checkAction(Request $request)
    {
        return $this->redirectToRoute('user_login');
    }

    /**
     * @Route("/logout", name="user_logout")
     */
    public function logoutAction()
    {
    }

    /**
     * @Route("/register", name="user_register")
     * @Template()
     * @param Request $request
     *
     * @return array
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles([ $user->getDefaultRole() ]);

            $encoder = $this->get('security.password_encoder');

            $user->setPassword(
                $encoder->encodePassword($user, $user->getPasswordRaw())
            );

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            return $this->redirectToRoute('homepage');
        }

        return [
            'form' => $form->createView()
        ];
    }
}
