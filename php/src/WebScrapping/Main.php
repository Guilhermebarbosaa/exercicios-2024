<?php

namespace Chuva\Php\WebScrapping;
 
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use PharIo\Manifest\Author;

/**
 * Runner for the Webscrapping exercice.
 */
class Main {


  public static function run(): void {

    $dom = new \DOMDocument('1.0', 'utf-8');
    @$dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    
    $dadosPlanilha = (new Scrapper())->scrap($dom);

    $caminhoPlanilha = 'C:\desenv\projeto\PLANILHA\dados_extraidos.xlsx';
    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile($caminhoPlanilha);


    $cabecalho = [
      WriterEntityFactory::createCell('ID'),
      WriterEntityFactory::createCell('Title'),
      WriterEntityFactory::createCell('Type'),
   ];

   $qtdAuthors = 0;
   foreach ($dadosPlanilha as $row) {
      if  ($qtdAuthors<count($row->authors)){
        $qtdAuthors = count($row->authors);
      }
   }
   for ($i = 1; $i <= $qtdAuthors; $i++) {
    $cabecalho[]= WriterEntityFactory::createCell ('Author '. $i);
    $cabecalho[]=  WriterEntityFactory::createCell('Author '.$i. ' Institution');
  }
    $singleRow = WriterEntityFactory::createRow($cabecalho);
    $writer->addRow($singleRow);

    foreach ($dadosPlanilha as $paper) {
    $cells = [
      WriterEntityFactory::createCell($paper->id),
      WriterEntityFactory::createCell($paper->title),
      WriterEntityFactory::createCell($paper->type),
    ]; 
    foreach ($paper->authors as $var ){
      $cells[] = writerEntityFactory::createCell($var->name);
      $cells[] = writerEntityFactory::createCell($var->institution);

    }
    $singleRow = WriterEntityFactory::createRow($cells);
    $writer->addRow($singleRow);
  }
   $writer->close();
 
  }

}