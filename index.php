<?php 
include('connectivity.php'); // Include Login Page

if (isset($_SESSION['username'])){
	header("location: skybox.php");
	exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Reg</title>
	<link rel="stylesheet" href="style.css">

	<link rel='stylesheet' href='https://cdn.rawgit.com/daneden/animate.css/v3.1.0/animate.min.css'>
	<script src='https://cdn.rawgit.com/matthieua/WOW/1.0.1/dist/wow.min.js'></script>
	<script src="http://code.jquery.com/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/BoxAPI.init.js"></script>
	<script type="text/javascript" src="js/SkyAPI.init.js"></script>
	<script>
	</script>
</head>
<body>
	<div class="form">      
      	<ul class="tab-group" >
      	<div data-wow-delay="0.2s" class="wow bounceInDown center">
      		<li class="tab active"><a href="#signup">Sign Up</a></li>
	        <li class="tab"><a href="#login">Log In</a></li>
      	</div>

        <div class="clear"></div>
      	</ul>
      
      	<div class="tab-content">     	
        	<div id="signup">
        		<div data-wow-delay="1s" class="wow rollIn">
        			<h1>Sign Up for Free</h1>	
        		</div>             		
          
          		<form action="" method="post">
          
          		<div class="top-row">
         	   		<div class="field-wrap">
         	   			<div data-wow-delay="1.5s" data-wow-duration="1.3s" class="wow fadeInRight">
         	   				<label>
		                	First Name<span class="req">*</span>
		              		</label>
		              		<input name="firstname" type="text" required autocomplete="off" />
         	   			</div>
         	   			<!-- End WOW -->
           			</div>
            		<!-- End firstname -->
        
           			<div class="field-wrap">
           				<div data-wow-delay="1.7s" data-wow-duration="1.3s" class="wow fadeInRight">
           					<label>
		                	Last Name<span class="req">*</span>
		             		</label>
		              		<input name="lastname" type="text" required autocomplete="off"/>
           				</div>
           				<!-- End WOW -->
            		</div>
            		<!-- End lastname -->
          		</div>
          		<!-- End .top-row -->

         		<div class="field-wrap">
         			<div data-wow-delay="1.9s" data-wow-duration="1.3s" class="wow fadeInRight">
         				<label>
		              	Username<span class="req">*</span>
			            </label>
			            <input name="username" type="text" required autocomplete="off"/>
         			</div>
         			<!-- End WOW -->
          		</div>
          		<!-- End username -->

          		<div class="field-wrap">
         			<div data-wow-delay="1.9s" data-wow-duration="1.3s" class="wow fadeInRight">
         				<label>
		              	Email Address<span class="req">*</span>
			            </label>
			            <input name="email" type="email" required autocomplete="off"/>
         			</div>
         			<!-- End WOW -->
          		</div>
          		<!-- End email -->
          
          		<div class="field-wrap">
          			<div data-wow-delay="2.1s" data-wow-duration="1.3s" class="wow fadeInRight">
          				<label>
			            Set A Password<span class="req">*</span>
			            </label>
			            <input name="password" type="password" required autocomplete="off"/>
          			</div>
          			<!-- End WOW -->
         		</div>
         		<!-- End password -->
			
				<div class="field-wrap">
					<div data-wow-delay="2.3s" data-wow-duration="1.3s" class="wow fadeInRight">
						<label>
			            Gender<span class="req">*</span>
			            </label>
			            <div id="gender">
			            	<input type="radio" name="gender" value="Male" id="gender-m" checked>
			            	<label for="male" id="label-m">Male</label>  
			            	<input type="radio" name="gender" value="Female" id="gender-fm">
			            	<label for="female" id="label-fm">Female</label>  
			            </div>	
		            <input id = "gender-input" type="text" autocomplete="off" disabled />
					</div>
					<!-- End WOW -->
          		</div>
          		<!-- End gender -->

				<div class="field-wrap">
					<div data-wow-delay="1.2s" data-wow-duration="1.3s" data-wow-offset = "50" class="wow fadeInRight">
						<label>
			            Country<span class="req">*</span>
			            </label>
			            <select name="country" id="country">
							<option class="country-name" value="Viet Nam">Viet Nam</option>
							<option class="country-name" value="Australia">Australia</option>
							<option class="country-name" value="United Statese">United States</option>
							<option class="country-name" value="India">India</option>
							<option class="country-name" value="Other">Other</option>
						</select>
						<input id="country-input" type="text" autocomplete="off" disabled />     
					</div>
					<!-- End WOW -->
          		</div>
          		<!-- End country -->
				
				<div data-wow-delay="1s" data-wow-duration="1.5s" class="wow bounceInLeft">
					<button type="submit" name="submitted" value="1" class="button button-block"/>Get Started</button>	
				</div>
				<!-- End WOW -->
				<!-- End submit -->
					
				<div data-wow-delay="1.5s" data-wow-duration="1.5s" class="wow bounceInRight">
					<button type="reset" class="button-reset button-block"/>Reset</button>
				</div>
				<!-- End WOW -->          		
          		<!-- End reset -->
          
          	</form>
		</div>
        
        <div id="login">  

        	<div data-wow-delay="0.5s" data-wow-duration="2s" class="wow rotateIn">
         		<h1>Welcome Back!</h1>        		
        	</div> 
        	<!-- End WOW -->
          
          	<form action="" method="post">

            <div class="field-wrap">
            	<div data-wow-delay="1s" data-wow-duration="2s" class="wow rotateInDownLeft">
            		<label>
		            Username<span class="req">*</span>
		            </label>
		            <input type="text"required name="username" autocomplete="off"/>
            	</div>
            	<!-- End WOW -->
          	</div>
          	<!-- End login-email -->
          
          	<div class="field-wrap">
          		<div data-wow-delay="1.5s" data-wow-duration="2s" class="wow rotateInDownRight">
          			<label>
		            Password<span class="req">*</span>
		            </label>
		            <input type="password"required name="password" autocomplete="off"/>
          		</div>	            
          	</div>
          	<!-- End login-password -->
          
			<div data-wow-delay="2s" data-wow-duration="2s" class="wow bounceInUp">
				<p class="forgot"><a href="#">Forgot Password?</a></p>
          		<!-- End .forgot -->
			</div>
			<!-- End WOW -->
          		
          	<div data-wow-delay="2.5s" data-wow-duration="2s" class="wow bounceInUp">
        		<button type="submit" name="login" value="1" class="button button-block" name="" />Log In</button>
          		<!-- End login-button -->     		
          	</div>
          	<!-- End WOW -->
          	
          
          	</form>
        </div>
        
      </div><!-- tab-content -->

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>
		$('.form').find('input[type = text], input[type = password], input[type = email], textarea').on('keyup blur focus', function (e) {
		    var $this = $(this), label = $this.prev('label');
		    if (e.type === 'keyup') {
		        if ($this.val() === '') {
		            label.removeClass('active highlight');
		        } else {
		            label.addClass('active highlight');
		        }
		    } else if (e.type === 'blur') {
		        if ($this.val() === '') {
		            label.removeClass('active highlight');
		        } else {
		            label.removeClass('highlight');
		        }
		    } else if (e.type === 'focus') {
		        if ($this.val() === '') {
		            label.removeClass('highlight');
		        } else if ($this.val() !== '') {
		            label.addClass('highlight');
		        }
		    }
		});
		$('.tab a').on('click', function (e) {
		    e.preventDefault();
		    $(this).parent().addClass('active');
		    $(this).parent().siblings().removeClass('active');
		    target = $(this).attr('href');
		    $('.tab-content > div').not(target).hide();
		    $(target).fadeIn(600);
		});
		//@ sourceURL=pen.js
	</script>
	<script src="http://codepen.io/assets/editor/live/css_live_reload_init.js"></script>
</div> <!-- /form -->
</body>
</html>