<?php 
require_once('functions.php');

$file = 'composer.json';
$json = json_decode(file_get_contents($file), true);

$settings = json_decode(file_get_contents('settings.json'), true);

 
?>

<!DOCTYPE html>
<html dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title> </title>
    <link href="favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="color-picker.min.css" rel="stylesheet">
    <link href="image-picker.css" rel="stylesheet">

    
    <style>
    .color-picker.static {
      display:inline-block !important;
      position:static !important;
      top:0 !important;
      left:0 !important;
    }
    </style>

  </head>
  <body>
  		<div class="col-md-3">
  		<div class="panel-group">

  			<?php 
  			if(isset($settings) && !empty($settings) && is_array($settings)){
  				
          $collapseNo = 1;

  				foreach ($settings as $key => $value) { ?>
  					 
  					<div class="panel panel-default">
		    
				    <div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" href="#collapse<?php echo $collapseNo ?>"> <?php echo $key; ?></a>
				      </h4>
				    </div>
				    <div id="collapse<?php echo $collapseNo ?>" class="panel-collapse collapse">
				     	<div class="panel-body">
				     		<form class="form-inline" id="form<?php echo $collapseNo ?>">
				     	 
				     		<?php
				     		if(is_array($value)){
				     			
                  $allInputTypes = array('text','checkbox','select','dropdown','color_bg','color_text');
				     			
                  foreach ($value['settings'] as $setting) {
				     				 
				     				if(isset($setting['type']) && in_array($setting['type'], $allInputTypes)){
				     					  addInput($setting);
				     				}
                    if(isset($setting['type']) && $setting['type'] == 'image_picker'){
                        addImagePicker($setting);
                    }

				     			}
				     		}
				     		?>
				     		 
							<div class="form-group col-md-12">
								<br/>
								<label class="col-md-2"></label>
								<button class="save" 
                  onclick="saveSettings(this); return false;" 
                  type="submit">
                  Save
                </button> 
							</div>
							</form>
				     		 
				     	</div>
				    </div>
				  </div>
				  </div>

  					<?php 
  					$collapseNo++;
  				}
  			}
  			?>
 

    </div>

	</div>

	<div class="col-md-9">
  		<header>
      <?php 
        $displayHeader = 'none';
        if($json['show_announcement']){
            $displayHeader = 'block';
        }

        echo '<div id="header" style="background-color:'.$json['headerBackground'].'; padding:10px; color:'.$json['headerColor'].'; display:'.$displayHeader.'"> 
         Header </div>';
       
      ?>
    </header>
 	
 	 
  	<nav class="navbar navbar-inverse navbar-static-top">
    <div>
      <div class="navbar-header">
         
        <a class="navbar-brand" href=""><img style="height:30px;" src="<?php echo $json['logo'] ?>" alt="Dispute Bills">
        </a>
      </div>
      <div id="navbar3" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right" style="margin-right:15px;">
          
          	<?php 
          	foreach ($json['menu'] as $value) {
          		  echo '<li class=""><a href="#">'.$value.'</a></li>';	
          	}
          	?>
       
        </ul>
      </div>
      <!--/.nav-collapse -->
    </div>
    <!--/.container-fluid -->
  	</nav>
	 
    <div>
      <img width="950px" height="350px" src="http://via.placeholder.com/950x350" id="header-banner" alt="banner image"/>
    </div>
  	</div>
  	
   
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="color-picker.min.js"></script>
    

    <script src="image-picker.js"></script>

    <script>
      
    var pickersEl = []; 
    
    <?php 
      if(isset($settings) && !empty($settings) && is_array($settings)){
          
          $i = 0;

          foreach ($settings as $key => $value) {

            foreach ($value['settings'] as $setting) {

                if(isset($setting['type']) && $setting['type'] == 'color_bg' || $setting['type'] == 'color_text') {
                  
                  $i++;

                  ?>
                    var elNo = '<?php echo $i ?>';

                    pickersEl[elNo] = document.getElementById('<?php echo $setting['id'] ?>');
                      
                    (new CP(pickersEl[elNo])).on("drag", function(color) {
                         
                        this.target.value = '#' + color;

                        var type = this.target.getAttribute('data');

                        if(type == 'background') {
                          document.getElementById("<?php echo $value['id'] ?>").style.backgroundColor = '#' + color;
                        }
                        else {
                          document.getElementById("<?php echo $value['id'] ?>").style.color = '#' + color;
                        }
                        
                    });
 
                  <?php 
                }
                else if($setting['type'] == 'checkbox') { ?>

                    document.getElementById("<?php echo $setting['id'] ?>").addEventListener("click", function(){
                      
                      var ele = document.getElementById("<?php echo $value['id'] ?>");

                      if(document.getElementById("<?php echo $setting['id'] ?>").checked){
                        ele.style.display = "block";
                      }
                      else {
                        ele.style.display = "none";
                      }

                    });
                     
                    <?php  
                }
                else if($setting['type'] == 'checkbox') { ?>

                  <?php

                }

              }

          }
      }

    ?> 
    
    function saveSettings(element){
        
        var e = $(element);
        var form = e.parents('form').get(0);
        var data = $('#'+form.id).serialize();

        $.post("save-settings.php", data, function(data, status){
            alert(data);
        });

    }

    $(document).ready(function () {

      $('.select-picker').click(function(){
          $(this).next().toggle();
          return false;

      });


      $('body').on('click','.image_picker_image', function(){
          $('#header-banner').attr('src',$(this).attr("src"));
      });    

      $("#banner-image").imagepicker({
          hide_select: true
      });

    });

    </script>

  </body>

</html>