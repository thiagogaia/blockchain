<?php

/**
 *  prova de trampo
 */
class Pow
{

  public static function hash($msg): string
  {
    return hash('sha256', $msg);
  }

  public static function isValid($msg, $nonce): bool
  {
    $zeros = '00000';
    return strpos(hash('sha256', $msg . $nonce), $zeros) === 0;
  }

  public static function nonce($msg): string
  {
    $nonce = 0;
    $zeros = '00000';
    while (strpos(hash('sha256', $msg . $nonce), $zeros) !== 0) {
      $nonce++;
    }
    return $nonce;
  }
}

class Block
{
  public string $msg;
  public ?string $previous;
  public string $hash;
  public string $nonce;
  public function __construct(string $msg, ?Block $previous = null)
  {
    $this->msg = $msg;
    $this->previous = $previous ? $previous->hash : null; //Block da blockchain
    $this->mine();
  }

  public function mine(): void
  {
    $info = $this->msg . $this->previous;
    $this->nonce = Pow::nonce($info);
    $hash = Pow::hash($info.$this->nonce);
    $this->hash = $hash;
    // echo $hash;
  }

  public function valid(): bool
  {
    return Pow::isValid($this->msg.$this->previous, $this->nonce);
  }

  public function __toString(): string
  {
    return sprintf("Previous: %s\nNonce: %s\nHash: %s\nMessage: %s", $this->previous, $this->nonce, $this->hash, $this->msg);
  }
}

/* $bloco = new Block('bloco gênesis');
$bloco->mine();
echo $bloco; */

class Blockchain
{
  public array $blocks = [];
  public function __construct($msg) {
    $this->blocks[] = new Block($msg, null);
    // var_dump($this->blocks);
  }
  
  public function add($msg): void
  {
    $this->blocks[] = new Block($msg, $this->blocks[count($this->blocks) - 1]);
  }

  public function __toString(): string
  {
    return implode("\n\n", $this->blocks);
  }

  public function valid(): bool
  {
    foreach($this->blocks as $i => $block) {
      if(!$block->valid()) {
        return false;
      }

      if($i != 0 && $block->previous != $this->blocks[$i-1]->hash) {
        return false;
      }

      return true;
    }
  }
}

$blockchain = new Blockchain('bloco gênesisssssss');
// var_export($blockchain);
$blockchain->add('Proximo bloco');
$blockchain->add('Informação sigilosa');
$blockchain->add('Comitê toledo');
$blockchain->add('Se fosse o Git');
print $blockchain ."\n";
var_export($blockchain->valid());
// var_export($blockchain);