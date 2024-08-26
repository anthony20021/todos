<tr>
    <td style="width:500px" colspan="2" width="500">
        <p>{!! $footer !!}</p><br>
    </td>
</tr>
<tr>
    <td style="width:500px" colspan="2" width="500">
        <span
            style="font-size:12pt; font-family: Arial, sans-serif; font-weight: bold; color: #000000;">{{ ucfirst($user['firstname']) }}
            {{ ucfirst($user['name']) }}
        </span>
    </td>
</tr>
<tr>
    <td style="width:500px; padding-top:5px" colspan="2" width="500"> <span
            style="font-size:10pt; font-family: Arial, sans-serif; color: #000000; padding-bottom:5px; font-weight: bold;">{{ $user['job'] }}</span>
    </td>
</tr>
<tr>
    <td style="width:500px;" colspan="2" width="500">&nbsp;</td>
</tr>
<tr>
    <td style="padding-right:15px; border-right:1px solid #7bc3bb" width="162" valign="top">
        <span>
            <a href="https://www.planfor.fr/" target="_blank">
                <img alt="Logo" style="border:0;max-width:200px" src="https://www.planfor.fr/images/logo_planfor.jpg"
                    width="200" border="0">
            </a>
        </span>
    </td>
    <td style="padding-left:15px; vertical-align: top;">
        <span style="font-family: Arial, sans-serif; color:#000000;">
            <span style="font-weight:bold;">Mobile: </span>{{ $user['mobile_phone'] }}<br>
        </span>
        <span style="font-family: Arial, sans-serif; color:#000000;">
            <span style="font-weight:bold;">Téléphone: </span>{{ $user['office_phone'] }}<br>
        </span>
        <span style="font-family: Arial, sans-serif; color:#000000;">
            <span style="font-weight:bold;">Email: </span>{{ $user['email'] }}<br>
        </span>
    </td>
</tr>
