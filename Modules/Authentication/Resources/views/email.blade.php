<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
	<head>
		<meta name ="viewport" content="width=device-width" />
		<meta http-equiv ="Content-Type" content="text/html; charset=UTF-8" />
		<title>User Registration Alert</title>

		<style type="text/css">
			img {
			max-width: 100%;
			}
			body {
			-webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6;
			}
			body {
			background-color: #f6f6f6;
			}
			@media only screen and (max-width: 640px) {
				h1 {
					font-weight: 600 !important; margin: 20px 0 5px !important;
				}
				h2 {
					font-weight: 600 !important; margin: 20px 0 5px !important;
				}
				h3 {
					font-weight: 600 !important; margin: 20px 0 5px !important;
				}
				h4 {
					font-weight: 600 !important; margin: 20px 0 5px !important;
				}
				h1 {
					font-size: 22px !important;
				}
				h2 {
					font-size: 18px !important;
				}
				h3 {
					font-size: 16px !important;
				}
				.container {
					width: 100% !important;
				}
				.content {
					padding: 10px !important;
				}
				.content-wrapper {
					padding: 10px !important;
				}
				.invoice {
					width: 100% !important;
				}
			}
		</style>
	</head>

	<body style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6; background: #f6f6f6; margin: 0; padding: 0;">

	<table class="body-wrap" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background: #f6f6f6; margin: 0; padding: 0;">
		<tr style ="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
			<td style ="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top"></td>
			<td class ="container" width="80%" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 900px !important; clear: both !important; margin: 0 auto; padding: 0;" valign="top">
				<div class ="content" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 900px; display: block; margin: 0 auto; padding: 20px;">
					<table class ="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background: #fff; margin: 0; padding: 0; border: 1px solid #e9e9e9;">
						<tr style ="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
							<td class ="content-wrap" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
								<table width ="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">

									<td colspan="2" style="width:7.75in;border-left:none;border-bottom:solid #7030a0 6.0pt;border-right:none;padding:.0in;width="744" valign="top">
										<p class="MsoNormal"><u></u><u></u></p>
									</td>

									<tr style="height:109.2pt">
										<td style="width:5.0in;border:none;border-top:solid #7030a0 5.0pt;border-bottom:solid #7030a0 1.0pt;padding:.05in .1in .05in .1in;height:109.2pt; color:#000000;" width="480" valign="top">
										<p class="MsoNormal"><span>{{ $user->first_name }},<u></u><u></u></span></p>
										<p class="MsoNormal"><u></u>&nbsp;<u></u></p>
										<p class="MsoNormal" style="padding-bottom: 10px;">
											Welcome to Ion News – the central information portal for all your industry information. The latest industry news, trends, technologies and information collateral are all now right at your fingertips. Enjoy!
											<u></u><u></u>
										</p>
										<p class="MsoNormal" style="padding-bottom: 10px;">
											Your account has been created using&nbsp; 
											<span><a href="mailto:{{ $user->email }}" target="_blank">{{ $user->email }}</a>.</span><u></u><u></u>
										</p>
										<p class="MsoNormal" style="padding-bottom: 10px;">
											Access to the various categories of content will be determined by your company administrators.<u></u><u></u>
										</p>
										<p class="MsoNormal"><u></u>&nbsp;<u></u></p>
										<p class="MsoNormal">- Ion News Team<u></u><u></u></p>
										</td>
										<td style="width:2.75in;border:none;background:#7030a0;padding:.05in .1in .05in .1in;height:109.2pt" width="264">
										<p class="MsoNormal"><span style="color:white">For assistance, feedback &amp; inquiries please contact:<u></u><u></u></span></p>
										<p class="MsoNormal"><u><span><a style="color:skyblue;" href="mailto:ionnews@anionmarketing.com" target="_blank">ionnews@anionmarketing.com</a></span><u></u><u></u></u></p>
										</td>
									</tr>

								</table>
							</td>
						</tr>
					</table>
					<div class ="footer" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
						<table width ="100%" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;">
						</table>
					</div>
				</div>
			</td>
			<td style ="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top"></td>
		</tr>
	</table>

	</body>
</html>
