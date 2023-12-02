<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CubeConundrumController extends Controller
{
    protected $file;
    protected $total_cubes = [
        "  red" => 12,
        "  green" => 13,
        "  blue" => 14
    ];

    public function importFile(): void
    {
        $this->file = File::get("D:\ADVENT_OF_CODE\DAY_1\advent_of_code\/resources\/files\day_2/input.txt");
    }

    public function returnResponse()
    {

        $this->importFile();
        $file = explode("\n", $this->file);
        $games = [];
        $final_games = [];
        $result_array = [];

        foreach ($file as $key => $value) {
            $games[$key] = explode(":", $value);
        }

        foreach ($games as $key => $value) {
            if (count($games[$key]) > 1) {
                $games[$key][1] = explode(";", $value[1]);
            }
        }

        foreach ($games as $key => $value) {
            if (count($value) > 1) {
                foreach ($value[1] as $k => $v) {
                    $final_games[$this->transformGameId($value[0])][$k] = explode(",", $v);
                }
            }
        }

        foreach ($final_games as $key => $value) {
            foreach ($value as $k => $v) {
                $result_array[$key][$k] = $this->checkPossibleGames($v);
            }
        }

        $this->checkFinalResults($result_array);
    }

    public function transformCubes($game)
    {
        return explode(",", $game);
    }

    public function transformGameId($gameID)
    {
        return Str::replace("Game ", "", $gameID);
    }

    public function cleanValues($value)
    {
        // $value = "4 blue";
        $replaced_array = [];
        $replaced_val = $value;
        // $replaced_val = $value;


        $replacables = [
            'green' => 0,
            'blue' => 1,
            'red' => 2,
            ' ' => 3,
        ];

        foreach ($replacables as $k => $v) {

            $replaced_val = Str::replace($k, '', $replaced_val);
            $replaced_array = [(int) $replaced_val,  Str::replace($replaced_val, '', $value)];
        }

        // dd($replaced_array);
        return $replaced_array;
    }

    public function checkPossibleGames($game)
    {

        if ($game == "") {
            return false;
        }

        $count = [
            "  red" => null,
            "  blue" => null,
            "  green" => null
        ];

        foreach ($game as $key => $value) {
            if ($key != "id" && $key != "") {
                $check_var = $this->cleanValues($value);
                $count[$check_var[1]] = $check_var[0];
            }
        }


        foreach ($count as $key => $value) {
            // dd($count[$key]);
            // dd($this->total_cubes[$key]);

            // ($count[$key] > $this->total_cubes[$key]);

            if ($count[$key] > $this->total_cubes[$key]) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function checkFinalResults($games)
    {
        $playableIDs = [];
        $finalCount = count($games);


        foreach ($games as $key => $value) {
            foreach ($value as $k => $v) {
                if ($v == false) {
                    array_push($playableIDs, $key);
                }
            }
        }

        // dd($games);

        // $playableIDs = array_unique($playableIDs);


        dd($playableIDs);
    }
}
