<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Revolution\Google\Sheets\Sheets;
// use Sheets;

class GoogleSheetController extends Controller
{
    public function index(){

        $sheets = new Sheets();

        $spreadsheetId = '1BZzaFfJz8-wRfIRT_DpL8J9sJpK3f_6YBhU9t4pIuuU';
        $sheetName = 'sheet01';

        $sheet = $sheets->spreadsheet($spreadsheetId)->sheet($sheetName)->get();
        $header = $sheet->pull(0);

        $values = $sheets->collection($header, $sheet);
        $dist = $values->toArray();

        $country = null; // or provide the actual country value
        $store = null; // or provide the actual store value
        $startDate = null; // or provide the actual start date value
        $endDate = null; // or provide the actual end date value
        $artist = 'DJ MICHO';
        $earningsSum = 0.0;

        foreach ($dist as $row) {
            if (
                ($country === null || $row['Country of Sale'] === $country) &&
                ($store === null || $row['Store'] === $store) &&
                ($startDate === null || $row['Reporting Date'] >= $startDate) &&
                ($endDate === null || $row['Reporting Date'] <= $endDate) &&
                $row['Artist'] === $artist
            ) {
                $earningsSum += floatval($row['Earnings (USD)']);
            }
        }


        ////////////////////////////

        $apiKey = 'AIzaSyDwsfekAlGRXhzkYOEzSwZiFXsVAxw3ZTc';
        $channelId = 'UCZ4O7CcuuXoEVC1tXxRLntQ';

        $apiUrl = 'https://www.googleapis.com/youtube/v3/channels?part=snippet,contentDetails,statistics&id=' . $channelId . '&key=' . $apiKey;

        $response = file_get_contents($apiUrl);
        if ($response === false) {
            // Error handling for failed API request
            die('Failed to fetch data from YouTube API.');
        }
        // Parse the JSON response
        $data = json_decode($response, true);
        if (!isset($data['items']) || empty($data['items'])) {
            // Error handling for missing 'items' key in API response
            die('Invalid API response. Unable to fetch channel data.');
        }
        // Extract the desired information from the response
        $channelName = $data['items'][0]['snippet']['title'];
        $subscribersCount = $data['items'][0]['statistics']['subscriberCount'];
        $uploadsCount = $data['items'][0]['statistics']['videoCount'];


        dd($earningsSum * 0.6, $channelName, $subscribersCount, $uploadsCount, $response, $dist);
    
    }
}
