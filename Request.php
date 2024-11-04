<?php

namespace App\Html;

//use App\Pdf\Pdf;
use App\RestApiClient\Client;
use App\Interfaces\PageInterface;
use App\Html\AbstractPage;
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;
class Request {

    static function handle()
    {
        switch ($_SERVER["REQUEST_METHOD"]){
            case "POST":
                self::postRequest();
                break;

        }
    }


    private static function postRequest()
    {
        $request = $_REQUEST;

        switch ($request) {
            case isset($request['btn-home']):
                break;

            case isset($request['btn-counties']):
                PageCounties::table(self::getCounties());
                break;

            case isset($request['btn-save-county']):
                $client = new Client();
                if (!empty($request['id'])) {
                    $data['id'] = $request['id'];
                }
                break;

            case isset($request['btn-del-county']):
                $id = $request['btn-del-county'];
                $client = new Client();
                $response = $client->delete('counties/' . $id, $id);
                if ($response && isset($response['success']) && $response['success']) {
                    echo "Sikeres törlés!";
                } 
                
                PageCounties::table(self::getCounties());
                break;
            case isset($request['btn-save-new-county']):
                $name = $request['new_name'];
                $client = new Client();
                $response = $client->post('counties', ['name' => $name]);
                if ($response && isset($response['success']) && $response['success']) {
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit; 
                } else {
                    //echo "Hiba történt a mentés során!";
                }
                PageCounties::table(self::getCounties());
                break;
            case isset($request['btn-search']):
                $keyword = $_POST['keyword'];
                $results = self::searchCountiesByName($keyword);
                echo "<h2>Keresési eredmények:</h2>";
                AbstractPage::searchbar(); 
                AbstractPage::displaySearchResults($results, $keyword);
                break;
               
            case isset($request['btn-edit-county']):
                    $id = $request['btn-edit-county'];
                    $client = new Client();
                    $county = $client->get('counties/' . $id); 
                    echo "<pre>";
                    echo "</pre>";
                    PageCounties::displayEditForm($county); 
                    break;
            case isset($request['btn-save-edit-county']):
                        $id = $request['id'];
                        $newName = $request['edit_name'];
                        $client = new Client();
                        
                        $response = $client->put('counties/' . $id, ['name' => $newName]);
                        
                        if ($response && isset($response['success']) && $response['success']) {
                            echo "A név sikeresen módosítva lett!";
                        } else {
                            echo "Hiba történt a módosítás során!";
                        }
                        
                       
                        PageCounties::table(self::getCounties());
            break;
        }
    }


    private static function getCounties() : array
    {
        $client = new Client();
        $response = $client->get('counties');
 
        return $response['data'];
    }
    static function searchCountiesByName($keyword)
    {
        $client = new Client();
        $counties = $client->get('counties');
        
        $results = [];
        foreach ($counties['data'] as $county) {
            if (stripos($county['name'], $keyword) !== false) {
                $results[] = $county;
            }
        }
        
        return $results;
    }


}