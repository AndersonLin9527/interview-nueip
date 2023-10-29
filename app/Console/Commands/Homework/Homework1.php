<?php

namespace App\Console\Commands\Homework;

use Illuminate\Console\Command;

class Homework1 extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:homework1';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Homework1 Lily\'s Homework';

  private bool $showProcess = false;

  /**
   * Execute the console command.
   * @return int
   * php artisan app:homework1
   */
  public function handle(): int
  {
    $inputArraySize = '';
    // for testing
//    $inputArraySize = 99;
    while ($inputArraySize == '') {
      $inputArraySize = $this->ask('Array size ? ex.4');
      if ($inputArraySize < 1 || $inputArraySize > 10000) {
        $this->error('Invalid $arraySize : ' . $inputArraySize);
        $inputArraySize = '';
      }
    }

    $inputArray = '';
    do {
      $inputArrayText = trim($this->ask('Array Values ? ex.7 15 12 3'));
      // for testing
//      $inputArrayText = '333 666 333 888 222 111';
//      $inputArrayText = '99 88 1 2 3 3 21 2 1 77 66 88 99';
      if ($inputArrayText == '') {
        $this->error('Array Values cannot be empty.');
        continue;
      }
      $inputArray = explode(' ', $inputArrayText);
      $inputArray = array_slice($inputArray, 0, $inputArraySize);
      $inputArrayValues = array_filter($inputArray, fn($inputArrayValue) => $inputArrayValue <= 0 || $inputArrayValue > 2000000000);
      if (count($inputArrayValues) > 0) {
        $inputArray = '';
        foreach ($inputArrayValues as $inputArrayValue) {
          $this->error('Invalid Array Value : ' . $inputArrayValue);
        }
      }
    } while ($inputArray == '');

    $correctArray = $inputArray;
    sort($correctArray);

    $swapCount = 0;
    $ignoreKeys = [];
    while ($inputArray != $correctArray) {
      $tempArray = $inputArray;
      if (!empty($ignoreKeys)) {
        $tempArray = array_filter($tempArray, fn($tempKey) => !in_array($tempKey, $ignoreKeys), ARRAY_FILTER_USE_KEY);
        if (count($tempArray) <= 1) {
          break;
        }
      }
      $swapValue = min($tempArray);
      $swapKey = $this->_arraySearchLast($swapValue, $tempArray);

      $toSwapKey = array_key_first($tempArray);
      $toSwapValue = $inputArray[$toSwapKey];
      $ignoreKeys[] = $toSwapKey;

      if ($swapValue == $toSwapValue) {
//        if ($this->showProcess) {
//          $this->line('--------------------------------------------------------------------------------------------');
//          $this->warn("Pass swap value $swapValue => $toSwapValue");
//          $this->warn("Pass swap key $swapKey => $toSwapKey");
//        }
        continue;
      }

      if ($this->showProcess) {
        $this->line('--------------------------------------------------------------------------------------------');
        $this->info("DO swap value $swapValue => $toSwapValue");
        $this->info("DO swap key $swapKey => $toSwapKey");
        $this->line('before swap $inputArray : ');
        dump($inputArray);
      }

      // swap
      $temp = $inputArray[$toSwapKey];
      $inputArray[$toSwapKey] = $swapValue;
      $inputArray[$swapKey] = $temp;
      $swapCount++;

      if ($this->showProcess) {
        $this->line('$swapCount : ' . $swapCount);
        $this->line('after swap $inputArray : ');
        dump($inputArray);
      }
    }

    if ($this->showProcess) {
      $this->line('============================================================================================');
      $this->line('Final Result : ');
      dump($inputArray);
      $this->line('============================================================================================');
    }
    $this->line($swapCount);
    return $swapCount;
  }

  private function _arraySearchLast($searchValue, $searchArray): bool|int|string
  {
    $reverseArray = $searchArray;
    asort($reverseArray);
    $reverseArray = array_reverse($reverseArray, true);
    return array_search($searchValue, $reverseArray);
  }

}
