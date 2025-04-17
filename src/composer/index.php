<?php 
//https://github.com/CurosMJ/NoConsoleComposer
include 'password.php';
$base_path = \realpath('../../laravel');
$base_path = \str_replace('\\', '\\\\', $base_path);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>NoConsole</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
		<script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				checkComposer();
			});
			function urlComposer(){
				return 'mainComposer.php';
			}
			function callComposer(func){
				$("#output").append("\nplease wait...\n");
				$("#output").append("\n===================================================================\n");
				$("#output").append("Executing Started");
				$("#output").append("\n===================================================================\n");
				$.post('mainComposer.php',
						{
							"package":$("#packageComposer").val(),
							"command":func,
							"function": "command"
						},
				function(data){
					$("#output").append(data);
					$("#output").append("\n===================================================================\n");
					$("#output").append("Execution Ended");
					$("#output").append("\n===================================================================\n");
				}
				);
			}
			function callBower(func){
				$("#output").append("\nplease wait...\n");
				$("#output").append("\n===================================================================\n");
				$("#output").append("Executing Started");
				$("#output").append("\n===================================================================\n");
				$.post('mainBower.php',
						{
							//"path":$("#path").val(),
							"package":$("#packageBower").val(),
							"command":func,
							"function": "command"
						},
				function(data){
					$("#output").append(data);
					$("#output").append("\n===================================================================\n");
					$("#output").append("Execution Ended");
					$("#output").append("\n===================================================================\n");
				}
				);
			}
			function checkComposer(){
				$("#output").append('\nloading...\n');
				$.post(urlComposer(),
						{
							"function": "getStatus",
							"password": $("#password").val()
						},
				function(data) {
					if (data.composer_extracted){
						$("#output").html("Ready. All commands are available.\n");
						$("button.composer").removeClass('disabled');
						$("button.bower").removeClass('disabled');
					}
					else if(data.composer){
						$.post(urlComposer(),
								{
									"password": $("#password").val(),
									"function": "extractComposer",
								},
								function(data) {
									$("#output").append(data);
									window.location.reload();
								}, 'text');
					}else{
						$("#output").html("Please wait till composer is being installed...\n");
						$.post(urlComposer(),
								{
									"password": $("#password").val(),
									"function": "downloadComposer",
								},
								function(data) {
									$("#output").append(data);
									check();
								}, 'text');
					}
				});
			}
		</script>
		<style>
			#output
			{
				width:100%;
				height:650px;
				overflow-y:scroll;
				overflow-x:hidden;
			} 
			fieldset { 
				display: block;
				margin-left: 2px;
				margin-right: 2px;
				padding-top: 0.35em;
				padding-bottom: 0.625em;
				padding-left: 0.75em;
				padding-right: 0.75em;
				border: 1px groove lightgray;
			}
		</style>
	</head>
	<body>
		<div class="row">
			<div class="col-lg-1"></div> 
			<div class="col-lg-10">
				<h1>NoConsole</h1>
				<hr/>
				<div class="form-inline">
					<fieldset>
						<legend>Composer Commands</legend>
						<button    	onclick="del()" 						class="composer btn btn-success disabled">Self Update</button>
						<input type="text" class="composer form-input" name="package" id="packageComposer">
						<button 	onclick="callComposer('require')" 		class="composer btn btn-success disabled">require</button>
						<button 	onclick="callComposer('remove')" 		class="composer btn btn-success disabled">remove</button>
						<button 	onclick="callComposer('install')" 		class="composer btn btn-success disabled">install</button>
						<button  	onclick="callComposer('update')" 		class="composer btn btn-success disabled">update</button>
						<button   	onclick="callComposer('dump-autoload')" class="composer btn btn-success disabled">dump-autoload</button>
						<button    	onclick="callComposer('show')" 			class="composer btn btn-success disabled">show</button>
						<button    	onclick="callComposer('geoip:update')" 			class="composer btn btn-success disabled">geoip:update</button>
						
					</fieldset>
					<hr/>
					<fieldset>
						<legend>Bower Commands</legend>
						<input type="text" class="bower form-input" name="package" id="packageBower">
						<button 	onclick="callBower('install')" 			class="bower btn btn-info disabled">install</button>
						<button 	onclick="callBower('remove')" 			class="bower btn btn-info disabled">remove</button>
						<button 	onclick="callBower('update')" 			class="bower btn btn-info disabled">update</button>
						<button 	onclick="callBower('list')" 			class="bower btn btn-info disabled">show</button>
					</fieldset>
				</div>  
				<h3>Console Output:</h3>
				<pre id="output" class="well"></pre>
			</div>
			<div class="col-lg-1"></div>
		</div>
	</body>
</html>
