<?php
 
namespace App\Html;
 
use App\Interfaces\PageInterface;
 
abstract class AbstractPage implements PageInterface
{
    static function head()
    {
        echo '<!DOCTYPE html>
        <html lang="hu-hu">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="css/index.css">
            <title>REST API Ügyfél</title>
           
            <!-- Script -->
            <script src="js/jquery-3.7.1.js" type="text/javascript"></script>
            <script src="js/app.js" type="text/javascript"></script>
        
        </head>';
    }
 
    static function nav()
    {
        echo '
        <nav>
            <form name = "nav" method = "post" action = "index.php">
                <button type = "submit" name = "btn-home">
                    <i class = "fa fa-home" title = "Kezdőlap"></i>
                </button>
                <button type = "submit" name = "btn-counties">Megyék</button>
                <button type = "submit" name = "btn-cities">Városok</button>
            </form>
        </nav>';
    }
 
    static function footer()
    {
        echo '
        <footer>
        <br>
            <strong>Tahu Szilárd</strong>
        <br>
        </footer>
        </html>';
    }
 
    abstract static function tableHead();
 
    abstract static function tableBody(array $entities);
 
    abstract static function table(array $entities);
 
    abstract static function editor();
    static function searchbar()
    {
    echo '
        <form method="post" action="">
            <input type="text" name="keyword" placeholder="Keresés név szerint" required />
            <button type="submit" name="btn-search" title="Keresés">
                <i class="fa fa-search"></i> Keresés
            </button>
        </form>
        <br>';
    }

    static function displaySearchResults($results, $keyword)
    {
        if (!empty($results)) {
            echo "<table><thead><tr><th>Index</th><th>Név</th><th>Műveletek</th></tr></thead><tbody>";
                
            foreach ($results as $result) {
                echo "
                    <tr>
                        <td>{$result['id']}</td> <!-- Itt jelenítjük meg a megye azonosítóját -->
                        <td>{$result['name']}</td>
                        <td class='flex'>
                            <form method='post' action='' class='inline-form'>
                                <button type='submit' name='btn-edit-county' value='{$result['id']}' title='Szerkesztés'>
                                    <i class='fa fa-edit'></i>
                                </button>
                            </form>
                            <form method='post' action=''>
                                <button type='submit' name='btn-del-county' value='{$result['id']}' title='Töröl'>
                                    <i class='fa fa-trash'></i>
                                </button>
                            </form>
                        </td>
                    </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Nincs találat a következő keresési kifejezésre: <strong>$keyword</strong></p>";
        }
    }
        
} 

