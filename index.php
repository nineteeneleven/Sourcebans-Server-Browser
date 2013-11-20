<html>
<head>
	<title>Kablowsion Inc Server List - by NineteenEleven</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	<link rel='stylesheet' type='text/css' href='style/style.css'>
	<script>
	$(document).ready(function() {
		$( "#loadingPlayers" ).hide();
	});
		  $(function() {
		    $( document ).tooltip({
		      position: {
		        my: "center bottom-20",
		        at: "center top",
		        using: function( position, feedback ) {
		          $( this ).css( position );
		          $( "<div>" )
		            .addClass( "arrow" )
		            .addClass( feedback.vertical )
		            .addClass( feedback.horizontal )
		            .appendTo( this );
		        }
		      }
		    });
		  });


		function getList(){
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();// code for IE7+, Firefox, Chrome, Opera, Safari
			}else{
				xmlhttp=new ActiveXObject('Microsoft.XMLHTTP'); // code for IE6, IE5
			}

			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					document.getElementById('svList').innerHTML=xmlhttp.responseText;
				}
			}

			xmlhttp.open('GET','ajax/serverlist.php',true);
			xmlhttp.send();
		}
		function getPlayers(ip,port,svNum){
			var e = document.getElementById(svNum);
			if(e.style.display == 'block'){
				//e.style.display = 'none';
				$( "#"+svNum ).hide("blind");
				return;
				
			}else{
				
			

				if (window.XMLHttpRequest){
					xmlhttp=new XMLHttpRequest();// code for IE7+, Firefox, Chrome, Opera, Safari
				}else{
					xmlhttp=new ActiveXObject('Microsoft.XMLHTTP'); // code for IE6, IE5
				}
				//var loadingHTML = "<center><strong>Grabbing Players</strong><br / ><img src='images/ajax-loader-bar.gif'></center>";
				xmlhttp.onreadystatechange=function(){
					if (xmlhttp.readyState<=3) {
						//document.getElementById(svNum).innerHTML= loadingHTML;
						$( "#loadingPlayers" ).show();
					}
					if (xmlhttp.readyState==4 && xmlhttp.status==200){
						//document.getElementById(svNum).innerHTML=xmlhttp.responseText;
						$( "#loadingPlayers" ).hide();
						$( "#"+svNum ).html(xmlhttp.responseText).slideDown('slow').css("display","block");
					}
				}

				xmlhttp.open('GET','ajax/players.php?ip='+ip+'&port='+port,true);
				xmlhttp.send();
			}
		}		
		getList();
		//Need to put javascript function in here, then change the url in serverlist.php to the function name(eg. getPlayers(ip,port))
	</script>
	<meta http-equiv='Content-Type'content='text/html;charset=UTF8'>
</head>
<body>
<div id='wrapper'>
	<div id='svList'><div id='loading'><center><strong>Loading Server List</strong><br / ><img src='images/ajax-loader-bar.gif'></center></div></div>
	<div id='loadingPlayers'><center><strong>Grabbing Players</strong><br / ><img src='images/ajax-loader-bar.gif'></center></div>

</div>



</body>
</html>`