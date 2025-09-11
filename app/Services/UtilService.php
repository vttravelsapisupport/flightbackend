<?php
namespace App\Services;

use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\FlightTicket\Airport;
use Illuminate\Support\Facades\Cache;
use App\Models\FlightTicket\BookTicket;
use Illuminate\Support\Facades\Storage;
use App\Models\FlightTicket\Destination;
use App\Models\FlightTicket\BookTicketSummary;

class UtilService
{
    public static function getAirports()
    {
        $cachekey = 'active_airports';
        $data = Cache::get($cachekey);
        if (!$data) {
            $airports = Airport::getAirports();
            $result = [];
            foreach ($airports as $k => $v) {
                $result[$v->code] = [
                    'name' => $v->cityName . ' ' . $v->code,
                    'code' => $v->code,
                    'airport_name' => $v->name,
                    'city_name' => $v->cityName,
                    'country_code' => $v->countryCode,
                ];
            }
            $data = Cache::put($cachekey, $result);
            return $result;
        }

        return $data;
    }
    public static function createPDF($booking_data,$book_ticket_details,$destinationDetail)
    {


        PDF::setOptions(['dpi' => 120, 'defaultFont' => 'sans-serif']);
        $filename =
                $destinationDetail->code .
                ' ' .
                $booking_data->travel_date->format('d-M-Y').
                '.pdf'
        ;

        $path =  '/pdfs/' . $filename;
        $data = $booking_data;
        $pdf = PDF::loadView(
            'flight-tickets.sales.pdf',
            compact('data', 'book_ticket_details')
        );
        $content = $pdf->download()->getOriginalContent();

        $image_path = Storage::disk('s3')->put($path, $content);
        // Generate a **temporary signed URL** valid for 10 minutes
        $signedUrl = Storage::disk('s3')->temporaryUrl($image_path, now()->addMinutes(10));

        return [
            'filename' => $filename,
            'url' => $signedUrl,
        ];
    }
}
?>
