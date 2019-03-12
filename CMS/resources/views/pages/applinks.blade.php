@extends('layouts.app')

{{--@section('template_title')--}}
	{{--See Message--}}
{{--@endsection--}}

@section('head')
@endsection

@section('content')
 <style>
	 nav{display: none}
	 .footer{display: none}
	 .wrapper{padding:0px!important}
 </style>
 <div class="container">
	 <p style="margin-top:20px;text-align: center">App Links</p>
	 <table style="width:100%">
		 <tr>
			 <td width="7%" style="vertical-align: top">1</td>
			 <td width="93%">You must be at least 13 years old to use the Service. </td>
		 </tr>
		 <tr>
			 <td width="7%" style="vertical-align: top">2</td>
			 <td width="93%">You may not post violent, nude, partially nude, discriminatory, unlawful, infringing, hateful, pornographic or sexually suggestive photos or other content via the Service.</td>
		 </tr>
		 <tr>
			 <td width="7%" style="vertical-align: top">3</td>
			 <td width="93%">You are responsible for any activity that occurs through your account and you agree you will not             sell, transfer, license or assign your account, followers, username, or any account rights. With the exception of people or businesses that are expressly authorized to create accounts on behalf of their employers or clients, Dress’d prohibits the creation of and you agree that you will not create an account for anyone other than yourself. You also represent that all information you provide or provided to Dress’d upon registration and at all other times will be true, accurate, current and complete and you agree to update your information as necessary to maintain its truth and accuracy. </td>
		 </tr>
	 </table>
</div>

@endsection