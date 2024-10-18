<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\Map\InfoWindow;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\Point;

class MapController extends AbstractController
{
    public function __construct(private readonly HttpClientInterface $httpClient){}

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/map', name: 'app_map')]
    public function index(): Response
    {
        $map = (new Map())
            // Explicitly set the center and zoom
            ->center(new Point(48.856614, 2.352222))
            ->zoom(12)
            // Or automatically fit the bounds to the markers
//            ->fitBoundsToMarkers()
        ;

        $files = $this->httpClient->request('GET', 'https://www.data.gouv.fr/fr/datasets/r/1d61b1f4-4730-4dfa-aa44-34220f67f493');
         $data = [];
        try {
            $data = json_decode($files->getContent(), true);
//            dd($data);
        } catch (ClientExceptionInterface $e) {
            echo $e->getMessage();
        } catch (RedirectionExceptionInterface $et) {
            echo $et->getMessage();

        } catch (ServerExceptionInterface $em) {
            echo $em->getMessage();
        } catch (TransportExceptionInterface $ey) {
            echo $ey->getMessage();
        }
//        $data = json_decode($files->getContent(), true);
//        dd($data['point_geo']);
        foreach ($data as $record) {
            $map->addMarker(new Marker(
                position: new Point($record['point_geo']['lat'], $record['point_geo']['lon']),
                title: '<b>'.$record['nom_site'].'</b>',
                infoWindow: new InfoWindow(
                    headerContent: '<b>'.$record['nom_site'].' '. $record['start_date'].' au '.$record['end_date'].'</b>',
                    content: $record['sports']
                )
            ));
        }

        return $this->render('map/index.html.twig', [
            'map' => $map,
        ]);
    }
}
