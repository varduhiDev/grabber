<?php

namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class StoreArticle implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    public $update = [];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Article ::truncate();
            if (file_exists(public_path('images'))) {
                File::cleanDirectory(public_path('images'));
            } else {
                File::makeDirectory(public_path('images'), $mode = 0777, true, true);
            }

            $page = 1;
            while($page <= 100) {
                $ch = curl_init('http://www.tert.am/am/news/'.$page);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $html = curl_exec($ch);
                $dom = new \DOMDocument();
                libxml_use_internal_errors(true);
                $dom->loadHTML($html);
                libxml_use_internal_errors(false);
                $xpath = new \DOMXPath($dom);
                $results = $xpath->query("//*[@class='news-blocks']");
                if ($results->length > 0) {
                    $this->update = [];
                    foreach($results as  $key => $result) {
                        $date = $xpath->query("//p[@class='nl-dates']", $result);
                        $dateValue = $date->item($key)->nodeValue;
                        $dateValue = trim($dateValue);
                        $dateValue = preg_replace('/[^0-9:|.\-]/', ' ', $dateValue);
                        $dateValue = preg_replace('/\s\s+/', ' ', $dateValue);
                        $dateValue = substr($dateValue, 0, 14);
                        $dateValue = \DateTime::createFromFormat('H:i d.m.y', $dateValue);
                        $title = $xpath->query("//h4/a", $result);

                        $description = $xpath->query("//p[@class='nl-anot']", $result);
                        $image = $xpath->query("//img[@class='news-pic']", $result);
                        $imagePath = $image->item($key)->getAttribute('src');
                        $filename = basename($imagePath);
                        Image::make($imagePath)->save(public_path('images/' . $filename));
                        $this->update[] = [
                            'date' => $dateValue,
                            'title' =>  trim($title->item($key)->nodeValue),
                            'url' => trim($title->item($key)->getAttribute('href')),
                            'description' => trim($description->item($key)->nodeValue),
                            'image' => asset('images/' . $filename)
                        ];
                    }
                    if(!empty($this->update)){
                        Article::insert(
                            $this->update
                        );
                    }
                }
                $page++;
            }
        } catch (\Exception $e) {
            return print_r($e->getMessage());
        }

    }
}
