<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserLocation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/users", name="users")
     */
    public function restLocationAction() {
        $repository = $this->getDoctrine()->getRepository(UserLocation::class);
        $users = $repository->findAll();

        $userLocations = [];
        foreach ($users as $user) {
            $userLocations[] = [
                'username' => $user->getUsername(),
                'lat' => $user->getLat(),
                'lon' => $user->getLon()
            ];
        }
        return $this->json($userLocations);
    }

    /**
     * @Route("/addLocation", name="addLocation")
     */
    public function addLocationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $username = $request->request->get('username');
        $lat = $request->request->get('lat');
        $lon = $request->request->get('lon');

        $userLocation = new UserLocation();
        $userLocation
            ->setUsername($username)
            ->setLat($lat)
            ->setLon($lon)
        ;
        $em->persist($userLocation);
        $em->flush();

        return $this->json([
            'username' => $username,
            'lat' => $lat,
            'lon' => $lon
        ]);
    }

}
