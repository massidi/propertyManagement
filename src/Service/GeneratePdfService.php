<?php


namespace App\Service;


use Dompdf\Css\Stylesheet;
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
//        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsHtml5ParserEnabled(true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
//        $css=new Stylesheet($dompdf);

//        $publicDirectory = $this->get('kernel')->getProjectDir() . '/public';
//
//        $css->load_css_file();
//        $dompdf->setCss(new Stylesheet($dompdf));
//        $dompdf->setHttpContext(fopen(__DIR__ . "assets/invoice/invoice.css", 'r',''));

//        $dompdf->getOptions()->setChroot($this->projectDir . DIRECTORY_SEPARATOR . 'www');
//        $dompdf->setOptions("isHtml5ParserEnabled", true);

        // Retrieve the HTML generated in our twig file
//        $html = $this->twig->render('default/mypdf.html.twig', [
//            'title' => "Welcome to our PDF Test"
//        ]);
//        $dompdf->setProtocol();

        // Load HTML to Dompdf
        $dompdf->loadHtml($html->getContent());

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