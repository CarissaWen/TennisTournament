
<?php
header("Content-Type: application/json; charset=utf-8");
header('Access-Control-Allow-Origin: localhost:3000');
/* the above headers are learnt from week6 server-side processing slides
about set HTTP headers for a JSON response. 
The first line includes content type of the response to JSON and character encoding to UTF-8;
the second line allows requests from "localhost:3000". 
This is known as Cross-Origin Resource Sharing(CORS). */

$errors = array(); //This line initializes an empty array called "$errors".//


if(!isset($_GET['year']) || 
!isset($_GET['file']) || 
!isset($_GET['winner']) ||
!isset($_GET['runnerup']) || !isset($_GET['tournament']) || !isset($_GET['yearop'])){
    $errors[] = "One or more of the fields is missing";
}

/*The lines 14 - 19's codes are learnt from week6 server-side processing slides, searchSuggest.php 
which checks the value is NULL or not.*/

/*Otherwise, the lines 25-32 codes help to retrieve resourses by a superglobal variable '$_GET'*/
else{
    $year = $_GET['year'];
    $filename = $_GET['file'];
    $winner = $_GET['winner'];
    $runnerup  = $_GET['runnerup'];
    $tournament = $_GET['tournament'];
    $yearop = $_GET['yearop'];

/* The lines 34-54 check for errors and validate user input */
    if($filename != "allfiles" && !file_exists($filename)){
       $errors[] = "file does not exist";
    }


    if($yearop != "=" && $yearop != "<" && $yearop != ">"){
        $errors[] = "invalid yearop";
    }

    if($tournament != "any" &&
        $tournament != "Australian Open" && 
        $tournament != "French Open" && 
        $tournament != "Wimbledon" && 
        $tournament != "U.S. Open"){
        $errors[] = "invalid tournament";
    }

    if($year != "" && (!is_integer((int)$year) || $year < "1877")){
        $errors[] = "invalid year" ;
    }
} 

/* The lines 60-62 check if the '$errors' array contains any errors
and if its count is greater than zero, if it has multiple errors, 
the code would only return the first error message. */ 

if (count($errors) > 0){
    echo json_encode(array("error" => $errors[0]));
}
/* The lines 65-76 read the files specified in the filename. If filename is 'allfiles' 
   then it reads both the men and women files and merge them.*/ 
else{
    if($filename == "allfiles"){
        $men_string = file_get_contents("mens-grand-slam-winner.json");
        $women_string = file_get_contents("womens-grand-slam-winner.json");
        $men_results = json_decode($men_string, true); 
        $women_results = json_decode($women_string, true); 
        $results = array_merge($men_results, $women_results);
    }
    else{
        $string = file_get_contents($filename);//read the data from the file//
        $results = json_decode($string, true); //convert the JSON data to a PHP array//
    }
    
    $search_results = array();  // create an empty array to store the data //
    
    //The lines 81-96 PHP codes iterate over a multidimensional array 'results using the 'foreach' loop.//
    foreach ($results as $row){
        
        if(($year == "") || ($yearop == "=" && $row["year"] == $year)
        || ($yearop == ">" && $row["year"] > $year) 
        || ($yearop == "<" && $row["year"] < $year)){
        if ($tournament == "any" || $row["tournament"] == $tournament){
            // filter the data from the file //
            if(str_contains($row["winner"], $winner) && str_contains($row["runner-up"], $runnerup)){
                
                    $search_results[] = $row;
                }
            }
        }

    }

    if(empty($search_results)){
        echo json_encode(array('error' => 'results not found'));//If there is no result, the output is 'Error' message.
    }else{
        echo json_encode($search_results); //return relevant data as JSON data
    }
}
?>