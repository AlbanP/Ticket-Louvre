<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CalculVisitors;
use App\Service\CheckNbVisitor;

class TicketAjaxController extends Controller
{
    /**
     * @Route({"en" : "/calculRate", "fr" : "/calculRate"}, name="ajax_rate", requirements={"_locale": "en|fr"})
    */
    public function calculRateAction(Request $request, CalculVisitors $calculVisitor, CheckNbVisitor $checkNbVisitor)
    {
        if ($request->isMethod('POST')) {
            $birthday = new \DateTime($request->get('birthday'));
            $reduction = $request->get('reduction');
            $dateVisit = new \DateTime($request->get('dateVisit'));
            $nbVisitor = $request->get('nbVisitor');

            $price = $calculVisitor->calculRate($birthday, $reduction, $dateVisit);
            $nbVisitorDay = $checkNbVisitor->calculNbVisitorDay($dateVisit, $nbVisitor);

            return $this->json(array(
                'price' => $price,
                'birthday' => $birthday,
                'nbVisitorsDay' => $nbVisitorDay
            ));
        }

        return new Response("Erreur : Is not Ajax request", 400);
    }
}
