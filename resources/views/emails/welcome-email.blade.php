{{-- <DOCTYPE html>
    <html lang=”en-US”>
    <head>
    <meta charset=”utf-8">
    </head>
    <body>
        <h2>Welcome Email</h2>
        <p>This is welcome email with login details</p>
        <p>username : {{$data['username']}}</p>
        <p>password : {{$data['password']}}</p>
   </body>
   </html> --}}

   <!DOCTYPE html>
<html>

    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Page Title</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
    </head>

    <body>
        <table cellspacing="0" cellpadding="0" border="0" width="800" style="background-color: white;color: #333333;font-size: 14px;border-collapse: collapse;width: 800px;max-width: 800px;font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;margin: 0 auto;">
            <tbody>
                <tr>
                    <td style="padding: 20px 15px;">

                        <table cellspacing="0" width="800" cellpadding="0" border="0" style="max-width: 800px;width: 800px;border: 0 none;border-collapse: separate;background-color: #ffffff;border-style: solid;border-width: 1px 1px 3px 1px;border-color: #dadce0;border-radius: 0px 0px 8px 8px;border-bottom: solid 3px #d8d8d8;padding: 20px;">
                            <tbody>
                                <tr>
                                    <td style="padding-bottom: 20px;"><img src="https://ci3.googleusercontent.com/mail-sig/AIorK4yWQCPeU4OQT4TeQ9PvSztDwGySxbPYpw2oCJMOslX89018nt6IoriCs9TI_uDNXrXBfNI_8ws" style="display: block;"></td>
                                </tr>

                                <tr>
                                    <td style="font-weight: bold; font-size: 18px; color: #2935e8;">Welcome to CropData's Unified Webapp Platform</td>
                                </tr>

                                <tr>
                                    <td>
                                        <hr />
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom:20px;"></td>
                                </tr>

                                <!-- Dynamic Content Start-->

                                <tr>
                                    <td style="color: #000;">Dear Candidate,</td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom:20px;"></td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom:15px;"></td>
                                </tr>

                                <tr>
                                    <td>
                                        <strong>Welcome and congrats on becomming members of cropdata family.</strong>
                                        <ul>
                                            <li>Username : {{$data['username']}}</li>
                                            <li>Password : {{$data['password']}}</li>
                                        </ul>

                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom:20px;"></td>
                                </tr>

                                <tr>
                                    <td><strong>Using this credential click below button to login:</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom:10px;"></td>
                                </tr>

                                <tr>
                                    <td>
                                        <a href="{{$data['link']}}" target="_blank" style="background-color:#2935e8; color:white;text-decoration: none;padding: 8px 16px;display: inline-block;border-radius: 5px;">Click here to login</a>
                                    </td>
                                </tr>

                                <!-- Dynamic Content End-->

                                <tr>
                                    <td style="padding-bottom:30px;"></td>
                                </tr>

                                <tr>
                                    <td>Thanks & Regards, <br>CropData Technology Pvt. Ltd.</td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom:10px;"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px 15px; text-align: center;">
                        <table cellspacing="0" width="800" cellpadding="0" border="0" style="color: black;font-size: 12px;border-collapse: collapse;width: 800px;max-width: 800px;font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;line-height: 18px;">
                            <tbody>
                                <tr>
                                    <td>The content of this email is confidential and intended for the recipient specified
                                        in message only. It is strictly forbidden to share any part of this message with any
                                        third party, without a written consent of the sender. If you received this message
                                        by mistake, please reply to this message and follow with its deletion, so that we
                                        can ensure such a mistake does not occur in the future.</td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom:10px;"></td>
                                <tr>
                                <tr>
                                    <td>© Copyright 2016-2023 All Rights Reserved. Powered by CropData Technology
                                        Pvt. Ltd.</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>

</html>

