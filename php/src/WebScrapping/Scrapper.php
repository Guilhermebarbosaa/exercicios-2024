<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
   
    $papers = [];

    foreach($dom->getElementsByTagName('a') as $xpto) {

      if ($xpto->getAttribute('class') == 'paper-card p-lg bd-gradient-left') {
        $title = '';
        $tipo = '';
        $id = '';
       
        foreach($xpto->getElementsByTagName('div') as $div) {
          if ($div->getAttribute('class') == 'volume-info') {
            $id = $div->nodeValue;
          }
        }

        foreach($xpto->getElementsByTagName('h4') as $h4) {
          $title = $h4->nodeValue; 
        }

        foreach($xpto->getElementsByTagName('div') as $tagr) {
          if ($tagr->getAttribute('class') == 'tags mr-sm') {
            $tipo = $tagr->nodeValue;
          }
        }

        $paper = new Paper($id, $title, $tipo);
        
        foreach ($xpto->getElementsByTagName('div') as $authors) {
          if ($authors->getAttribute('class') == 'authors') {
      
            foreach ($authors->getElementsByTagName('span') as $span) {
              $paper->authors[] = new Person(str_replace(';', '', $span->nodeValue), $span->getAttribute('title'));
            }
          }
        }
        
        $papers[] = $paper;       
      }    
    } 

    return $papers;
  }

}
