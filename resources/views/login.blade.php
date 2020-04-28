<!DOCTYPE html>
<html>
	
	<head>
		<title>Welcome</title>
	</head>


	<body>
		<form action="/game" method="POST">
			{{ csrf_field() }}

			<text>請填入姓名:</text><br />
			<input type="text" name="name" maxlength="15" required="required" />
			<input type="submit" value="送出" />
		</form>

		<br />
		@if(session()->has('mes'))
			@if(session()->get('mes') == '1')
				<text style="color:red">只接受英文和數字</text>
			@endif

			<!-- @if(session()->get('mes') == '2')
				<text style="color:red">Complete</text> -->
			@endif
		@endif
		<br />
		@if(isset($best))
			<text>最高紀錄: {{ $bestPlayer }} {{ $best }}</text>
		@endif

	</body>


</html>