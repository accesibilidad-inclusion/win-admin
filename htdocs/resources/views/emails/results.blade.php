<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Win - Resultados de test de autodeterminación</title>
<style>
html, body {
    font-family: Helvetica, Arial, sans-serif;
    font-size: 16px;
    line-height: 1.5;
    color: #fff;
    background-color: #05739a;
}
#emailContainer {
    background-color: #007faa;
}
#header {
    width: 100%;
    padding-top: 20px;
    padding-bottom: 20px;
    text-align: center;
    background-color: #3e96bb;
}
#main {
    width: 600px;
    padding: 30px;
    color: #fff;
}
#chartContainer {
    padding-top: 20px;
    padding-bottom: 20px;
}
#chart {
    width: 426px;
    height: 426px;
    line-height: 0;
}
.dimension {
    padding: 20px;
    background-color: #459dbf;
    border-bottom: 3px solid #177a9e;
}
.dimension--title {
    font-size: 20px;
}
.dimension--title span {
    vertical-align: top;
    padding-left: 10px;
}
.dimension--description {
    padding-right: 20px;
}
</style>
</head>
<body>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" id="bodyTable">
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="emailContainer">
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td id="header">
                                        <img src="https://admin.apoyos.win/img/newsletter/logo-win.png" alt="">
                                    </td>
                                </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" id="main">
                                <tr>
                                    <td id="chartContainer" align="center">
                                        <div id="chart">@foreach ( $results->dimensions as $dimension )<img src="https://admin.apoyos.win/img/newsletter/dimension-{{ $dimension->id }}-{{ $dimension->level }}.png" alt="{{ $dimension->label }}" width="213" height="213">@endforeach</div>
                                    <td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Estos son tus resultados de WIN:</p><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    @foreach ( $results->dimensions as $dimension )
                                        <table border="0" cellpadding="0" cellspacing="0" class="dimension" width="100%">
                                            <tr>
                                                <td class="dimension--title">
                                                    <img src="https://admin.apoyos.win/img/newsletter/dimension-{{ $dimension->id }}.png" alt="{{ $dimension->label }}" width="25" height="25">
                                                    <span>{{ $dimension->label }}</span>
                                                </td>
                                                <td rowspan="2" class="dimension-chart">
                                                    <img src="https://admin.apoyos.win/img/newsletter/dimension-{{ $dimension->id }}-{{ $dimension->level }}.png" alt="Resultado de dimensión" width="130" height="130">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dimension--description">
                                                    <p>{{ $dimension->aid }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                    @endforeach
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
</html>