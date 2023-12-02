<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class InputController extends Controller
{
    protected $file;

    public function returnResponse()
    {
        $split_array = [];

        $this->importFile();

        $file = $this->file;
        $file = explode("\n", $file);

        foreach ($file as $key => $value) {
            foreach (str_split($value) as $index => $char) {
                if (preg_match('/\d/', $char)) {
                    $split_array[$key][$index] = (int) $char;
                    //replaced with \n we get strings in each line which we can work with such as below
                    // dqfournine5four2jmlqcgv
                    // 7ggzdnjxndfive
                    // twofive4threenine
                    // dttwonezbgmcseven5seven
                    // 5vsrcnine
                    //then this regex extracts numbers from the string
                }
            }
        }

        foreach ($split_array as $key => $value) {
            foreach ($value as $index => $char) {
                if (count($value) > 1) {
                    $new_val = $value[$index] . end($value);
                    $split_array[$key] = [];
                    $split_array[$key] = (int) $new_val;
                    //simply if theres more than one item in the array we get the first and last

                    break;
                } else {
                    $split_array[$key] = [];
                    $split_array[$key] = (int) ($value[$index] . $value[$index]);
                    //else we just return the same digit doubly
                }
            }
        }

        //then we sum it all up
        return array_sum($split_array);
        // return $file;
        // return $split_array;
    }

    public function importFile(): void
    {
        $this->file = File::get("D:\ADVENT_OF_CODE\DAY_1\advent_of_code\/resources\/files\input.txt");
    }
}
