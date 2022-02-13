<?php


namespace App\Service;


use Dompdf\Options;
use Dompdf\Dompdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;



class GeneratePdfService
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var Pdf
     */
    private $pdf;

    public function __construct(Environment  $twig,Pdf  $pdf)
    {
        $this->twig = $twig;
        $this->pdf = $pdf;
    }

    public function getPdf($html,$title)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
//        $html = $this->twig->render('default/mypdf.html.twig', [
//            'title' => "Welcome to our PDF Test"
//        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("$title.pdf", [
            "Attachment" => true
        ]);
    }

    public function pdfAction($html,$title)
    {
//        $html = $this->renderView('MyBundle:Foo:bar.html.twig', array(
//            'some'  => $vars
//        ));
//        $options = [
//            'margin-top' => 2,
//            'margin-bottom' => 2,
//            'margin-left' => 2,
//            'margin-right' => 3,
//        ];
//        $this->pdf->setOptions($options);



        return new Response(
            $this->pdf->getOutputFromHtml($html),
            200,
            array(
                "Content-Type"=>"PDF",
                "Content-Disposition"=>'inline;filename="'.$title.'.pdf"'

            )
        );
    }

}