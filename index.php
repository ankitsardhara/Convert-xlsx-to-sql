
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Convert xlsx to sql</title>
        <meta name="description" content="This tutorial will learn how to import excel sheet data in mysql database using php. Here, first upload an excel sheet into your server and then click to import it into database. All column of excel sheet will store into your corrosponding database table."/>
        <meta name="keywords" content="import excel file data in mysql, upload ecxel file in mysql, upload data, code to import excel data in mysql database, php, Mysql, Ajax, Jquery, Javascript, download, upload, upload excel file,mysql"/>
    </head>
    <body>

        <?php
        /*         * ********************** YOUR DATABASE CONNECTION START HERE   *************************** */
        ////////  CONFIG
        define("DB_HOST", "localhost"); // set database host
        define("DB_USER", "root"); // set database user
        define("DB_PASS", "root"); // set database password
        define("DB_NAME", "book"); 
        $inputFileName = 'Book.xlsx';
        $databasetable="table_book";
        //////// END CONFIG
        /*         * ********************** YOUR DATABASE CONNECTION END HERE   *************************** */


        $link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.");
        $db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database");


        set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
        include 'PHPExcel/IOFactory.php';

// This is the file path to be uploaded.


        try {
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }



        $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        $arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet

        $first_row = ($allDataInSheet[1]);





        $alll_filed = (array_keys($first_row));


        $num_of_all_filed = count($alll_filed);





function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}


//Create dynemic table

        $insert_table = "CREATE TABLE IF NOT EXISTS `" . $databasetable . "` (
  `" . $databasetable . "_id` int(11) NOT NULL AUTO_INCREMENT,
  ";
        for ($z = 0; $z < count($alll_filed); $z++) {
            $filed_name = str_replace(" ", "", trim($first_row[$alll_filed[$z]]));
            $filed_name = str_replace(".", "", $filed_name);
            $filed_name = str_replace("-", "", $filed_name);
            $filed_name = strtolower($filed_name);
            $filed_name = clean($filed_name);
            if ($filed_name != "") {
                $insert_table.="`" . $filed_name . "` text NOT NULL,";
            }
            $first_row[$alll_filed[$z]]=$filed_name;
        }

        $insert_table.=" PRIMARY KEY (`" . $databasetable . "_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";


        $sql = $insert_table;


        if (mysql_query($sql, $link)) {
            echo 'Table created';
        } else {
            echo "Error creating database: " . mysql_error();
            die;
        }






        echo '<pre>';
        echo '<table border=1  style="border-collapse: collapse">';
        echo '<tr>';

        echo '<td>';
        echo $databasetable . "_id";
        echo '</td>';
        for ($z = 0; $z < count($alll_filed); $z++) {

            echo '<td>';
            echo $first_row[$alll_filed[$z]];
            echo '</td>';
        }
        echo '<td>';
        echo 'status';
        echo '</td>';
        echo '</tr>';

        $j = 0;
        for ($i = 2; $i <= $arrayCount; $i++) {



            echo '<tr>';
            echo '<td>';
            echo $j = $j + 1;
            echo '</td>';
            $sql = "INSERT INTO " . $databasetable . "";
            for ($z = 0; $z < count($alll_filed); $z++) {
                $column[$z] = trim($allDataInSheet[$i][$alll_filed[$z]]);
                $column[$first_row[$alll_filed[$z]]] = mysql_escape_string($column[$z]);
                echo '<td>';
                echo $column[$first_row[$alll_filed[$z]]];
                echo '</td>';
                if($first_row[$alll_filed[$z]]!=""){
                    $key['key'][$z] = $first_row[$alll_filed[$z]]; 
                      $key['value'][$z] = "'" . $column[$first_row[$alll_filed[$z]]] . "'";
                }
               

              
            }


            $mysql_key = implode(",", $key['key']);
            $mysql_value = implode(",", $key['value']);
            $sql.= " (" . $mysql_key . ") VALUES (" . $mysql_value . ")";
          


            echo '<td>';
            if (mysql_query($sql, $link)) {
                echo "Record my_db created  :" . $sql;
            } else {
                echo "Error creating database: " . mysql_error();
            }
            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';
        ?>
    <body>
</html>