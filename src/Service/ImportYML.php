<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use App\Entity\Base;
use App\Entity\Product;
use App\Entity\Photo;
use DOMDocument;

class ImportYML
{
	private $em;
	private $twig;
	private $nameMyStore;
	private $fileDirectory;
	private $importDirectory;
	private $importProductFileName;
	
	public function __construct(EntityManagerInterface $em, Environment $twig, string $nameMyStore, $fileDirectory, $importDirectory, $importProductFileName) 
	{
		$this->em = $em;
		$this->twig = $twig;
		$this->nameMyStore = $nameMyStore;
		$this->fileDirectory = $fileDirectory;
		$this->importDirectory = $importDirectory;
		$this->importProductFileName = $importProductFileName;
    }

    public function createTemplate()
    {
    	$xml = new DOMDocument("1.0", "utf-8");
    	
    	$xml_yml = $xml->createElement("yml_catalog");
    	$date = new \DateTimeImmutable('now');
    	$xml_yml->setAttribute("date", $date->format('Y-m-d H:i:s'));
    	$xml_yml = $xml->appendChild($xml_yml);
    	
    	$xml_shop = $xml->createElement("shop");
    	$xml_shop = $xml_yml->appendChild($xml_shop);

    	$xml_name = $xml->createElement("name", $this->nameMyStore);
    	$xml_name = $xml_shop->appendChild($xml_name);
    	
    	$xml_categories = $xml->createElement("categories");
    	$xml_categories = $xml_shop->appendChild($xml_categories);
    	
    	$bases = $this->em->getRepository(Base::class)->findAll();
  
    	for ($i=0; $i < count($bases); $i++) { 
    		$xml_category = $xml->createElement("category", $bases[$i]->getName());
    		$xml_category->setAttribute("id", $bases[$i]->getId());
    		$xml_categories->appendChild($xml_category);
    	}

    	$xml_offers = $xml->createElement("offers");
    	$xml_offers = $xml_shop->appendChild($xml_offers);

    	$products = $this->em->getRepository(Product::class)->findAll();

    	for ($i=0; $i < count($products); $i++) { 
    		   $xml_offer = $xml->createElement("offer");
    		$xml_offer->setAttribute("id", $products[$i]->getSku());
    		$xml_offers->appendChild($xml_offer);
    		$xml_name = $xml->createElement("name", $products[$i]->getName());
    		$xml_offer->appendChild($xml_name);
    		$xml_picture = $xml->createElement("picture");
    		$xml_offer->appendChild($xml_name);
			$xml_description = $xml->createElement("description");
    		$xml_offer->appendChild($xml_description);
    		$xml_categoryId = $xml->createElement("categoryId");
    		$xml_offer->appendChild($xml_categoryId);
    		$xml_vendor = $xml->createElement("vendor", $products[$i]->getAuthor());
    		$xml_offer->appendChild($xml_vendor);
    		$xml_price = $xml->createElement("price", $products[$i]->getPrice());
    		$xml_offer->appendChild($xml_price);
		}

    	$xml->save($this->importDirectory . $this->importProductFileName);
    }

    public function addOffer(Product $product)
    {
    	$xml = new DOMDocument();
		$xml->load($this->importDirectory . $this->importProductFileName);
		foreach ($xml->getElementsByTagName('offers') as $xml_offers) {
			$product = $this->em->getRepository(Product::class)->find($product); 
			$xml_offer = $xml->createElement("offer");
    		$xml_offer->setAttribute("id", $product->getSku());
    		$xml_offers->appendChild($xml_offer);
    		$xml_name = $xml->createElement("name", $product->getName());
    		$xml_offer->appendChild($xml_name);
    		$xml_picture = $xml->createElement("picture");
    		$xml_offer->appendChild($xml_name);
			$xml_description = $xml->createElement("description");
    		$xml_offer->appendChild($xml_description);
    		$xml_categoryId = $xml->createElement("categoryId");
    		$xml_offer->appendChild($xml_categoryId);
    		$xml_vendor = $xml->createElement("vendor", $product->getAuthor());
    		$xml_offer->appendChild($xml_vendor);
    		$xml_price = $xml->createElement("price", $product->getPrice());
    		$xml_offer->appendChild($xml_price);
		
			$xml->save($this->importDirectory . $this->importProductFileName);		
		}
	}

	public function replaceOffer(Product $product)
	{
		$xml = new DOMDocument();
		$xml->load($this->importDirectory . $this->importProductFileName);
        $product = $this->em->getRepository(Product::class)->find($product); 
        $els = $xml->getElementsByTagName('offer');
        for ($i = $els->length; --$i >= 0; ) {
  			$el = $els->item($i);
  			if ($el->getAttribute('id') == $product->getSku()) {
  				$DOMNodeList = $el->childNodes;
  				if ($DOMNodeList->item(0)->nodeName == 'name' and $DOMNodeList->item(4)->nodeName == 'price') {
  					$DOMNodeList->item(0)->nodeValue = $product->getName();
  					$DOMNodeList->item(4)->nodeValue = $product->getPrice();
  				} 
			}
  		}	
		$xml->save($this->importDirectory . $this->importProductFileName);	
	}

	public function removeOffer($productSku)
	{
		$xml = new DOMDocument();
		$xml->load($this->importDirectory . $this->importProductFileName);
		$els = $xml->getElementsByTagName('offer');
		for ($i = $els->length; --$i >= 0; ) {
  		$el = $els->item($i);
  			if ($el->getAttribute('id') == $productSku) {
      			$el->parentNode->removeChild($el);
  			}
		}
		$xml->save($this->importDirectory . $this->importProductFileName);	
	}
}

