<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PhaseTwoController extends Controller
{
    protected $file;

    protected $complex_digits = [
        'ten'   => '10',
        'nine'  => '9',
        'eight' => '8',
        'seven' => '7',
        'six'   => '6',
        'five'  => '5',
        'four'  => '4',
        'three' => '3',
        'two'   => '2',
        'one'   => '1',
    ];

    protected $split_array = [];
    protected $final_array = [];

    public function importFile(): void
    {
        $this->file = File::get("D:\ADVENT_OF_CODE\DAY_1\advent_of_code\/resources\/files\input.txt");
    }

    public function returnResponse()
    {

        $this->importFile();

        $file = explode("\n", $this->file);


        foreach ($file as $key => $value) {
            $this->split_array[$key] = $this->checkDigits($value);
            //replaced with \n we get strings in each line which we can work with such as below
            // dqfournine5four2jmlqcgv
            // 7ggzdnjxndfive
            // twofive4threenine
            // dttwonezbgmcseven5seven
            // 5vsrcnine
        }

        foreach ($this->split_array as $key => $value) {
            foreach (str_split($value) as $index => $char) {
                if (preg_match('/\d/', $char)) {
                    $this->final_array[$key][$index] = (int) $char;
                    //this regex extracts numbers from the string
                }
            }
        }

        foreach ($this->final_array as $key => $value) {
            foreach ($value as $index => $char) {
                if (count($value) > 1) {
                    $new_val = $value[$index] . end($value);
                    $this->final_array[$key] = [];
                    $this->final_array[$key] = (int) $new_val;
                    //simply if theres more than one item in the array we get the first and last
                    break;
                } else {
                    $this->final_array[$key] = [];
                    $this->final_array[$key] = (int) ($value[$index] . $value[$index]);
                    //else we just return the same digit doubly
                }
            }
        }
        //then we sum it all up
        return array_sum($this->final_array);
    }

    public function checkDigits($data)
    {
        $replaced = null;

        foreach ([$data] as $key => $value) {
            foreach ($this->complex_digits as $k => $v) {
                $temp_value = $value;
                $split_val = str_split($k);
                $replaced = Str::replace($k, $split_val[0] . $this->complex_digits[$k] . end($split_val), Str::replace($k, $split_val[0] . $k . end($split_val), $temp_value));
                //initially replacing first and last word of digits for cases such as oneight as my prev version would make it as one and discard the eight
                //now it makes it as such oonee which is replaced to o1eight -> o1e8t then we get 1 and 8 
                $value = $replaced;
            }
        }

        return $replaced;
    }
}
