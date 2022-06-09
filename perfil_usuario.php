<?php
    $people = file_get_contents("https://randomuser.me/api/?results=5");
    $people = json_decode($people);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
<body style="background-color: #eeeeee;">
<a href="usuarios.php"><img src="https://img.icons8.com/fluency/48/000000/left.png"/></a>

    <div class="container mt-5 border-rounded padding" style="background-color:white;">
        <div class="d-flex justify-content-center">
                <img src=<?php echo $people -> results[0] -> picture -> large?> alt="Profile picture" class ="rounded-circle" width="250" height="250"><br>
        </div>
            
        <div class="d-flex justify-content-center mt-3">
            <h3>
                <strong> 
                    <?php 
                    echo $people -> results[0] -> name -> title . " "; 
                    echo $people -> results[0] -> name -> first . " ";
                    echo $people -> results[0] -> name -> last; ?>
                </strong>
            </h3>
        </div>

        <div class="d-flex justify-content-center mt-3">
            <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad <br>minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Lorem dolor</p>
        </div>

        <div class="d-flex justify-content-center mt-3">
            <button type="button" class="btn btn-secondary"><?php echo $people -> results[0] -> location -> city?></button>
        </div>

        <div class="d-flex justify-content-center margin-top">
            <img src="https://img.icons8.com/color/48/000000/facebook.png"/>
            <img src="https://img.icons8.com/fluency/50/000000/instagram-new.png"/>
            <img src="https://img.icons8.com/color/50/000000/linkedin.png"/>
            <img src="https://img.icons8.com/color/50/000000/google-logo.png"/>
        </div>
            <div class="d-flex justify-content-center margin-top">
                <button type="button" class="btn btn-outline-primary btn-space">Mensaje</button>
                <button type="button" class="btn btn-primary">Contacto</button>
            </div>
    </div>

</body>
</html>

<style>
    .btn-space {
    margin-right: 15px;
    }  
    
    .margin-top{
        margin-top: 30px;
    }

    .border-rounded{
        border-radius: 20px;
    }
    .padding{
        padding-top: 70px;
        padding-bottom: 70px;
    }
</style>