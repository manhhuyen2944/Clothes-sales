<!DOCTYPE html>
<html lang="">
<head>
	@include('frontend.layouts.head')	
</head>
<body class="js">
	
	@include('frontend.layouts.notification')

	@include('frontend.layouts.header')

	@yield('main-content')
	
	@include('frontend.layouts.footer')

</body>
</html>