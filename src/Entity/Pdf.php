<?php

namespace App\Entity;

use FPDF;

class Pdf extends FPDF
{   
    private array $products;

    private string  $devise;
        

    /**
     * @var array $products contains an product result repository.
     */
    private $nbPage;

    /**
     * @var array $customer an array that contain 2 array with on each Client object.
     * One for delivery info customer and another for billing info customer.
     */
    private array $customer ;

    public function __construct(array $products = [], array $customer)
    {
        $this->devise = iconv('UTF-8', 'windows-1252', '€');
        parent::__construct();
        $this->products = $products;
        $this->customer = $customer;
        $this->body($this->products);
    }

    public function header()
    {
        $this->Image( ROOT . DS . 'public/image/fountain-pen.png' , 80,  12.5, 5 );
        $this->Image( ROOT . DS . 'public/image/fountain-pen.png' , 125,  12.5, 5 );
        
        $this->SetFont('Arial','B',15);
        $this->Cell(80); // Décalage à droite
        $this->Cell(30,10,'E-shop', 1, 0,'C'); // Saut de ligne
        $this->Ln(20);
    }

    private function body()
    { 
        $this->AddPage();

        $this->SetFont('Arial','B', 24);
        $this->Cell( 190 , 10,'Facture de vos Achats', 1 , 1, 'C');
        $this->Ln(10.5);

        $this->delivery();
        $this->billing();
           
        $this->SetFont('Arial','U', 12);
        $this->Cell(47.5, 10, 'Numero article', 1, 0, 'C');
        $this->Cell(47.5, 10, 'Nom', 1, 0, 'C');
        $this->Cell(47.5, 10, 'Quantite', 1, 0, 'C');
        $this->Cell(47.5, 10, 'Total en euro', 1, 1, 'C');

        $totalPurchasePrices = $this->insertRow($this->products);

        $this->Ln(5);

        $this->Cell(142.5, 10, 'Total en euro', 1, 0, 'L');
        $this->Cell(47.5,  10, $totalPurchasePrices . ' ' . $this->devise, 1, 0, 'C');

        $this->Output();
    }

    public function Footer()
    {
        // Positionnement à 1,5 cm du bas
        $this->SetY(-15);
        // Police Arial italique 8
        $this->SetFont('Arial','I',8);
        // Numéro de page
        $this->Cell( 0 ,10 ,'Page '.  $this->PageNo() . '/{$nb}' , 0, 0, 'C');
    }

    /**
     * Customer delivery info
     */
    private function delivery() : void
    {
        $customer = $this->customer['delivery'];

        $this->SetFont('Arial','U', 12);
        
        $this->Cell(40, 10, 'A livrer a :', 0 , 0, 'L');
        $this->Ln(10.5);

        $this->SetFont('Arial','I', 8);

        $this->Cell(40, 10,  $customer->getEmail(),0 , 0, 'L');
        $this->Ln(5);

        $this->Cell(40, 10, strtoupper($customer->getName()) . ' ' . $customer->getSurname() ,0 , 0, 'L');
        $this->Ln(5);

        $this->Cell(40, 10,  $customer->getSurname(),0 , 0, 'L');
        $this->Ln(5);
        
        $this->Cell(40, 10,  $customer->getAddress(),0 , 0, 'L');
        $this->Ln(5);
        
        $this->Cell(40, 10,  $customer->getZip(),0 , 0, 'L');
        
        $this->setY(35);
        
        $this->Ln(15.5);
    }

    /**
     * Customer billing info
     */
    private function billing() : void
    {
        $customerBill = $this->customer['customer'];
        
        $this->SetFont('Arial','U', 12);

        $this->Cell(190, 10, 'Moyen de paiement :', 0, 0,'R');
        $this->Ln(10.5);

        $this->SetFont('Arial','U', 8);

        $this->Cell(190, 10,  'Facture numero : ' . $customerBill->getIdBilling(), 0, 0,'R');
        $this->Ln(5);

        $this->SetFont('Arial','I', 8);

        $this->Cell(190, 10, strtoupper($customerBill->getName()) . ' ' . $customerBill->getSurname() , 0, 0,'R');
        $this->Ln(5);
        
        $this->Cell(190, 10, $customerBill->getcreditCardNumberFormated() , 0, 0,'R');
        $this->Ln(5);

        $this->Cell(190, 10,  $customerBill->getExpirationDateFormated(), 0, 0,'R');
        $this->Ln(5);
        
        $this->Ln(15.5);
    }
    
    private function insertRow() : float
    {

        foreach($this->products as $product)
        {
            $price = str_replace(',','.', $product['product']['product_price']);
            
            $totalPerProduct = $price * $product['quantity'];
            $totalPurchasePrices[] = $totalPerProduct;

            $this->SetFont('Arial','I', 10);
            $this->Cell(47.5, 10, $product['product']['product_id'],1 , 0, 'C');
            $this->Cell(47.5, 10, $product['product']['product_name'], 1, 0, 'C');
            $this->Cell(47.5, 10, $product['quantity'], 1, 0, 'C');
            $this->Cell(47.5, 10, $totalPerProduct . ' ' . $this->devise , 1, 1, 'C');
        }

        return array_sum($totalPurchasePrices);
    }

    /**
     * Set the value of product
     *
     * @return  self
     */ 
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }
}
