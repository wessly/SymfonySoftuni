<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $conn = $em->getConnection();

        $myUsername = $this->getUser()->getUsername();

        $query = "SELECT speaker.id, speaker.name, speaker.confirmed, speakers.conference_id, speakers.speaker_id, conference.id FROM speaker INNER JOIN speakers ON speakers.speaker_id = speaker.id INNER JOIN conference ON conference.id = speakers.conference_id WHERE name = '$myUsername' AND confirmed = '1'";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $invitations = $stmt->fetchAll();

        return $this->render('default/index.html.twig', array('invitations' => $invitations));
    }

    /**
     * @Route("/speakers/update", name="speakers_update")
     * @Method("POST")
     *
     * @param Request $request
     * @return Response
     */
    public function updateInvitationProcess(Request $request)
    {
        $speaker_id = $request->request->get('speaker_id');
        $speaker_status = $request->request->get('speaker_status');

        if (is_numeric($speaker_id) && is_numeric($speaker_status)) {
            $em = $this->getDoctrine()->getManager();
            $speaker_update = $em->getRepository('AppBundle:Speaker')->find($speaker_id);

            $speaker_update->setConfirmed($speaker_status);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/lectures/visit", name="lectures_visit")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateLecturesVisit(Request $request)
    {
        $conference_id = $request->request->get('conference_id');
        $lecture_id = $request->request->get('lecture_id');
        $visit_type = $request->request->get('visit_type');

        $em = $this->getDoctrine()->getEntityManager();
        $conn = $em->getConnection();

        $myId = $this->getUser()->getId();

        $query = "SELECT * FROM lecture_visits WHERE conference_id = '$conference_id' AND lecture_id = '$lecture_id' AND visitor_id = '$myId'";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $check = $stmt->fetchAll();

        if ($check) {
            $query_update = "UPDATE lecture_visits SET must_visit = '$visit_type' WHERE conference_id = '$conference_id' AND lecture_id = '$lecture_id' AND visitor_id = '$myId'";

            $stmt = $conn->prepare($query_update);
            $stmt->execute();
        } else {
            $query_insert = "INSERT INTO lecture_visits (conference_id, lecture_id, visitor_id, must_visit) VALUES ('$conference_id', '$lecture_id', '$myId', '$visit_type')";

            $stmt = $conn->prepare($query_insert);
            $stmt->execute();
        }

        return $this->redirectToRoute("user_profile");
    }
}
