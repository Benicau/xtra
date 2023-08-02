<?php

namespace App\Controller;

use App\Repository\InvoicesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends AbstractController
{
    #[Route('/caisse/pdf', name: 'app_pdf')]
    public function index(InvoicesRepository $invoiceRepository): Response
    {
       
        $latestInvoice = $invoiceRepository->findLatest();
        if (!$latestInvoice) {
            throw $this->createNotFoundException('Aucune facture trouvée.');
        }

        $brusselsTimeZone = new \DateTimeZone('Europe/Brussels');
        $brusselsDate = $latestInvoice->getDate()->setTimezone($brusselsTimeZone);


        $data = [
            'id'         => $latestInvoice->getId(),
            'date'         => $brusselsDate->format('d/m/Y H:i'),
            'texte' => $latestInvoice->getTexte(),
            'total'        => $latestInvoice->getTotal()
        ];

        $html = $this->renderView('pdf/index.html.twig', [
            'data' => $data,
        ]);
        
        $id = strval($latestInvoice->getId());
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'roboto'); 
        $pdfOptions->set('isRemoteEnabled', true); 

        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait'); 

       
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="facture'.$id.'.pdf"'
            ]
        );
    }
}
