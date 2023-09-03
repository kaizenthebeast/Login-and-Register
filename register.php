<?php

include 'home/header.php';


?>


<link rel="stylesheet" href="main.css">



<!-- Registration Sign in and Sign Up Form -->

<section class="d-flex align-items-center justify-content-center py-5">

  <div class="container2">

    <div class="signin-signup">

      <!-- Log In -->

      <form action="loginCheck.php" method="post" class="sign-in-form " id="sign-in-form">
        <h2 class="title ">Sign In</h2>
         
         <div class="form-message text-white pt-3 rounded text-center w-100 text-uppercase px-1" style="display: none; letter-spacing:2px;
        background-color:rgb(4, 4, 132);"></div>



        <div class="input-field">
          <i class="bi bi-person-fill"></i>
          <input type="text" placeholder="Email" name="userEmail">
        </div>
        <div class="input-field">
          <i class="bi bi-lock-fill"></i>
          <input type="password" placeholder="Password" name="password">
        </div>

        <input type="submit" name="login" value="login" class="reg-button">


        <style>
          .account-text {
            display: none;
          }

          @media(max-width:779px) {
            .account-text {
              display: block;
            }
          }

          @media(max-width:635px) {
            .account-text {
              display: block;
            }
          }
        </style>
        <p class="account-text">Don't have an account? <a href="#" id="sign-up-btn2">Sign Up</a></p>
      </form>


       <!-- Register -->


      <form action="signupCheck.php" method="post" class="sign-up-form " id="signup-form">

        <h2 class="title">Sign Up</h2>

        <div class="form-message text-white pt-3 rounded text-center w-100 text-uppercase" style="display: none; letter-spacing:2px;
        background-color:rgb(4, 4, 132);"></div>

        <div class="input-field">
          <i class="bi bi-person-fill"></i>
          <input type="text" placeholder="Full Name" name="fullName">
        </div>

        <div class="input-field">
          <i class="bi bi-envelope-fill"></i>
          <input type="text" placeholder="Email" name="userEmail">
        </div>

        <div class="input-field">
          <i class="bi bi-lock-fill"></i>
          <input type="password" name="password" placeholder="Password">
        </div>

        <div class="input-field">
          <i class="bi bi-lock-fill"></i>
          <input type="password" name="confirmPassword" placeholder="Confirm Password">
        </div>


        <input type="submit" name="signup" value="Sign Up" class="reg-button" id="submit">


        <p class="account-text">Already have an account? <a href="#" id="sign-in-btn2">Sign in</a></p>

      </form>
    </div>



    <div class="panels-container ">

      <div class="panel left-panel">
        <div class="content">
          <h3>Already have an account?</h3>
          
          <button class="reg-button" id="sign-in-btn">Sign In</button>
        </div>
        <img src="../images/sign_in.svg" alt="" class="image">
      </div>

      <div class="panel right-panel">
        <div class="content">
          <h3>Don't have an account?</h3>
        
          <button class="reg-button" id="sign-up-btn">Sign Up</button>
        </div>
        <img src="../images/sign_up.svg" alt="" class="image">
      </div>

    </div>

  </div>


</section>





<script>


$(document).ready(function() {
  $('#sign-in-form').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: 'loginCheck.php',
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData: false,

      success: function(response) {
        $('.form-message').css('display', 'block');

        if (response.status == 1) {
          $('#sign-in-form')[0].reset(); // reset the form

          // Redirect the user to the appropriate page based on user type
          window.location.href = response.redirect;

        } else {
          $('.form-message').html('<p>' + response.message + '</p>').css('display', 'block');
          hideFormMessage(); // Call the function to hide the message after 4 seconds
        }
      }
    });
  });

  $('#signup-form').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: 'signupCheck.php',
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData: false,

      success: function(response) {
        $('.form-message').css('display', 'block');

        if (response.status == 1) {
          $('#signup-form')[0].reset(); // reset the form

          // Show success message to the user
          $('.form-message').html('<p>' + response.message + '</p>').css('display', 'block');
          hideFormMessage(); // Call the function to hide the message after 4 seconds

        } else {
          $('.form-message').html('<p>' + response.message + '</p>').css('display', 'block');
          hideFormMessage(); // Call the function to hide the message after 4 seconds
        }
      }
    });
  });

  function hideFormMessage() {
    setTimeout(function() {
      $('.form-message').fadeOut('slow', function() {
        $(this).css('display', 'none');
      });
    }, 3000); // 3000 milliseconds =  seconds
  }
});




</script>

<script src=app.js></script

<?php

include("home/footer.php");

?>