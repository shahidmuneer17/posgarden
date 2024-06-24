<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Pacifico&display=swap" rel="stylesheet">

<title>Grocery Garden</title>

<style>
    :root {
        --primary-color: #3587AD;
        --secondary-color: #ffffff;
        --success-color: #28a745;
        --info-color: #17a2b8;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --logo-color: #ecca1c;
    }

    .bgprimary {
        background-color: var(--primary-color);
    }

    h1 {
        font-family: 'Pacifico', cursive;
        font-size: 3rem;
        font-weight: 700;
        color: var(--logo-color);
    }

    h2, p {
        font-family: 'Manrope', sans-serif;
    }

   h2, p {
        color: var(--secondary-color);
    }

    h2 {
        font-size: 2rem;
        font-weight: 600;
    }
</style>
</head>
<body>
<section class="container-fluid vh-100 bgprimary pt-5 pb-5">
    <div class="row">
        <div class="col-md-8 p-5 d-flex justify-content-center" style="flex-direction: column; row-gap: 100px;">
            <div>
                <h1 class="mb-3">Grocery Garden</h1>
                <p>Your one stop shop for all your grocery needs</p>
                
            </div>

            <div>
                <h2 class="mb-3">Coming Soon!</h2>
                <p>We're working hard to bring you the best online grocery shopping experience.<br> Stay tuned for our grand opening!</p>
            </div>
        </div>
        <div class="col-md-4 p-5">
        <img src="{{asset('images/grocery.png')}}" alt="Grocery Garden" class="img-fluid" width="100%">
        </div>
    </div>
</section>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
-->
</body>
</html>