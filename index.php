<?php

    require 'vendor/autoload.php';
    require 'db.php';

    if(isset($_REQUEST["q"]) and $_REQUEST["q"] != ''){

        $dbconn = new Database("localhost", "scrapper", "root", "");
        $dbconn->conectar();
        

        $client = new \Goutte\Client();
        $client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36');
        $query = $_REQUEST["q"];
        $url = 'https://www.bing.com/search?q=' . urlencode($query);
    
        $scrapper = $client->request('GET', $url);
    
        $scrapper->filter('li.b_algo')->each(function ($node) use (&$links) {

            $link = $node->filter('a')->first()->attr('href');
            $linkInfo = parse_url($link);
    
            if (isset($linkInfo['host'])) {
                $links[] = $linkInfo['host'] . $linkInfo["path"];

                  
            }

        });

      
        foreach($links as $link){
            $host = explode("/",$link);
            $dbconn->insert($host[0]); 
        }
                 
        

    }




    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>WebScrapper</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" href='styles.css'>
    </head>
    <body>
        <div class='container'>
            <div class='row text-center'>
                <div class='col-md-6 offset-md-3 mb-5'>
                    <h3>WebScrapper</h3>

                    <form class='form-group' action='index.php' method='post'>
                    
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Que desea buscar?" name='q' id='q' value='<?php echo (isset($_POST["q"])) ? $_POST["q"] : ''; ?>'  >
                            <button class="btn btn-outline-primary" type="submit">Search</button>
                        </div>

                        <a href='cleandb.php'>Limpiar DB</a>

                    </form>
                </div>
            </div>
            <?php

                if(isset($_POST["q"]) and $_POST["q"] != ''){

            ?>
                <div class='row'>
                    <div class='col-md-6'>
                        <h4>Ultima busqueda: <?php echo $_POST["q"]; ?></h4>
                        <ul>
                            <?php
                                for($i = 0; $i < count($links); $i++){
                            ?>    
                                <li><a target='_blank' href='http://<?php echo htmlentities($links[$i]); ?>'><?php echo htmlentities($links[$i]); ?></a></li>
                                

                            <?php
                                }
                            ?>      
                        </ul>
                    </div>

                    <div class='col-md-6 scrolleable' >
                        <h4>Ocurrencia de dominios</h4>
                        <table class='table table-dark '>
                            <thead>
                                <tr>
                                    <th>Dominio</th>
                                    <th>Ocurrencias</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    
                                    $dominios = $dbconn->getResultados();
                                    $dbconn->desconectar();
                                    foreach($dominios as $dominio){

                                        echo "<tr>";
                                        echo "<td><a target='_blank' href='http://$dominio[dominio]'>$dominio[dominio]</a></td>";
                                        echo "<td>$dominio[total]</td>";
                                        echo "</tr>";
                                        
                                    }
                                   
                                ?>
                               
                            </tbody>
                        </table>        
                    </div>
                </div>

            <?php
                }
            ?>
            
        </div>

        


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </body>
    </html>