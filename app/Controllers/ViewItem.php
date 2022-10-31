<?php
 
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Itemmobil;
 
class Viewitem extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function index()
    {
       
        $db = \Config\Database::connect();
        $model = new Itemmobil();
        
    
     $semua="SELECT * FROM `itemmobil`";
     $hasil = $model->query($semua);
     $masuk=  $hasil->getResult() ;
     //var_dump($masuk);
    //  $dijual = [
    //     "id"        => $masuk->id, // no rekening penampungnya
    //     "merk" =>  $masuk->merk,
    //     "kondisi"          => $masuk->kondisi,
    //     "deskripsi"  =>  $masuk->deskripsi,
    //     "harga"  =>  $masuk->harga,
    //  ];
        echo "Mobil yang dijual:\n";
        echo "\n";
foreach ($masuk as $masuks ) {
    echo "id: $masuks->id\n";
    echo "merk:$masuks->merk\n";
    echo "nohp:$masuks->nohp\n";
    echo "lokasi:$masuks->lokasi\n";
    echo "kondisi:$masuks->kondisi\n";
    echo "deskripsi:$masuks->deskripsi\n";
    echo "harga:$masuks->harga\n";
    echo "\n";
   // echo "<br>";

}
    
    
//      $model = new Usermarketplace();
//      $semua="SELECT * FROM itemmobil";
//      $hasil = $model->query($semua);
//    // echo $hasil;
//    if ($hasil->num_rows > 0) 
//    {
//        // OUTPUT DATA OF EACH ROW
//        while($row = $hasil->fetch_assoc())
//        {
//            echo "Mobil yang dijual: " .
//                $row["merk"]. " - kondisi: " .
//                $row["kondisi"]. " | deskripsi: " . 
//                $row["deskripsi"]. " | harga: " . 
//                $row["harga"]. "<br>";
//        }
//    } 
//    else {
//        echo "0 results";
//    }
        
        
    }
 
}