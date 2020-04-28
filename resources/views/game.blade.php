<!DOCTYPE html>
<html>
	
	<head>
		<title>Game</title>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<meta name="_token" content="{{ csrf_token() }}" />
		<script type="text/javascript">
			function sendForm(){

				//var ans = document.getElementById("ans");
				//alert('test');
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					},
					url: "/check",
					// data: {
					// 	ans: ans
					// },
					data: $('#getForm').serialize(),
					type: "POST",
					//dataType: 'text',
					dataType: 'json',
					success: function(data){
						//document.getElementById("res").innerHTML = data;
						document.getElementById("res").innerHTML = data.ansRes;
						document.getElementById("count").innerHTML = data.couRes;
						if(data.ansRes == "Correct Answer"){
							document.getElementById("clear").value = "1";
							document.getElementById("clearCount").value = data.couRes;
							document.getElementById("send").disabled = "disabled";
						}
					},
					error: function(){
						alert("something wrong!");
					},
					async: false,
					cache: true
				});
				//alert('test');
				
			}

	</script>
	</head>


	<body>
		
			<text>請輸入答案:</text>
			<form id="getForm">
				<input type="text" name="ans" id="ans" maxlength="4" required="required" />
				<input type="hidden" name="user" value="{{ $user }}" />
				<input type="button" value="確定" id="send" onclick="sendForm()" />
			</form>

			<br />
			<text id="res"></text><br />
			<text id="count"></text>
			<br />
			<form action="/reset" method="POST">
				{{ csrf_field() }}
				<input type="hidden" name="player" value="{{ $user }}" />
				<input type="hidden" name="clear" id="clear" value="0" />
				<input type="hidden" name="clearCount" id="clearCount" />
				<input type="submit" value="重新一局" id="reset"  />
			</form>
			<br />
			<a href="/">上一頁</a>
		@if(isset($Ans))
			<?php 
				print_r($Ans);
			?>
		@endif
	</body>

</html>