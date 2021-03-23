<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<style>
	.emailbody {
		background-color: #f5f8fa;
		padding-top: 10px;
	}
	body, table, button, a, td {
		font-family: Avenir, Helvetica, sans-serif;
		font-size: 16px;
	}
	.wrapper {
		background-color: #f5f8fa;
		max-width: 800px;
	}
	.header, .footer {
		padding-bottom: 15px;
		padding-top: 10px;
		text-align: left;
	}
	.footer td {
		font-size: 11px;
	}
	.center {
		text-align: center;
	}
	.small {
		font-size:11px;
	}
	.button {
		color: #ffffff !important;
		background-color:#1e7899;
		border-radius: 10px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
		padding: 10px;
		font-size: 115%;
		text-decoration: none;
	}

	.heading {
		border-radius: 10px 10px 0px 0px;
		padding-top: 15px;
		padding-bottom: 15px;
		padding-left: 30px;
		padding-right: 30px;
		border-bottom: 1px solid #19448d;
		background-color: #19448d;
		color: #fff;
		font-size: 20px;
	}
	.body {
		padding-top: 30px;
		padding-bottom: 30px;
		padding-left: 30px;
		padding-right: 30px;
		border-bottom: 1px solid #EDEFF2;
		background-color: #FFFFFF;
	}
	@media only screen and (max-width: 600px) {
/*		.inner-body {
			width: 100% !important;
		}*/

		.footer {
			width: 100% !important;
		}
	}

	@media only screen and (max-width: 500px) {
		.button {
			width: 100% !important;
		}
	}
</style>

<body class="emailbody">
<div class="emailbody">
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td align="center">
			<table class="content" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="header" align="center">
						<a href="{{ config('app.url') }}">
							<img src="https://search.clingen.info/brand/logo/logo-clinical-genome-logo.png" style="height: 100px;" alt="{{ config('app.name') }}">
						</a>
					</td>
				</tr>
				<!-- Email Body -->
				@hasSection ('heading')
				<tr>
					<td class="heading" width="100%" cellpadding="0" cellspacing="0">
									@yield('heading', '')
					</td>
				</tr>
				@endif

				<tr>
					<td class="body" width="100%" cellpadding="0" cellspacing="0">
									@yield('content', '')
					</td>
				</tr>


				@hasSection ('boilerplate')
				<tr>
					<td class="body" width="100%" cellpadding="0" cellspacing="0">
							<hr />
									@yield('boilerplate', '')
					</td>
				</tr>
				@endif


				<tr>
					<td>
						<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0">
							<tr>
								<td class="content-cell" align="center">
									The information on this website is not intended for direct diagnostic use or medical decision-making without review by a genetics professional. Individuals should not change their health behavior solely on the basis of information contained on this website. If you have questions about the information contained on this website, please see a health care professional.<br />
									&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</div>
</html>
