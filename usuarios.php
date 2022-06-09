<?php
                
session_start();

if ($_SESSION['data_history'] == null){

    $people = file_get_contents("https://randomuser.me/api/?results=17"); //Modifica el numero de (results) para cambiar la cantidad de usuarios
    $people = json_decode($people);

    $_SESSION['total_users'] =  $people -> info -> results;
    $_SESSION['users_per_page'] = 5;

    foreach($people -> results as $key => $value){  //Take off the (results) in the json object. To avoid results -> ... -> ... ->
        $people_without_results[$key] = $value;
    }

    $_SESSION['data_history'] = $people_without_results; 
    
    $data = array();

    for($i = 0; $i < $_SESSION['users_per_page']; $i++){

        $data[] = $_SESSION['data_history'][$i];
    }

}else{

    if(!isset($_POST['search-word'])){

        $_SESSION['total_users'] = count($_SESSION['data_history']);
    }

    for($i = 0; $i < $_SESSION['users_per_page']; $i++){
        
        $data[] = $_SESSION['data_history'][$i];
        

    }
     
}

function orderNames($data){

    $names = array();

    for($i = 0; $i < count($data); $i++){
        $names[$i] = $_SESSION['data_history'][$i] -> name -> first; 
    } 
    
    asort($names); // Ordered array of names

    $data = array();

    foreach($names as $key => $value){
        array_push($data, $_SESSION['data_history'][$key]);  //Add every (person) to data(array) ordered by their name
    }
    return $data;
}

function users_per_page(&$data, $index_start, $index_end){
    $data = array();
    for($index_start; $index_start <= $index_end; $index_start++){
        $data[] = $_SESSION['data_history'][$index_start];
    }
}

$current_page = 1;

for ($i = 0; $i < count($_SESSION['pagination_array']); $i++){

    if (isset( $_POST[$_SESSION['pagination_array'][$i]] ) ){  //Si le han dado clic a un boton de la paginación
        
        $index_start = $_SESSION['users_per_page'] * ($_SESSION['pagination_array'][$i] - 1);

        $index_end = ($_SESSION['users_per_page'] * $_SESSION['pagination_array'][$i] ) - 1;

        users_per_page( $data, $index_start, $index_end );   

        $current_page = $_SESSION['pagination_array'][$i];
    }
} 

function searchName($entered_word, $data){

    $names_found = array();
    $entered_word = strtolower($entered_word);
    $entered_word = str_replace(" ", "",$entered_word) ;

    foreach ($_SESSION['data_history'] as $item => $key) {

        $name = strtolower($key -> name -> first);

        $name = str_replace(" ", "",$name);

        
        if ( $name == $entered_word || substr($entered_word, 0, 3) == substr($name, 0, 3)){

            array_push($names_found, $_SESSION['data_history'][$item]);

        }
    } 

    return $names_found;

}


