<!DOCTYPE html>
<html>
<head>
  <title>Template</title>
</head>
<body>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody><tr>
                    <td align="center" bgcolor="#f6f6f6">
                        <table style="margin:0 10px" border="0" cellpadding="0" cellspacing="0" width="640">
                            <tbody><tr><td height="20" width="640"></td></tr>

                                <tr>
                                    <td width="640" style="background-color:#1a82e2;padding:20px 30px">
                                        <table border="0" cellpadding="0" cellspacing="0" width="640">
                                            <tbody>
                                                <tr>
                                                    <td align="left" valign="middle" width="350">
                                                       IRC Completed members list
                                                    </td>
                                                    <td width="30"></td>
                                                    <td align="right" valign="middle" width="255">
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                                <tr><td bgcolor="#ffffff" height="30" width="640"></td></tr>
                                

                                <tr>
                                    <td bgcolor="#ffffff" width="640">
                                        <p style="margin-left: 28px;">IRC completed lists for resignation</p>
                                        <table border="0" cellpadding="0" cellspacing="0" width="640">
                                            <tbody>
                                                <tr>
                                                    <td width="30"></td>
                                                    <td width="580">
                                                        <table  border="1" cellpadding="6" style="border-collapse: collapse; width : 100%;" width="100%">
                                                            <tr>
                                                                <td style="">M/ID</td>
                                                                <td style="">Full Name</td>
                                                                <td style="">ICNO</td>
                                                                <td style="">Bank Name</td>
                                                                <td style="">Branch</td>
                                                                <td >Status</td>
                                                            </tr>
                                                            @php
                                                                $irclist = CommonHelper::getIrcMailList();
                                                            @endphp
                                                            @foreach($irclist as $irc)
                                                                <tr>
                                                                    <td>{{ $irc->member_number }}</td>
                                                                    <td>{{ $irc->resignedmembername }}</td>
                                                                    <td>{{ $irc->icno }}</td>
                                                                    <td>{{ $irc->bankname }}</td>
                                                                    <td>{{ $irc->branchname }}</td>
                                                                    <td>{{ $irc->status_name }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                       
                                                       
                                                        
                                                        
                                                    </td>
                                                    <td width="30"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        
</body>
</html>