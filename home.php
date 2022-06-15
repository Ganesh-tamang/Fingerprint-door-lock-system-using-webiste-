<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/home.css">
</head>
<body>
    <header class="header">
        <div class="header-container">
            
            <div class="header_title">
                <h1>Minor project </h1>
            </div> 
            <nav class="nav">
                <ul>
                    <li><a href="#" class="actives">Home</a></li>
                    <?php if(isset($_SESSION['account'])){
                        echo '<li><a href="./unlock.php" >Unlock</a></li> <li><a href="./logout.php">Logout</a></li>';             
                        }else{
                            echo '<li><a href="./login.php" >Login</a></li> <li><a href="./register.php">Register</a></li>';             
                        } ?>                     
                </ul>
            </nav>
        </div>
    </header>
    
    <section>
        <div class="front">
            <h2>FingerPrint Sensor</h2>
            <div class="front-detail">
            Fingerprints are the oldest and most widely used form of biometric identification.The use of fingerprint for identification has been employed in law enforcement for about a century. A much broader application of fingerprint is for personal authentication, for instance to access a computer, a network, an ATM machine, a car or a home or a phone. Electronic lock using fingerprint recognition system is a process of verifying the fingerprint image to open the electronic lock. This project highlights the development of fingerprint verification. Verification is completed by comparing the data of authorized fingerprint image with incoming fingerprint image. Then the information of incoming fingerprint image will undergo the comparison process to compare with authorized fingerprint image.
            </div>
            <button onclick="myFunction()" id="myBtn">Read More</button>
        </div>
    </section>
    
  <div class="container" id="more">
    <section>
        <div class="detail_container">
        <div class="detail">
            <div class="item1"></div>
           
            <div class="item2">
                <div class="item3"></div>
                <div class="item4">
              
                    <div style="color: rgb(0, 166, 243); font-style: italic; font-size: 1.4rem; margin-bottom: 15px;">
                        “Fingerprint patterns are genotypically determined and remain unchanged from birth till death.”
                    </div>
                    Fingerprints are made of a series of ridges and fur -
                    rows or valleys formed on the surface of the finger's skin.
                    Each fingerprint is unique, as determined by the pattern of
                    ridges and furrows, which form the elevated and depressed
                    portions of the fingertips, respectively. 
            
                </div>
            </div><!-- item2 end -->
            
    
        </div> <!-- deatil end -->
        </div>

    </section>
    <section>
        <div class="detail_container">
            <img src="./access/image/fingerprint1.png" style="max-width:480px; float:right; padding: 20px; border:2px solid white ; margin: 15px;">
            <p style="padding: 10px; color: rgb(87, 209, 169);">
                Fingerprints are made of a series of ridges and fur -
            rows or valleys formed on the surface of the finger's skin.
            Each fingerprint is unique, as determined by the pattern of
            ridges and furrows, which form the elevated and depressed
            portions of the fingertips, respectively. In particular, the inter
            sections of multiple ridges are of interest, and are called the
            minutiae of the fingerprint. Minutiae points are characteris
            tics of the pattern of ridges, which identify a ridge bifurcation
            or a ridge ending. The pattern of minutiae may also be used to
            identify the fingerprint. Although this method does not take
            into account the global shape of the ridges and the furrows of to identify the person to whom the fingerprint belongs. Comparing minutiae rather than the entire fingerprint makes the
            process faster and less resource intensive.
            Human fingerprints are detailed, nearly unique, difficult to alter, and durable over the life of an individual, making them suitable as long-term markers of human identity. They may be employed by police or other authorities to identify individuals who wish to conceal their identity, or to identify people who are incapacitated or deceased and thus unable to identify themselves, as in the aftermath of a natural disaster.
            The fingerprint sensor is one kind of sensor which is used in a fingerprint detection device. These devices are mainly inbuilt in the fingerprint detection module and it is used for computer safety. The main features of this device mainly include accuracy, better performance, robustness based on exclusive fingerprint biometric technology. Both fingerprint scanner otherwise reader is an extremely safe & suitable device for safety instead of a secret word. Because the password is easy to scan and also it is hard to keep in mind.
            </p>
             </div>
    </section>
</div>
 
   <script>
    function myFunction() {
  
            var moreText = document.getElementById("more");
            var btnText = document.getElementById("myBtn");
            
        
            if (moreText.style.display != "none") {
                btnText.innerHTML = "Read More";
                moreText.style.display = "none";
            } else {
                btnText.innerHTML = "Read Less";
                moreText.style.display = "block";
            }
        }
   </script>
   
</body>
</html>