if (isset($_POST['search-word']) && !empty($_POST['key-word'])) { // Si le han dado clic y hay una palabra

    $entered_word = $_POST['key-word'];

    $data = searchName($entered_word, $data);

    $_SESSION['total_users'] = count($data);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>


<body style="background-color: #eeeeee;"> 
    <div class="container " style="background-color: white;">
    <div class="col-md py-4">
        <h5>Buscar usuario</h5>
        
        <form action="usuarios.php" method="POST">

        <label for="key-word">Palabra clave</label>
                <input type="text" class="" id="key-word" name="key-word">
                
                <button type="submit" name="search-word">Buscar</button>
                <!-- Crear <a> para el link de buscar y así poder enviar mediante GET el num -->
                <button type="submit" name="delete-data">Eliminar</button>
        </form>

    </div>

        <p>Usuarios Registrados: <strong><?php echo $_SESSION['total_users']; ?></strong></p>
        <table class="table">
            <thead>
                <tr>
                    <td>Foto</td> 
                    <td>Nombre <form action="usuarios.php" method="POST"> 
                    <?php 
                    if (isset($_POST['order'])){

                        $data = orderNames($data); ?>

                        <button type="submit" name="disorganize">Desordenar</button> </form></td>

            <?php
                    }else{ ?>
                        <button type="submit" name="order">Ordenar</button> </form></td>
            <?php   } 

                    if (isset($_POST['disorganize'])){
                        $data = $_SESSION['data_history']; ?>
            <?php
                    }
                    ?>

                    <td>Género</td>
                    <td>Email</td>
                    <td>Dirección</td>
                    <td>Teléfono</td>
                    <td>Ver</td>
                </tr>
            </thead>
            <tbody>
             <?php           
                    if (isset($_POST['delete-data'])){
                        $_SESSION['data_history'] = array();
                        session_destroy();
                    }

                    foreach($data as $key => $item){ 
                        
                        if (!empty($data[$key])) { ?>
                        <tr>
                            <td>
                                <img src="<?php echo $item -> picture -> thumbnail; ?>" class="rounded-circle"> 
                            </td>

                            <td>
                                <?php echo  $item -> name -> title . " ";  ?>
                                      <strong>
                                            <?php
                                            echo  $item -> name -> first . " ";
                                            echo  $item -> name -> last;
                                            ?>
                                      </strong>
                            </td>
                            <td>
                                <img src=<?php 
                                            if ($item -> gender == "male"){
                                                    $gend = "https://www.freeiconspng.com/uploads/male-icon-32.png";
                                                    echo $gend;
                                            }else{
                                                    $gend = "https://www.freeiconspng.com/uploads/female-icon-6.png";
                                                    echo $gend;
                                            }
                                
                                ?>   width="40"> </img>

                            </td>
                            <td>
                                <?php 
                                    echo $item -> email;
                                ?>
                            </td>
                            <td>
                                <?php
                                    echo $item -> location -> street -> number . " ";
                                    echo $item -> location -> street -> name;
                                ?>
                            </td>
                            <td>
                                <?php
                                    echo $item -> phone;
                                ?>
                            </td>
                            <td>
                                <a href="perfil_usuario.php"><button><img src="https://www.freeiconspng.com/uploads/eye-icon-png-eye-icon-6.png" width="20"> </button></a>
                            </td>
                        </tr>
             <?php }
                }
                    
                    ?>
                        
            </tbody>
            </table>
            <?php
                $total_pages = ceil($_SESSION['total_users'] /  $_SESSION['users_per_page']);  ?>

                <form action="usuarios.php" method="POST"> <?php

                if (!isset($_SESSION['pagination_array'])){ ?>

                        <?php
                        for($i=1; $i <= $total_pages; $i++){ 
                            if ( $current_page == $i){ 
                                ?>
                                <button style="background-color: blue;" type="submit" name="<?php echo $i ?>"><?php echo $i ?></button>
                                
                        <?php
                            }else{ ?>
                                <button type="submit" name="<?php echo $i ?>"><?php echo $i ?></button>
                        <?php
                            }
                           
                        
                        $_SESSION['pagination_array'][] = $i;

                        } ?>
                        <!-- <br><br><br> -->
                        <?php

                    }else{
                        for($i=1; $i <= $total_pages; $i++){ 
                            
                            if ($current_page == $i){ ?>

                                <button style="background-color: blue;" type="submit" name="<?php echo $i ?>"><?php echo $i ?></button>

               <?php        }else{ ?>
                                <button type="submit" name="<?php echo $i ?>"><?php echo $i ?></button>

                <?php            }
                            
                            
                            ?>

              <?php     } 

              
                    } ?>
                    <br><br><br>

                    </form> 

    <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php                    
                foreach($data as $key => $item){ 
                    
                    if (!empty($data[$key])){ ?>

                        <div class="col">

                                <div class="card">

                                    <div class="card-header col">
                                        
                                        <img class="img-thumbnail" src=" <?php echo $item -> picture -> thumbnail; ?>" class="card-img-top" width="70"> 
                                        <?php echo $item -> name -> title . " ";  ?>
                                                <strong> <?php
                                                echo  $item -> name -> first . " ";
                                                echo  $item -> name -> last;
                                                ?>
                                                </strong></h5>
                                    </div>

                                    <div class="card-body">
                                        <img src="<?php 
                                            if ($item -> gender == "male"){
                                                $gend = "https://www.freeiconspng.com/uploads/male-icon-32.png";
                                                echo $gend;
                                            }else{
                                                $gend = "https://www.freeiconspng.com/uploads/female-icon-6.png";
                                                echo $gend;
                                                } 
                                            
                                            ?> " width="30"> </img>
                                            <?php echo $item -> gender; ?>
                                            <p class="card-text">

                                                Email: <strong> <?php echo $item -> email . "<br>" ?> </strong> 

                                                Teléfono: <strong> <?php echo $item -> phone . "<br>" ?> </strong>

                                                Dirección: <strong> <?php echo $item -> location -> street -> number . " ";

                                                echo  $item -> location -> street -> name . "<br>"?> </strong>
                                                <a href="perfil_usuario.php">
                                                    <button type="button" class="btn btn-primary">
                                                        
                                                            <img src="https://www.freeiconspng.com/uploads/eye-icon-png-eye-icon-6.png" alt="" width="20"> 
                                                            Ver perfil
                                                        
                                                    </button>
                                                </a>           
                                            </p>
                                     </div>
                                </div>
                            </div>
                <?php } } ?> 
     </div>
                    

</body>
</html>


    
