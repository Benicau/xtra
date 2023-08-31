<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Invoices;
use App\Repository\InvoicesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfController extends AbstractController
{

    /**
     * Controller action for generating a PDF of the latest invoice
     *
     * @param InvoicesRepository $invoiceRepository
     * @return Response
     */
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
            'id'    => $latestInvoice->getId(),
            'date'  => $brusselsDate->format('d/m/Y H:i'),
            'texte' => $latestInvoice->getTexte(),
            'total' => $latestInvoice->getTotal(),
            'name' => $latestInvoice->getClient()
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
    
  /**
     * Controller action for generating a PDF of a specific invoice in the admin panel
     *
     * @param Invoices $invoice
     * @return Response
     */
    #[Route('/admin/compta/pdf/{id}', name: 'app_pdf_admin')]
    public function generateInvoicePdf(Invoices $invoice): Response
    {
        $brusselsTimeZone = new \DateTimeZone('Europe/Brussels');
        $brusselsDate = $invoice->getDate()->setTimezone($brusselsTimeZone);

        $data = [
            'id'    => $invoice->getId(),
            'date'  => $brusselsDate->format('d/m/Y H:i'),
            'texte' => $invoice->getTexte(),
            'total' => $invoice->getTotal(),
            'name' => $invoice->getClient()

        ];

        $html = $this->renderView('pdf/index.html.twig', [
            'data' => $data,
        ]);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'roboto'); 
        $pdfOptions->set('isRemoteEnabled', true); 

        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait'); 
        $dompdf->render();

        $id = strval($invoice->getId());
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

